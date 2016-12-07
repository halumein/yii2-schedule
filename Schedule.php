<?php
namespace halumein\schedule;

use yii\base\Component;
use halumein\schedule\models\ScheduleRecord;
use halumein\schedule\models\SchedulePeriod;

class Schedule extends Component
{
    public function addRecord($scheduleId,$periodId)
    {
        $model = new ScheduleRecord();
        $model->schedule_id = (int) $scheduleId;
        $model->period_id = (int) $periodId;
        $model->user_id =  \Yii::$app->user->id;
        $model->status = 'in process';
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

}
