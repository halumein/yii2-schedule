<?php

namespace halumein\schedule\models;

use pistol88\worksess\models\Schedule;
use Yii;

/**
 * This is the model class for table "schedule_period".
 *
 * @property integer $id
 * @property integer $schedule_id
 * @property integer $day_id
 * @property integer $time_start
 * @property integer $time_stop
 * @property string $status
 */
class SchedulePeriod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule_id', 'day_id', 'time_start', 'time_stop', 'status'], 'required'],
            [['schedule_id', 'day_id', 'time_start', 'time_stop', 'amount'], 'integer'],
            [['status'], 'string'],
            [['schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScheduleSchedule::className(), 'targetAttribute' => ['schedule_id' => 'id']],
            [['time_start'], 'exist', 'skipOnError' => true, 'targetClass' => ScheduleTime::className(), 'targetAttribute' => ['time_start' => 'id']],
            [['time_stop'], 'exist', 'skipOnError' => true, 'targetClass' => ScheduleTime::className(), 'targetAttribute' => ['time_stop' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'schedule_id' => 'Schedule ID',
            'day_id' => 'Day ID',
            'time_start' => 'Time Start',
            'time_stop' => 'Time Stop',
            'status' => 'Status',
            'amount' => 'Amount',
        ];
    }
    
    public function getPeriods($dayId)
    {
        return $this->find()->where(['day_id' => $dayId])->all();
    }

    public function getRecords()
    {
        return ScheduleRecord::find()->where(['period_id' => $this->id])->all();    
    }
    
    
    public function getRecordByUserId() 
    {
        return ScheduleRecord::find()->where(['period_id' => $this->id, 'user_id' => \Yii::$app->user->id])->all();
    }
}
