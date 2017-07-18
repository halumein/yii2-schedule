<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Url;
use halumein\schedule\helpers\RenderButtonHelper;

?>
<div class="schedule-view" style="height: auto">
    <h4>Расписание на <?= $day['dayName'] ?> | <?= $date ?></h4>
        <table class="table table-bordered">
            <thead>
            <th>Время</th>
            <th></th>
            <th>Места</th>
            </thead>
            <tbody>
            <?php foreach ($periods as $period) { ?>
                <tr class="period-row" data-period-id="<?= $period->id ?>">
                    <td><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?></td>
                    <td class="record-list">
                        <?php foreach($period->getRecordsByDate($date) as $record) {
                            echo RenderButtonHelper::renderDatedRecordBlock($record,$period->schedule_id,$period->id,$date);
                        } ?>
                        <a  class="schedule-link <?= (\Yii::$app->schedule->getPlaces($period->schedule_id,$period->id,$date) != 0) ? '' : 'hidden' ?>"
                            data-role="show-record-to-date-modal"
                            data-time-title="<?= $day['dayName'] ?>: <?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?>"
                            data-period-id=<?= $period->id ?>
                            data-date="<?= $date ?>"
                            data-schedule-id=<?= $period->schedule_id ?>>
                            Записать</a>
                    </td>
                    <td class="places">Мест:
                        <label>
                            <span data-role="places">
                              <?= \Yii::$app->schedule->getPlaces($period->schedule_id,$period->id,$date) ?>
                            </span>
                        </label>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <div data-role="record-to-date-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Записать на время <span data-role="record-to-date-time-label"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-offset-2 col-md-8">
                            <input class="form-control text-center" type="text" data-role="record-date" readonly>
                            <br>
                            <input class="form-control text-center" type="text" name="" value="" placeholder="Заголовок" data-role="record-to-date-name">
                            <br>
                            <textarea class="form-control" name="name" rows="8" cols="80" data-role="record-to-date-text" placeholder="Дополнительная информация"></textarea>
                            <br>
                            <button class="btn btn-primary col-sm-12"
                                    data-role="sign-record-to-date"
                                    data-url="<?= Url::to(['/schedule/record/add-custom']) ?>"
                                    data-schedule-id="0"
                                    data-period-id="0"
                                    type="button"
                                    name="button">Записать</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>

        </div>
    </div>
</div>
