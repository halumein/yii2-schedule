<?php

namespace halumein\schedule\models;

use Yii;

/**
 * This is the model class for table "schedule_user_to_schedule".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $schedule_id
 */
class ScheduleUserToSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_user_to_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'schedule_id'], 'required'],
            [['user_id', 'schedule_id'], 'integer'],
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
        ];
    }
}
