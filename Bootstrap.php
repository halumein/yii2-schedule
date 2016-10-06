<?php
namespace halumein\schedule;

use yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('schedule')) {
            $app->set('schedule', ['class' => 'halumein\schedule\Schedule']);
        }
    }
}
