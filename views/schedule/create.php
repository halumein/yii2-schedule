<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model halumein\schedule\models\SchedulePeriod */

$this->title = 'Create Schedule Period';
$this->params['breadcrumbs'][] = ['label' => 'Schedule Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-period-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
