<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel halumein\schedule\models\search\SchedulePeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Schedule Periods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-period-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Schedule Period', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'owner_id',
            'target_model',
            'target_id',
            'monday:ntext',
            // 'tuesday:ntext',
            // 'wednesday:ntext',
            // 'thursday:ntext',
            // 'friday:ntext',
            // 'saturday:ntext',
            // 'sunday:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
