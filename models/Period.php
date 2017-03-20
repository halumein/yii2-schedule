<?php

namespace halumein\schedule\models;

use Yii;
use yii\helpers\ArrayHelper;

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
class Period extends \yii\db\ActiveRecord
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
            [['time_start'], 'exist', 'skipOnError' => true, 'targetClass' => Time::className(), 'targetAttribute' => ['time_start' => 'id']],
            [['time_stop'], 'exist', 'skipOnError' => true, 'targetClass' => Time::className(), 'targetAttribute' => ['time_stop' => 'id']],
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
        return Record::find()->where(['period_id' => $this->id])->all();
    }

    public function getRecordsByDate($date)
    {

        $date = date('Y-m-d',strtotime($date));

        $recordIds = ArrayHelper::getColumn(RecordToDate::find()->select('record_id')->where(['date' => $date])->all(),'record_id');
//        echo '<pre>';
//        var_dump($recordIds);
//        var_dump($this->id);
//        var_dump(Record::find()->where(['id' => $recordIds])->andWhere(['period_id' => $this->id])->all());

        return Record::find()->where(['period_id' => $this->id, 'id' => $recordIds])->all();
    }


    public function getRecordByUserId()
    {
        return Record::find()->where(['period_id' => $this->id, 'user_id' => \Yii::$app->user->id])->all();
    }
}
