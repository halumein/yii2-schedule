<?php

namespace halumein\schedule\controllers;

use Yii;
use halumein\schedule\models\Schedule;
use halumein\schedule\models\Period;
use halumein\schedule\models\Time;
use halumein\schedule\models\Record;
use halumein\schedule\models\search\ScheduleSearch;
use halumein\schedule\models\UserToSchedule;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


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
                        'actions' => ['add','delete','update']
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
            return [
                'status' => 'success',
                'cancelUrl' => Url::to(['/schedule/record/delete']),
                'recordId' => $recordId,
            ];
        } else {
            return [
                'status' => 'error',
            ];
        }

    }

    public function actionDelete()
    {
        $recordId = Yii::$app->request->post('recordId');

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
        $places = \Yii::$app->schedule->getPlaces($record['scheduleId'],$record['periodId']);
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
