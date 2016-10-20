<?php

\halumein\schedule\assets\ScheduleAsset::register($this);
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model halumein\schedule\models\SchedulePeriod */

$this->title = 'Create Schedule Period';
$this->params['breadcrumbs'][] = ['label' => 'Schedule Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-period-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row container-fluid">
        <div class="col-md-4">
            <?= $form->field($model, 'target_model')->dropDownList($targetList,[
                'data-role' => 'targetModelList',
                'data-url' => Url::to(['/schedule/schedule/get-targets-by-model']),
            ])->label('Расписание для:'); ?>

            <?= $form->field($model, 'target_id')->dropDownList([],[
                'data-role' => 'targetId',
            ])->label(false); ?>
        </div>
        <div class="col-md-8">
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
                <div id="<?= $day ?>" class="tab-pane fade in <?=$day === 'monday' ? 'active' : ''?>" data-role="schedule-day-period">
                    <div class="time" data-role="time" data-target="<?= $day ?>"><?=$dayStat ?></div>
                </div>
                <?php } ?>
            </div>
            <div class="set-period-block col-sm-8">
                <input type="text" class="dayInput" data-role="getTime" placeholder="Период времени...">
                <span class="col-sm-1 glyphicon glyphicon-plus-sign" data-role="addTime"></span>
            </div>
        </div>
        <div data-role="saveSchedule" class="pull-right">тык!</div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
