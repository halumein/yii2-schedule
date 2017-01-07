<?php

namespace halumein\schedule\models;

use Yii;

class CustomRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'schedule_custom_record';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'text'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'text' => 'Text',
        ];
    }
}
