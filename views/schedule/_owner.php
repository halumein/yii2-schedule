<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use halumein\schedule\helpers\RenderButtonHelper;
/* @var $this yii\web\View */
/* @var $model app\vendor\halumein\schedule\models\ScheduleSchedule */

$this->title = (!empty($model->name)) ? $model->name : 'Какое-то расписание без названия';
$this->params['breadcrumbs'][] = ['label' => 'Расписания', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Список расписаний', 'url' => ['schedule-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-condensed schedule-table">
            <?php foreach ($days as $day => $dayId) { ?>
            <tr class="info">
                <th class="day" data-day="<?= $dayId ?>"><?= $day ?></th>
                <th></th>
                <th class="places">Места</th>
            </tr>
            <?php foreach ($model->getActivePeriods($dayId)->all() as $period) { ?>
            <tr class="period-row">
                <td class="day"><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?></td>
                <td >
                    <?php foreach($period->getRecords() as $record) {
                        echo RenderButtonHelper::renderOwnerRecordBlock($record,$model->id,$period->id);
                    } ?>

                </td>
                <td class="places">Мест:
                    <label>
                        <span data-role="places">
                          <?= \Yii::$app->schedule->getPlaces($model->id,$period->id) ?>
                        </span>                        
                    </label>
                </td>
                <?php }?>
                <?php  } ?>
            </tr>
        </table>
    </div>

</div>