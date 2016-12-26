<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use halumein\schedule\helpers\RenderButtonHelper;
/* @var $this yii\web\View */
/* @var $model app\vendor\halumein\schedule\models\Schedule */

$this->title = (!empty($model->name)) ? $model->name : 'Какое-то расписание без названия';
?>
<div class="schedule-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-condensed">
            <?php foreach ($days as $day => $dayId) { ?>
                <tr class="info">
                    <th data-day="<?= $dayId ?>"><?= $day ?></th>
                    <th></th>
                    <th>Места</th>
                </tr>
                <?php foreach ($model->getActivePeriods($dayId)->all() as $period) { ?>
                        <tr>
                        <td><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?></td>
                        <td>
                            <?php foreach($period->getRecordByUserId() as $record) {
                                echo RenderButtonHelper::renderButton($record);
                                echo "<br>";
                            } ?>
                        </td>
                        <td>Мест: <?= $period->amount ?></td>
                    <?php }?>
                <?php  } ?>
                </tr>
        </table>
    </div>

</div>
