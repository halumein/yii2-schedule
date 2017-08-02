<?php

namespace halumein\schedule\controllers;

use halumein\schedule\models\RecordToDate;
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
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'get-targets-by-model',
                            'view',
                            'schedule-list',
                            'client-choose-ajax',
                            'date-view',
                            'find-records-ajax'
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

    public function actionIndex()
    {
        $searchModel = new ScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionScheduleList()
    {
        $schedules = Schedule::find()->all();
        return $this->render('schedule-list',[
            'schedules' => $schedules,
        ]);
    }

    public function actionView($id = null)
    {
        $owner = false;
        $days = [
            'Понедельник' => 1,
            'Вторник' => 2,
            'Среда' => 3,
            'Четверг' => 4,
            'Пятница' => 5,
            'Суббота' =>  6,
            'Воскресенье' => 0,
        ];
        if (UserToSchedule::find()->where(['schedule_id'=>$id, 'user_id'=>\Yii::$app->user->id])->one()) {
            $owner = true;
        }
        $model = Schedule::findOne($id);
        $time = Time::find()->all();
        $timeList = ArrayHelper::map($time,'id','time');
        if (!$owner) {
            return $this->render('_user',[
                'model' => $model,
                'days' => $days,
                'timeList' => $timeList,
            ]);
        } else {
            return $this->render('_owner',[
                'model' => $model,
                'days' => $days,
                'timeList' => $timeList,
            ]);
        }

    }

    public function actionUpdate($id = null)
    {
        $periods = [];
        if ($id != null) {
            $model = $this->findModel($id);
            $periods = Period::find()->where(['schedule_id'=>$model->id])->orderBy(['time_start' => SORT_ASC])->all();
        } else {
            $model = new Schedule();
        }



        $users = $this->module->userModel;
        $users = new $users;
        $time = Time::find()->where(['show' => 1])->all();
        $timeList = ArrayHelper::map($time,'id','time');
        $targetList = [];
        $days = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' =>  6,
            'sunday' => 0,
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
                return $this->redirect(['index']);
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
     * Deletes an existing Schedule model.
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
     * Finds the Schedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Schedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Schedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetTargetsByModel() {
        $list = [];

        $modelName = Yii::$app->request->get('model');

        $model = new $modelName;

        $titleColumnName = $this->module->sourceTitleColumnName;


        $model = $model->find()->all();
        $list = ArrayHelper::map($model,'id',$titleColumnName);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'success',
            'list' => $list,
        ];
    }

    public function actionDateView()
    {
        $schedules = ArrayHelper::map(Schedule::find()->all(),'id','name');

        return $this->render('date-view',[
            'schedules' => $schedules,
        ]);
    }

    public function actionClientChooseAjax()
    {
        $model = $this->module->clientSearchModel;

        $searchModel = new $model;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->renderAjax('_clientChoose', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'periodId' => Yii::$app->request->get('periodId'),
            'scheduleId' => Yii::$app->request->get('scheduleId'),
        ]);
    }

    public function actionFindRecordsAjax()
    {

        $days = [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
            6 =>  'Суббота',
            0 => 'Воскресенье',
        ];

        $date = yii::$app->request->post('date');
        $scheduleId = yii::$app->request->post('scheduleId');

        $day = ['id' => strftime("%w", strtotime($date)), 'dayName' => $days[strftime("%w", strtotime($date))]];

        $periods = Period::find()->where(['day_id' => $day['id'], 'status' => 'active', 'schedule_id' => $scheduleId])->all();

        $timeList = ArrayHelper::map(Time::find()->all(),'id','time');

        return $this->renderAjax('_date',[
            'date' => $date,
            'day' => $day,
            'periods' => $periods,
            'timeList' => $timeList,
        ]);
    }

    public function actionAddDatedRecord()
    {

    }

    private function savePeriod($periodsArray,$scheduleId){

        $days = json_decode($periodsArray);

        $times = ArrayHelper::map(Time::find()->all(),'time','id');

        $periodId = \Yii::$app->schedule->addPeriod($days,$scheduleId,$times);
    }
}
