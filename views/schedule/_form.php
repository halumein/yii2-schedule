<?php
\halumein\schedule\assets\ScheduleAsset::register($this);
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model halumein\schedule\models\SchedulePeriod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-period-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'owner_id')->textInput() ?>

    <?= $form->field($model, 'target_model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target_id')->textInput() ?>

    <?= $form->field($model, 'monday')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tuesday')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'wednesday')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'thursday')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'friday')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'saturday')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sunday')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
