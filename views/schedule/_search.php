<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model halumein\schedule\models\search\SchedulePeriodSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-period-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    'options' => [
            'data-pjax'   => 1
        ],    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'owner_id') ?>

    <?= $form->field($model, 'target_model') ?>

    <?= $form->field($model, 'target_id') ?>

    <?= $form->field($model, 'monday') ?>

    <?php // echo $form->field($model, 'tuesday') ?>

    <?php // echo $form->field($model, 'wednesday') ?>

    <?php // echo $form->field($model, 'thursday') ?>

    <?php // echo $form->field($model, 'friday') ?>

    <?php // echo $form->field($model, 'saturday') ?>

    <?php // echo $form->field($model, 'sunday') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
