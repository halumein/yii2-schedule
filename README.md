Yii2-repot
==========


Модуль отчётов


```
php composer require halumein/yii2-report "*"
```

миграция:

```
php yii migrate --migrationPath=vendor/halumein/yii2-report/migrations
```

В конфигурационный файл приложения добавить модуль test

```php
    'modules' => [
        'report' => [
            'class' => 'halumein\report\Module',
        ],
        //...
    ]
```

