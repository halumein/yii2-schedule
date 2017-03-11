Yii2-schedule
==========


Модуль расписаний


```
 composer require halumein/yii2-schedule "*"

```

миграция:

```
php yii migrate --migrationPath=vendor/halumein/yii2-schedule/migrations
```

В конфигурационный файл приложения добавить модуль test

```php
    'modules' => [
        'schedule' => [
            'class' => 'halumein\schedule\Module',
            'userModel' => 'namespace\to\userModel' \\модель пользователей, которые будут работать с расписанием
            'sourceList' => [
                'namespace\to\someModel' => 'modelName'
                \\...
            ], \\ список моделей, для которых будет создаваться расписание
        ],
        //...
    ]
```

