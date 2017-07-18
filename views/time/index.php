<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel halumein\schedule\models\search\TimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Время';
$this->params['breadcrumbs'][] = ['label' => 'Время', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'time',
            [
                'attribute' => 'show',
                'format' => 'html',
                'value' => function($model) {
                    if ($model->show == 1) {
                        return Html::a('Не показывать', ['/schedule/time/change-show', 'id' => $model->id, 'newValue' => 0], ['class' => 'btn-sm btn-danger']);
                    } else {
                        return Html::a('Показывать', ['/schedule/time/change-show', 'id' => $model->id, 'newValue' => 1], ['class' => 'btn-sm btn-success']);
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'options' => ['style' => 'width: 125px;']
            ],
        ],
    ]); ?>
</div>
