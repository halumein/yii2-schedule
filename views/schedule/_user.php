<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use halumein\schedule\helpers\RenderButtonHelper;
/* @var $this yii\web\View */
/* @var $model app\vendor\halumein\schedule\models\ScheduleSchedule */

$this->title = (!empty($model->name)) ? $model->name : 'Какое-то расписание без названия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-view">
    <h1><?= Html::encode($this->title) ?></h1>
        <table class="table table-hover schedule-table">
            <?php foreach ($days as $day => $dayId) { ?>
            <tr class="info">
                <th class="day" data-day="<?= $dayId ?>"><?= $day ?></th>
                <th class="actions" style="width: 250px;">Действие</th>
                <th class="places">Места</th>
            </tr>
            <?php foreach ($model->getActivePeriods($dayId)->all() as $period) { ?>
            <tr>
                <td class="day"><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?></td>
                <td class="actions">
                    <?php if ($period->getRecordByUserId()){ foreach($period->getRecordByUserId() as $record) {
                        echo RenderButtonHelper::renderButton($record,$model->id,$period->id);
                    } } else {
                        echo RenderButtonHelper::renderButton(null,$model->id,$period->id);
                    }
                    ?>
                </td>
                <td class="places">Свободных мест: <label><?= \Yii::$app->schedule->getPlaces($model->id,$period->id) ?></label></td>
                <?php }?>
                <?php  } ?>
            </tr>
        </table>


</div>