<?php

namespace halumein\schedule;

use Yii;


class Module extends \yii\base\Module
{
    const EVENT_RECORD_CREATE = 'record_create';
    const EVENT_RECORD_DELETE = 'record_delete';
    
    public $componentList = null;
    public $sourceList = null; // массив моделей для которых буду создаваться расписания
    public $sourceTitleColumnName = 'title'; // колонка из которй будет браться название сущности, для которой создаётся расписание
    public $userModel = null; // модель пользователя, для разграничения прав
    public $clientSearchModel = null; // используется для записи занесённых ранее клиентов


    public function init()
    {
        parent::init();

    }

}
