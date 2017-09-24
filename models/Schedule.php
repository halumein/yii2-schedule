<?php

namespace halumein\schedule\models;

use Yii;

/**
 * This is the model class for table "schedule_schedule".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property string $target_model
 * @property integer $target_id
 * @property string $name
 * @property string $date
 */
class Schedule extends \yii\db\ActiveRecord
{

    public $periodsArray;

    public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\linker\LinkerBehavior::className(),
                'relations' => [
                    'user_ids' => 'users',
                ],
            ],
        ];
    }


    public static function tableName()
    {
        return 'schedule_schedule';
    }

    public function rules()
    {
        return [
            [['owner_id', 'target_model', 'target_id'], 'required'],
            [['owner_id', 'target_id'], 'integer'],
            [['date'], 'safe'],
            [['target_model', 'name'], 'string', 'max' => 255],
            [['periodsArray'],'string'],
            [['user_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Создатель',
            'target_model' => 'Модель',
            'target_id' => 'Экземпляр модели',
            'name' => 'Имя',
            'date' => 'Уникальная дата',
        ];
    }

    public function getUsers()
    {
        $userModel = Yii::$app->getModule('schedule')->userModel;
        return $this->hasMany($userModel::className(), ['id' => 'user_id'])->viaTable('schedule_user_to_schedule', ['schedule_id' => 'id']);
    }

   public function getPeriods()
   {
       return $this->hasMany(Period::className(), ['schedule_id' => 'id']);

   }

   public function getActivePeriods($dayId = null)
   {
       $dayId = (int)$dayId;
       $periods = $this->hasMany(Period::className(), ['schedule_id' => 'id'])->andWhere(['status' => 'active']);
       if (!is_null($dayId) && $dayId >= 0){
           $periods->andWhere(['day_id' => $dayId]);
       }

       return $periods->orderBy(['time_start' => SORT_ASC]);
   }


   public function getPlaceAmount()
   {
       $amount = Record::find()->count(['schedule_id' => $this->id]);
   }

   public function getTargetName()
   {
       $model = $this->target_model;
       $model = $model::findOne($this->target_id);
       $title = Yii::$app->getModule('schedule')->sourceTitleColumnName;

       return $model->{$title};
   }
}
