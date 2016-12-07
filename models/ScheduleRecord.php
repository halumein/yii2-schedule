<?php

namespace halumein\schedule\models;

use Yii;

/**
 * This is the model class for table "schedule_record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $schedule_id
 * @property integer $period_id
 * @property string $status
 */
class ScheduleRecord extends \yii\db\ActiveRecord
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
            [['user_id', 'schedule_id', 'period_id', 'status'], 'required'],
            [['user_id', 'schedule_id', 'period_id'], 'integer'],
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
            'user_id' => 'User ID',
            'schedule_id' => 'Schedule ID',
            'period_id' => 'Period ID',
            'status' => 'Status',
        ];
    }
    
}
