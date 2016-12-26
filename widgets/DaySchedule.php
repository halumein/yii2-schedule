<?php
namespace halumein\schedule\widgets;

use halumein\schedule\models\Period;
use halumein\schedule\models\Schedule;
use halumein\schedule\models\Time;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii;

class DaySchedule extends \yii\base\Widget
{
    
    public $scheduleId = null;
    
    public $days = [
            'Monday' => 0,
            'Tuesday' => 1,
            'Wednesday' => 2,
            'Thursday' => 3,
            'Friday' => 4,
            'Saturday' =>  5,
            'Sunday' => 6,
        ];
    
    public function run()
    {
        $timeList = ArrayHelper::map(Time::find()->all(),'id','time');
        $schedule = Schedule::findOne($this->scheduleId);
        //$periods = Period::find()->where(['schedule_id' => $this -> scheduleId, 'day_id' => $this->days[date('l')]]);
        return $this->render('view',[
            'schedule' => $schedule,
            'timeList' => $timeList,
            'days' => $this->days,
        ]);
    }
}