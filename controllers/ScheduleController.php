<?php

namespace halumein\schedule\controllers;

use Yii;
use halumein\schedule\models\ScheduleSchedule;
use halumein\schedule\models\SchedulePeriod;
use halumein\schedule\models\ScheduleTime;
use halumein\schedule\models\ScheduleRecord;
use halumein\schedule\models\search\ScheduleScheduleSearch;
use halumein\schedule\models\ScheduleUserToSchedule;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


class ScheduleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
//                'only' => ['index,update,delete'],
//                'allow' => true,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index', 'update', 'delete','get-targets-by-model','view','schedule-list','save-record','delete-record']
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

    public function actionIndex()
    {
        $searchModel = new ScheduleScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionScheduleList()
    {
        $schedules = ScheduleSchedule::find()->all();
        return $this->render('schedule-list',[
            'schedules' => $schedules,
        ]);
    }

    public function actionView($id = null)
    {
        var_dump(\Yii::$app->has('schedule'));
        die;
        $owner = false;
        $days = [
            'monday' => 0,
            'tuesday' => 1,
            'wednesday' => 2,
            'thursday' => 3,
            'friday' => 4,
            'saturday' =>  5,
            'sunday' => 6,
        ];
        if (ScheduleUserToSchedule::find()->where(['schedule_id'=>$id, 'user_id'=>\Yii::$app->user->id])->one()) {
            $owner = true;
        }
        $model = ScheduleSchedule::findOne($id);
        $time = ScheduleTime::find()->all();
        $timeList = ArrayHelper::map($time,'id','time');
        return $this->render('view',[
            'model' => $model,
            'days' => $days,
            'timeList' => $timeList,
            'owner' => $owner,
        ]);
    }

    public function actionUpdate($id = null)
    {
        if ($id != null) {
            $model = $this->findModel($id);
            $periods = SchedulePeriod::find()->where(['schedule_id'=>$model->id])->orderBy(['time_start' => SORT_ASC])->all();
        } else
        {
            $model = new ScheduleSchedule();
        }

        $users = $this->module->userModel;
        $users = new $users;
        $time = ScheduleTime::find()->all();
        $timeList = ArrayHelper::map($time,'id','time');
        $targetList = [];
        $days = [
            'monday' => 0,
            'tuesday' => 1,
            'wednesday' => 2,
            'thursday' => 3,
            'friday' => 4,
            'saturday' =>  5,
            'sunday' => 6,
        ];

        if ($this->module->sourceList) {
            $targetList = $this->module->sourceList;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->owner_id)) {
                $model->owner_id = \Yii::$app->user->id;
            }
            if ($model->save()) {
                $this->savePeriod($model->periodsArray,$model->id);
                return $this->redirect('index');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'targetList' => $targetList,
                'timeList' => $timeList,
                'days' => $days,
                'periods' => $periods,
                'users' => $users::find()->all(),
            ]);
        }
    }

    /**
     * Deletes an existing ScheduleSchedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScheduleSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScheduleSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleSchedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetTargetsByModel() {
        $list = [];
        $modelName = Yii::$app->request->post('model');
        $model = new $modelName;
        $model = $model->getActive();
        $list = ArrayHelper::map($model,'id','name');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'success',
            'list' => $list,
        ];
    }

    public function actionSaveRecord()
    {
        $record = Yii::$app->request->post('record');
        $recordId = \Yii::$app->schedule->addRecord($record['scheduleId'],$record['periodId']);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($recordId){
            return [
                'status' => 'success',
                'cancelUrl' => Url::to(['/schedule/schedule/delete-record']),
            ];
        } else {
            return [
                'status' => 'error',
            ];
        }

    }

    public function actionDeleteRecord()
    {
        $record = Yii::$app->request->post('record');
        $success = \Yii::$app->schedule->deleteRecord($record['scheduleId'],$record['periodId']);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($success) {
            return [
                'status' => 'success',
                'saveUrl' => Url::to(['/schedule/schedule/save-record']),
            ];
        } else {
            return [
                'status' => 'error',
            ];
        }
    }

    private function savePeriod($periodsArray,$scheduleId){
        $days = json_decode($periodsArray);
        $times = ArrayHelper::map(ScheduleTime::find()->all(),'time','id');
        $periodId = \Yii::$app->schedule->addPeriod($days,$scheduleId,$times);
    }
}
