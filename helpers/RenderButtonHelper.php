<?php
namespace halumein\schedule\helpers;

use yii\helpers\Url;

class RenderButtonHelper
{
    static function renderButton($record)
    {
        if ($record['user_id'] === \Yii::$app->user->id) {
            switch ($record['status']){
                case 'in process': {
                    $status = 'in process';
                    $url = Url::to(['/schedule/schedule/delete-record']);
                    $text = 'Отменить заявку';
                    break;
                }
                case 'confirmed': {
                    $status = 'confirmed';
                    $url = Url::to(['/schedule/schedule/delete-record']);
                    $text = 'Заявка подтверждена( отменить )';
                    break;
                }
                case 'denied': {
                    $status = 'denied';
                    $url = '';
                    $text = 'Заявка заблокирована';
                    break;
                }
                case 'canceled': {
                    $status = 'active';
                    $text = 'Подать заявку';
                    $url = Url::to(['/schedule/schedule/save-record']);
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
            $url = Url::to(['/schedule/schedule/save-record']);
        }

    
    $button = '<a class="record" data-period-id="'.$record->period_id.
        '"data-schedule-id="'.$record->schedule_id .
        '" data-role="send-record"
         data-url="'.$url.'"
         data-status="'.$status.'">'.
        $text.'</a>';
        
    return $button;    
    }

    static function renderOwnerButton()
    {
           
    }

}