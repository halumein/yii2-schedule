<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\vendor\halumein\schedule\models\search\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Расписания';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-schedule-index">

    <h1 class="schedule-list-url" ><a href="<?= Url::to(['schedule/schedule-list'])?>"><?= Html::encode($this->title) ?></a></h1>
    <p>
        <?= Html::a('Создать расписание', ['update'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if($model->active) {
                return ['class' => 'success'];
            } else {
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            //'owner_id',
            [
                'attribute' => 'target_model',
                'filter' => false,
                'value' => function($model) {
                    $module = \Yii::$app->getModule('schedule');
                    return $module->sourceList[$model->target_model];
                }
            ],
            [
                'attribute' => 'target_id',
                'filter' => false,
                'value' => function($model) {
                    return $model->targetName;
                }
            ],
            'name',
            // 'date',
            [
                'label' => 'active',
                'format' => 'html',
                'value' => function($model) {
                    if ($model->active) {
                        $html = Html::a('Отключить', ['/schedule/schedule/set-unactive', 'id' => $model->id]);
                    } else {
                        $html = Html::a('Включить', ['/schedule/schedule/set-active', 'id' => $model->id]);
                    }
                    return $html;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'options' => ['style' => 'width: 125px;']
            ],
        ],
    ]); ?>
</div>
