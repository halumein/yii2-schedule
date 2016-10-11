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
        ],
        //...
    ]
```

