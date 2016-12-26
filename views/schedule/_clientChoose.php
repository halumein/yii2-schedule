<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['periodId'] = $periodId;
$this->params['scheduleId'] = $scheduleId;
?>
<div class="clients-list">
    <?= GridView::widget([
        'id' => 'pjax-clients',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'format' => 'raw',
                'value' => function($model) {
                    return '<div class="btn btn-default"
                            data-role="sign-object"
                            data-url="'. Url::to(['/schedule/record/add']) .'"
                            data-label="'. $model->name .'"
                            data-schedule-id="'.$this->params['scheduleId'].'"
                            data-period-id="'.$this->params['periodId'].'"
                            data-client-model="'.$model->className().'"
                            data-client-id="'.$model->id.'">
                            Записать</div>';
                }
            ]
        ],
        'pjax' =>true,
    ]); ?>

</div>
