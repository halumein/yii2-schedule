<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\vendor\halumein\schedule\models\Schedule */

$this->title = 'Список расписаний';
$this->params['breadcrumbs'][] = ['label' => 'Расписания', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-list">
    <h3>Старый вид</h3>
    <?php foreach ($schedules as $schedule) { ?>
            <p>
                <a class="btn btn-default" href="<?= Url::to(['/schedule/schedule/view', 'id' => $schedule->id]) ?>">
                    <?php if (!empty($schedule->name)) { echo $schedule->name; } else echo 'какое-то расписание без названия'; ?>
                </a>
            </p>
    <?php } ?>
    <h3>Запись на дату</h3>
    <?php foreach ($schedules as $schedule) { ?>
            <p>
                <a class="btn btn-default" href="<?= Url::to(['/schedule/schedule/date-view', 'id' => $schedule->id]) ?>">
                    <?php if (!empty($schedule->name)) { echo $schedule->name; } else echo 'какое-то расписание без названия'; ?>
                </a>
            </p>
    <?php } ?>
</div>
