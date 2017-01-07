<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use halumein\schedule\helpers\RenderButtonHelper;
/* @var $this yii\web\View */
/* @var $model app\vendor\halumein\schedule\models\Schedule */

$this->title = (!empty($model->name)) ? $model->name : 'Басписание без названия';
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
                <tr class="period-row" data-period-id="<?= $period->id ?>">
                    <td class="time"><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?>
                    </td>
                    <td class="record-list">
                        <?php foreach($period->getRecords() as $record) {
                            echo RenderButtonHelper::renderOwnerRecordBlock($record,$model->id,$period->id);
                        } ?>

                        <div class="dropdown">
                          <a class="href">Записать</a>
                          <div class="dropdown-content">
                            <a
                                data-role="show-sign-object-modal"
                                data-period-id=<?= $period->id ?>
                                data-schedule-id=<?= $model->id ?>>
                                Из списка</a>
                            <a
                                data-role="show-sign-custom-object-modal"
                                data-time-title="<?= $day ?>: <?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?>"
                                data-period-id=<?= $period->id ?>
                                data-schedule-id=<?= $model->id ?>>
                                Ввести</a>
                        </div>
                      </div>
                    </td>
                    <td class="places">Мест:
                        <label>
                            <span data-role="places">
                              <?= \Yii::$app->schedule->getPlaces($model->id,$period->id) ?>
                            </span>
                        </label>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
        </table>
    </div>

    <!-- Sign Object Modal -->
    <div data-role="sign-object-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Записать на время</h4>
                </div>
                <div class="modal-body">
                    <p>Контент</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Sign Custom Object Modal -->
    <div data-role="sign-custom-object-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Записать на время <span data-role="custom-record-time-label"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-offset-2 col-md-8">
                            <input class="form-control" type="text" name="" value="" placeholder="Заголовок" data-role="custom-record-name">
                            <br>
                            <textarea class="form-control" name="name" rows="8" cols="80" data-role="custom-record-text" placeholder="Дополнительная информация"></textarea>
                            <br>
                            <button class="btn btn-primary col-sm-12"
                                    data-role="sign-custom-object"
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
