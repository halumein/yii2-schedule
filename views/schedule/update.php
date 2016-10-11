<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model halumein\schedule\models\SchedulePeriod */

$this->title = 'Update Schedule Period: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Schedule Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="schedule-period-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
