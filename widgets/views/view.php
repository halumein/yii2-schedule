<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use halumein\schedule\helpers\RenderButtonHelper;

?>
<div class="schedule-view">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-condensed schedule-table">
            <tr class="info">
                <th class="day"><?= date('l') ?></th>
                <th>Записавшиеся</th>
                <th class="places">Места</th>
            </tr>
            <?php foreach ($schedule->getActivePeriods($days[date('l')])->all() as $period) { ?>
            <tr class="period-row">
                <td class="day"><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?></td>
                <td >
                    <?php foreach($period->getRecords() as $record) {
                        echo RenderButtonHelper::renderOwnerUserRecord($record);
                    } ?>

                </td>
                <td class="places">Мест осталось:
                    <label>
                        <span data-role="places">
                          <?= \Yii::$app->schedule->getPlaces($schedule->id,$period->id) ?>
                        </span>
                    </label>
                </td>
                <?php }?>
            </tr>
        </table>
    </div>

</div>