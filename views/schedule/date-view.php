<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\DetailView;
use halumein\schedule\helpers\RenderButtonHelper;

$this->title = 'Запись на дату';
$this->params['breadcrumbs'][] = ['label' => 'Расписания', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Список расписаний', 'url' => ['schedule-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-view">

    <div class="hiddent"
        data-role="schedule-record-on-date-settings"
        data-choose-client-modal-content-url="<?= Url::to(['/schedule/schedule/client-choose-ajax']) ?>"
        data-delete-record-url="<?= Url::to(['/schedule/record/delete-ajax']) ?>"
        >

    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?= Html::dropDownList('schedules-list',null,$schedules,['data-role' => 'schedules-list', 'class' => 'form-control']) ?>
                </div>
                <div class="form-group text-center">
                    <?=
                    DatePicker::widget([
                        'name' => 'record-date-picker',
                        'type' => DatePicker::TYPE_INLINE,
                        'value' => date('d.m.Y'),
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy'
                        ],
                        'options' => [
                            'class' => 'hide',
                            'data-url' => Url::to(['find-records-ajax']),
                            'data-role' => 'records-date-value'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-md-9" data-role="schedule-on-day">
            </div>
        </div>
    </div>
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
