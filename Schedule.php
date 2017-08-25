<?php
namespace halumein\schedule;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use halumein\schedule\models\Record;
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
            $model->user_id =  \Yii::$app->user->id;
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

}
