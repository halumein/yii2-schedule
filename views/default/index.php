<?php
use yii\helpers\Url;
?>
<div class="schedule-default-index">
    <div class="row">
        <div class="col-sm-12">

            <table class='table table-bordered'>
                <thead>
                    <th>url</th>
                    <th>описание</th>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="<?= Url::to(['/schedule/schedule/index'])?>"><?= Url::to(['/schedule/schedule/index'])?></a> </td>
                        <td>Индекс расписаний и кнопка создания</td>
                    </tr>
                    <tr>
                        <td><a href="<?= Url::to(['/schedule/schedule/schedule-list'])?>"><?= Url::to(['/schedule/schedule/schedule-list'])?></a> </td>
                        <td>Список расписаний</td>
                    </tr>
                    <tr>
                        <td><a href="<?= Url::to(['/schedule/time/index'])?>"><?= Url::to(['/schedule/time/index'])?></a> </td>
                        <td>Время</td>
                    </tr>
                    <tr>
                        <td><a href="<?= Url::to(['/schedule/schedule/date-view'])?>"><?= Url::to(['/schedule/schedule/date-view'])?></a> </td>
                        <td>Запись на дату</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
