<?php
namespace halumein\schedule\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class RenderButtonHelper
{
    static function renderButton($record = null,$scheduleId,$periodId)
    {

        if ($record['user_id'] == \Yii::$app->user->id) {
            switch ($record['status']){
                case 'in process': {
                    $status = 'in process';
                    $url = Url::to(['/schedule/record/delete']);
                    $text = 'Отменить';
                    $textStatus = 'Заявка отправлена.  | ';
                    break;
                }
                case 'confirmed': {
                    $status = 'confirmed';
                    $url = Url::to(['/schedule/record/delete']);
                    $text = 'Отменить';
                    $textStatus = 'Заявка подтверждена.  | ';
                    break;
                }
                case 'denied': {
                    $status = 'denied';
                    $url = '';
                    $text = '';
                    $textStatus = 'Заявка заблокирована.';
                    break;
                }
                case 'canceled': {
                    $status = 'active';
                    $text = 'Подать заявку';
                    $textStatus = 'Заявка отменена.  | ';
                    $url = Url::to(['/schedule/record/add']);
                    break;
                }
                default: {
                    break;
                }
            }
        } else {
            $btn = 'btn';
            $status = 'active';
            $text = 'Подать заявку';
            $url = Url::to(['/schedule/record/add']);
        }

    if ($status != 'denied') {
        $button = '<div class="user-record"><span class="text-status">'.$textStatus.'</span><a class="record" data-period-id="'.$periodId.
            '"data-schedule-id="'.$scheduleId.
            '" data-role="send-record"
         data-url="'.$url.'"
         data-status="'.$status.'">'.
            $text.'</a></div>';
    } else {
        $button = '<div class="user-record"><span class="text-status" style="color:red">'.$textStatus.'</span></div>';
    }


    return $button;
    }

    static function renderOwnerRecordBlock($record,$scheduleId,$periodId)
    {
        switch ($record->status) {
            case 'in process': {
                $actions = '<a class="record"
                         data-record-id="'.$record->id.'"'.
                         'data-status="confirmed"
                         data-role="update-record">
                         Подтвердить</a> |
                         <a class="record"
                         data-record-id="'.$record->id.'"'.
                         'data-status="denied"
                         data-role="update-record">
                         Заблокировать</a>';
                break;
            }
            case 'confirmed': {
                $actions = '<a class="record"
                        data-record-id="'.$record->id.'"'.
                        'data-status="canceled"
                        data-role="update-record">
                        Отменить</a> |
                        <a class="record"
                        data-record-id="'.$record->id.'"'.
                        'data-status="denied"
                        data-role="update-record">
                        Заблокировать</a>';
                break;
            }
            case 'canceled': {
                $actions = '<a class="record"
                        data-record-id="'.$record->id.'"'.
                        'data-status="confirmed"
                        data-role="update-record">
                        Подтвердить</a> |
                        <a class="record"
                        data-record-id="'.$record->id.'"'.
                        'data-status="denied"
                        data-role="update-record">
                        Заблокировать</a>';
                break;
            }
            default: {
                return '';
                break;
            }

        }

        $actions .= ' | <a class="record"
                           data-record-id="'.$record->id.'"
                           data-role="delete-record"
                           data-url="'.Url::to(['/schedule/record/delete']).'"
                           >
                        Удалить</a>';
        $userModel = yii::$app->getModule('schedule')->userModel;
        $userModel = new $userModel;
        $users = ArrayHelper::map($userModel::find()->all(),'id','username');

        $clientObjectName = false;
        if (!is_null($record->client_model) && !is_null($record->client_id)) {
            $model = new $record->client_model;
            $client = $model->findOne($record->client_id);
            $clientObjectName = $client->name;
        }

        $label = $clientObjectName ? $clientObjectName : $users[$record->user_id];

        $block = '<div class="user-record"><label>'.$label.' </label>
        '.'<span data-role="target"
        data-schedule-id="'.$scheduleId.'"
        data-period-id="'.$periodId.'"
        data-url="'.Url::to(['/schedule/record/update']).'" > ( '.$actions.' )</span></div>';

        return $block;
    }

    // static function renderOwnerUserRecord($record)
    // {
    //     $users = ArrayHelper::map(User::getActive(),'id','name');
    //     $username = '<div><label>'.$users[$record->user_id].'</label></div>';
    //
    //     return $username;
    // }

}
