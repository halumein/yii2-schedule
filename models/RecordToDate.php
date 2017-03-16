<?php

namespace halumein\schedule\models;

use Yii;

/**
 * This is the model class for table "schedule_record_to_date".
 *
 * @property int $id
 * @property int $record_id
 * @property string $date
 * @property string $description
 *
 */

class RecordToDate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_record_to_date';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id'], 'required'],
            [['record_id'], 'integer'],
            [['date'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_id' => 'ID Записи',
            'date' => 'На какое число запись',
            'description' => 'Описание',
        ];
    }

    public function getRecord()
    {
        return $this->hasOne(Record::className(),['record_id' => 'id']);
    }

}
