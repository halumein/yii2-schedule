<?php

namespace halumein\schedule\models;

use Yii;
use halumein\schedule\models\RecordToDate;
/**
 * This is the model class for table "schedule_record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $schedule_id
 * @property integer $period_id
 * @property string $status
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule_id', 'period_id', 'status'], 'required'],
            [['user_id', 'schedule_id', 'period_id', 'amount'], 'integer'],
            [['status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Id', // Пользователь под которым создана запись
            'client_model' => 'Client model', // Модель объекта записи
            'client_id' => 'Client ID', // Ид объекта записи
            'schedule_id' => 'Schedule ID',
            'period_id' => 'Period ID',
            'status' => 'Status',
            'amount' => 'Amount',
        ];
    }

    public function getClient()
    {
        $client = $this->client_model;
        $client = new $client();
        return $this->hasOne($client::className(), ['id' => 'client_id']);

    }

    public function getRecordToDate()
    {
        return $this->hasOne(RecordToDate::className(), ['record_id' => 'id']);
    }
    
    public function getSchedule()
    {
        return $this->hasOne(Schedule::className(), ['id' => 'schedule_id'])->andWhere(['active' => Schedule::STATUS_ACTIVE]);
    }

}
