<?php

namespace halumein\schedule\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;

use halumein\schedule\models\Record;
use halumein\schedule\models\CustomRecord;

use halumein\schedule\events\ScheduleEvent;


class RecordController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => [
                            'add',
                            'delete-ajax',
                            'update',
                            'add-custom',
                        ]
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionAdd()
    {
        $scheduleId = Yii::$app->request->post('scheduleId');
        $periodId = Yii::$app->request->post('periodId');
        $clientModel = Yii::$app->request->post('clientModel');
        $clientId = Yii::$app->request->post('clientId');
        $status = Yii::$app->request->post('status');


        $recordId = \Yii::$app->schedule->addRecord( (int)$scheduleId,(int)$periodId, $status, $clientModel, $clientId);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($recordId){

            if ($date = yii::$app->request->post('date')) {
                $text = yii::$app->request->post('comment');
                yii::$app->schedule->addRecordToDate($recordId,$date,$text);
            }

            $model = Record::findOne($recordId);
            $module = $this->module;
            $scheduleEvent = new ScheduleEvent(['recordModel' => $model]);
            $this->module->trigger($module::EVENT_RECORD_CREATE, $scheduleEvent);
            
            return [
                'status' => 'success',
                'updateUrl' => Url::to(['/schedule/record/update']),
                'cancelUrl' => Url::to(['/schedule/record/delete']),
                'recordId' => $recordId,
            ];
        } else {
            return [
                'status' => 'error',
            ];
        }
    }

    public function actionAddCustom()
    {
        $customRecord = new CustomRecord;
        $customRecord->name = htmlspecialchars(Yii::$app->request->post('recordName'));
        $text = htmlspecialchars(Yii::$app->request->post('recordText'));
        $customRecord->text = $text;

        if ($customRecord->save()) {

            $scheduleId = Yii::$app->request->post('scheduleId');
            $periodId = Yii::$app->request->post('periodId');

            $clientModel = $customRecord::className();
            $clientId = $customRecord->id;

            $status = Yii::$app->request->post('status');

            $recordId = \Yii::$app->schedule->addRecord( (int)$scheduleId,(int)$periodId, $status, $clientModel, $clientId);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($recordId){

                if ($date = yii::$app->request->post('date')) {
                    yii::$app->schedule->addRecordToDate($recordId,$date,$text);
                }

                $model = Record::findOne($recordId);
                $module = $this->module;
                $scheduleEvent = new ScheduleEvent(['recordModel' => $model]);
                $this->module->trigger($module::EVENT_CUSTOM_RECORD_CREATE, $scheduleEvent);

                return [
                    'status' => 'success',
                    'updateUrl' => Url::to(['/schedule/record/update']),
                    'cancelUrl' => Url::to(['/schedule/record/delete-ajax']),
                    'recordId' => $recordId,
                ];
            } else {
                return [
                    'status' => 'error',
                ];
            }
        } else {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'error',
                'error' => $model->error
            ];
        }

    }

    public function actionDeleteAjax()
    {
        $recordId = Yii::$app->request->post('recordId');

        $model = Record::findOne($recordId);
        $module = $this->module;
        $scheduleEvent = new ScheduleEvent(['recordModel' => $model]);
        $this->module->trigger($module::EVENT_RECORD_DELETE, $scheduleEvent);

        $success = \Yii::$app->schedule->deleteRecord($recordId);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($success) {
            return [
                'status' => 'success',
                'saveUrl' => Url::to(['/schedule/record/add']),
            ];
        } else {
            return [
                'status' => 'error',
            ];
        }
    }

    public function actionUpdate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $record = Yii::$app->request->post('updateRecord');

        if (Record::find()->where(['id' => $record['recordId'],'status' => $record['status']])->one()) {
            $places = \Yii::$app->schedule->getPlaces($record['scheduleId'],$record['periodId']);
            return [
              'status' => 'true',
              'places' => $places,
            ];
        }
        $success = \Yii::$app->schedule->updateRecord($record['recordId'],$record['status']);
        $places = \Yii::$app->schedule->getPlaces($record['scheduleId'],$record['periodId'],$record['date']);
        if ($success) {

            return [
                'status' => 'success',
                'places' => $places,
            ];
        } else {
            return [
                'status' => 'error',
            ];
        }
    }
}
