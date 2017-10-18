<?php
namespace halumein\schedule;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use halumein\schedule\models\Record;
use halumein\schedule\models\CustomRecord;
use halumein\schedule\models\Period;
use halumein\schedule\models\RecordToDate;

class Schedule extends Component
{
    public $clientModel = null;

    public function addRecord($scheduleId,$periodId, $status = null, $clientModel = null, $clientId = null)
    {
        if ($model = Record::find()->where(
            [
                'schedule_id' => $scheduleId,
                'period_id' => $periodId,
                'client_model' => $clientModel,
                'client_id' => $clientId,
                'user_id' => \Yii::$app->user->id,
            ])->one()) {
            $model->status = 'in process';
        } else {
            $model = new Record();
            $model->schedule_id =  $scheduleId;
            $model->period_id =  $periodId;
            $model->client_model =  $clientModel;
            $model->client_id =  $clientId;
            $model->user_id =  \Yii::$app->user->id ? \Yii::$app->user->id : 0;
            if ($status) {
                $model->status = $status;
            } else {
                $model->status = 'in process';
            }
        }
        if ($model->validate() && $model->save()) {
            return $model->id;
        } else {
            return false;
        }
    }

    public function deleteRecord($recordId)
    {
        $model = Record::findOne($recordId);
        if ($model->delete()) {

            RecordToDate::deleteAll(['record_id' => $recordId]);

            return true;
        } else { return false; }
    }

    public function addPeriod($days,$scheduleId,$times)
    {
        foreach ($days as $day => $dayPeriods) {

            foreach ($dayPeriods as $indexPeriod => $period) {
                if ($period->periodId != 'NULL') {
                    $model = Period::findOne($period->periodId);
                } else {
                    $model = new Period();
                }
                if  ($model) {
                    $model->schedule_id = $scheduleId;
                    $model->day_id = $day;
                    $model->status = $period->status;
                    $time = explode('-',$period->time);
                    $model->time_start = $times[trim($time[0])];
                    $model->time_stop = $times[trim($time[1])];
                    $model->amount = $period->amount;

                    if ($model->validate() && $model->save()){
                        // do nothing
                    } else var_dump($model->getErrors());
                }
            }
        }
    }

    public function addRecordToDate($recordId,$date,$description = '')
    {

        $model  = new RecordToDate();

        $model->record_id = $recordId;
        $model->date = date('Y-m-d',strtotime($date));
        $model->description = $description;

        if ($model->save()) {
            return [
              'status' => 'success',
            ];
        }
    }

    public function updateRecord($recordId,$status)
    {

        $record = Record::find()->where(['id' => $recordId])->one();
        $record->status = $status;

        return $record->update();
    }

    public function getPlaces($scheduleId,$periodId,$date = null)
    {

        if ($date == null) {
            $recordsCount = Record::find()->where([
                'schedule_id' => $scheduleId,
                'period_id' => $periodId,
                'status' => 'confirmed'
            ])->count();

        } else {
            $date = date('Y-m-d',strtotime($date));

            $recordIds = ArrayHelper::getColumn(RecordToDate::find()->select('record_id')->where(['date' => $date])->all(),'record_id');

            $recordsCount = Record::find()->where([
                'id' => $recordIds,
                'schedule_id' => $scheduleId,
                'period_id' => $periodId,
                'status' => 'confirmed'
            ])->count();
        }

        $amount = Period::findOne($periodId)->amount - $recordsCount;

        return $amount;
    }

    public function addCustomRecord($name, $text, $scheduleId, $periodId, $status)
    {
        $customRecord = new CustomRecord;
        $customRecord->name = $name;
        $customRecord->text = $text;
        if ($customRecord->save()) {

            $scheduleId = $scheduleId;
            $periodId = $periodId;

            $clientModel = $customRecord::className();
            $clientId = $customRecord->id;

            $status = $status;

            $recordId = \Yii::$app->schedule->addRecord( (int)$scheduleId,(int)$periodId, $status, $clientModel, $clientId);

            if ($recordId){
                return $recordId;
            } else {
                return false;
            }
        } else {
            var_dump($customRecord->getErrors());
            return false;
        }
    }

    public function checkFreeSlots($periodId, $date, $amount = 1)
    {
        $periodModel = Period::findOne($periodId);

        $totalAmountAvailable = $periodModel->amount;

        $records = Record::find()->where([
                'schedule_id' => $periodModel->schedule_id,
                'period_id' => $periodId,
                'status' => 'confirmed'])->all();

        // занятого времени нет - значит свободно
        if (!$records) {
            return true;
        }

        $recordIds = ArrayHelper::getColumn($records, 'id');

        $recordsToDate = RecordToDate::find()->where(['record_id' => $recordIds, 'date' => $date])->all();

        $recordIds = ArrayHelper::getColumn($recordsToDate, 'record_id');

        $slotReservAmount = Record::find()->where(['id' => $recordIds, 'status' => 'confirmed'])->sum('amount');
        if  (((int)$totalAmountAvailable - (int)$slotReservAmount) >= $amount) {
            return true;
        }

        return false;

    }

    public function clearPeriods($day, $scheduleId)
    {
        $command = \Yii::$app->db->createCommand()
            ->update('schedule_period', ['status'=> 'deleted'],
                ['id' => ArrayHelper::getColumn(
                    Period::find()->where(['day_id' => $day, 'schedule_id' => $scheduleId])->select('id')->all(), 'id'
                )])
            ->execute();

    }
}
