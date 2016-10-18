<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
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
    <?php foreach ($modelSchedule as $model):    ?>
    <div class="row container-fluid">
        <div class="col-md-4">
            <div class="col-md-4">
                Расписание для: <br>
                <?=$model->target_model ?>
            </div>
            <div class="col-md-2">
                id: <br>
                <?=$model->target_id ?>
            </div>
        </div>
        <div class="col-md-8">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#panel1-<?=$model->owner_id ?>-<?=$model->target_id ?>">Понедельник</a></li>
                <li><a data-toggle="tab" href="#panel2-<?=$model->owner_id ?>-<?=$model->target_id ?>">Вторник</a></li>
                <li><a data-toggle="tab" href="#panel3-<?=$model->owner_id ?>-<?=$model->target_id ?>">Среда</a></li>
                <li><a data-toggle="tab" href="#panel4-<?=$model->owner_id ?>-<?=$model->target_id ?>">Четверг</a></li>
                <li><a data-toggle="tab" href="#panel5-<?=$model->owner_id ?>-<?=$model->target_id ?>">Пятница</a></li>
                <li><a data-toggle="tab" href="#panel6-<?=$model->owner_id ?>-<?=$model->target_id ?>">Суббота</a></li>
                <li><a data-toggle="tab" href="#panel7-<?=$model->owner_id ?>-<?=$model->target_id ?>">Воскресенье</a></li>
            </ul>
            <div class="tab-content">
                <div id="panel1-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane fade in active">
                    <div class="col-md-4">
                        <?=$model->monday ?>
                    </div>
                </div>
                <div id="panel2-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane">
                    <div class="col-md-4">
                        <?=$model->tuesday ?>
                    </div>
                </div>
                <div id="panel3-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane">
                    <div class="col-md-4">
                        <?=$model->wednesday ?>
                    </div>
                </div>
                <div id="panel4-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane">
                    <div class="col-md-4">
                        <?=$model->thursday ?>
                    </div>
                </div>
                <div id="panel5-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane">
                    <div class="col-md-4">
                        <?=$model->friday ?>
                    </div>
                </div>
                <div id="panel6-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane">
                    <div class="col-md-4">
                        <?=$model->saturday ?>
                    </div>
                </div>
                <div id="panel7-<?=$model->owner_id ?>-<?=$model->target_id ?>" class="tab-pane">
                    <div class="col-md-4">
                        <?=$model->sunday ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
