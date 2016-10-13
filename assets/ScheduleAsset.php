<?php
namespace halumein\schedule\assets;

use yii\web\AssetBundle;

class ScheduleAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public $js = [
        'js/schedule.js',
    ];

//    public $css = [
//        'css/styles.css',
//    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }
}
