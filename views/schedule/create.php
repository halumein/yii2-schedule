<?php

\halumein\schedule\assets\ScheduleAsset::register($this);
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;

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
                <li class="active"><a data-toggle="tab" href="#panel1">Понедельник</a></li>
                <li><a data-toggle="tab" href="#panel2">Вторник</a></li>
                <li><a data-toggle="tab" href="#panel3">Среда</a></li>
                <li><a data-toggle="tab" href="#panel4">Четверг</a></li>
                <li><a data-toggle="tab" href="#panel5">Пятница</a></li>
                <li><a data-toggle="tab" href="#panel6">Суббота</a></li>
                <li><a data-toggle="tab" href="#panel7">Воскресенье</a></li>
            </ul>
            <div class="tab-content">
                <div id="panel1" class="tab-pane fade in active">
                    <?= $form->field($model, 'monday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
                <div id="panel2" class="tab-pane">
                    <?= $form->field($model, 'tuesday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
                <div id="panel3" class="tab-pane">
                    <?= $form->field($model, 'wednesday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
                <div id="panel4" class="tab-pane">
                    <?= $form->field($model, 'thursday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
                <div id="panel5" class="tab-pane">
                    <?= $form->field($model, 'friday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
                <div id="panel6" class="tab-pane">
                    <?= $form->field($model, 'saturday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
                <div id="panel7" class="tab-pane">
                    <?= $form->field($model, 'sunday')->textInput(['class' => 'dayInput'])->label(false) ?>
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
