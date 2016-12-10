<?php
namespace halumein\schedule;

use yii\base\Component;
use halumein\schedule\models\ScheduleRecord;
use halumein\schedule\models\SchedulePeriod;

class Schedule extends Component
{
    public function addRecord($scheduleId,$periodId)
    {
        if ($model = ScheduleRecord::find()->where(
            [
                'schedule_id' => $scheduleId,
                'period_id' => $periodId,
                'user_id' => \Yii::$app->user->id,
            ])->one()) {
            $model->status = 'in process';
        } else {
            $model = new ScheduleRecord();
            $model->schedule_id =  $scheduleId;
            $model->period_id =  $periodId;
            $model->user_id =  \Yii::$app->user->id;
            $model->status = 'in process';
        }
        if ($model->validate() && $model->save()) {
            return $model->id;

        } else {
            return false; 
        } 
    }
    
    public function deleteRecord($scheduleId,$periodId)
    {
        $model = ScheduleRecord::find()->where([
            'schedule_id' => (int) $scheduleId,
            'period_id' => (int) $periodId,
            'user_id' => \Yii::$app->user->id
        ])->one();
        if ($model->delete()) {
            return true;
        } else { return false; }
    }
    
    public function addPeriod($days,$scheduleId,$times)
    {
        foreach ($days as $day => $dayPeriods) {
            foreach ($dayPeriods as $indexPeriod => $period) {
                if ($period->periodId != 'NULL') {
                    $model = SchedulePeriod::findOne($period->periodId);
                } else {
                    $model = new SchedulePeriod();
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
    
    public function updateRecord($recordId,$status)
    {
        $record = ScheduleRecord::find()->where(['id' => $recordId])->one();
        $record->status = $status;
        return $record->update();
    }

    public function getPlaces($scheduleId,$periodId)
    {
        $recordsCount = ScheduleRecord::find()->where([
            'schedule_id' => $scheduleId,
            'period_id' => $periodId,
            'status' => 'confirmed'
        ])->count();
        $amount = SchedulePeriod::findOne($periodId)->amount - $recordsCount;

        return $amount;
    }
    
    public function updatePlaces()
    {
        
    }
    

}
