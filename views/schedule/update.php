<?php

\halumein\schedule\assets\ScheduleAsset::register($this);

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\vendor\halumein\schedule\models\ScheduleSchedule */

$this->title = (!empty($model->name)) ? $model->name : 'Новое расписание';
?>
<div class="schedule-schedule-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'options' =>[
            'data-role' => 'schedule-form',
        ]
    ]);
    ?>
    <div class="row container-fluid">
        <div class="col-md-4">
            <?= $form->field($model,'name')->input('text')->label('Название расписания'); ?>
            
            <?= $form->field($model, 'target_model')->dropDownList($targetList,[
                'data-role' => 'targetModelList',
                'data-url' => Url::to(['/schedule/schedule/get-targets-by-model']),
            ])->label('Расписание для:')->hint('Выберите модель, для которой будет создано расписание'); ?>

            <?= $form->field($model, 'target_id')->dropDownList([],[
                'data-role' => 'targetId',
                'data-id' => ($model->target_id != '') ? $model->target_id : '',
            ])->label(false)->hint('Выберите экземпляр модели'); ?>

            <?php
            echo $form->field($model, 'user_ids')->label('Пользователи, которые имеют доступ')
                ->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($users, 'faq_id', 'faq_title'),
                    'language' => 'ru',
                    'options' => ['multiple' => true, 'placeholder' => 'Выберите пользователей ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
            
            <?= $form->field($model,'periodsArray')->input('hidden',['data-role' => 'periods-array'])->label(false); ?>
        </div>
        <div class="col-md-8 text-center">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#monday">Понедельник</a></li>
                <li><a data-toggle="tab" href="#tuesday">Вторник</a></li>
                <li><a data-toggle="tab" href="#wednesday">Среда</a></li>
                <li><a data-toggle="tab" href="#thursday">Четверг</a></li>
                <li><a data-toggle="tab" href="#friday">Пятница</a></li>
                <li><a data-toggle="tab" href="#saturday">Суббота</a></li>
                <li><a data-toggle="tab" href="#sunday">Воскресенье</a></li>
            </ul>
            <div class="tab-content">
                <?php foreach ($days as $day => $dayStat){ ?>
                    <div id="<?= $day ?>" class="tab-pane fade in <?=$day === 'monday' ? 'active' : ''?>"
                         data-role="schedule-day-period"
                         data-day-id="<?= $dayStat ?>">
                        <div class="time text-center" data-role="time-block" data-target="<?= $day ?>">
                            <?php if (!empty($periods)) {
                                foreach ($periods as $period) {
                                    if ($period['status'] != 'deleted') {
                                        if ($period['day_id'] == $dayStat) {?>
                                            <div class="row" data-role="time-row" data-period-id="<?= $period['id'] ?>">
                                                <span data-role="schedule-day-item" style=""><?=$timeList[$period['time_start']]?> - <?=$timeList[$period['time_stop']]?></span>
                                                <input type="text" data-role="schedule-day-item-amount" value="<?=$period['amount']?>">
                                                <input type="checkbox" data-role="schedule-day-item-status" data-status="<?= ($period['status']=='active') ? 'active' : 'inactive';?>"
                                                    <?= ($period['status']=='active') ? 'checked' : '';?>>
                                                <span class="btn glyphicon glyphicon-remove" data-role="removePeriod" data-target-period="<?= $period['id'] ?>"></span>
                                            </div>
                                        <?php }
                                    }
                                }
                            }?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="set-period-block col-sm-12 text-center">
                <?= Html::dropDownList('setTimeStart',null,$timeList); ?>
                -
                <?= Html::dropDownList('setTimeStop',null,$timeList); ?>
                <span class="glyphicon glyphicon-plus-sign" data-role="addTime"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success','data-role' => 'submitBtn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::begin([
'header' => '<h2>Ошибка добавления периода!</h2>',
'toggleButton' => [
'tag' => 'button',
'class' => 'btn btn-lg btn-block btn-info hidden',
 'id' => 'alertBtn',
'label' => 'Ошибка',
]
]);
echo '<div class="alert alert-warning">Возможно время начала позже времени окончания.'.
     '<br> Проверьте правильно ли вы задали время и повторите добавление заново.</div>';

Modal::end();
?>
