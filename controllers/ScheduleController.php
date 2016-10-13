<?php

namespace halumein\schedule\controllers;

use Yii;
use halumein\schedule\models\SchedulePeriod;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ScheduleController implements the CRUD actions for SchedulePeriod model.
 */
class ScheduleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SchedulePeriod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = SchedulePeriod::find()->all();

        return $this->render('index', [
            'modelSchedule' => $model,
        ]);
    }

    /**
     * Displays a single SchedulePeriod model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SchedulePeriod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $targetList = [];
        $model = new SchedulePeriod();
        if ($this->module->sourceList) {
            $targetList = $this->module->sourceList;
        }
        if ($model->load(Yii::$app->request->post())) {

            $model->owner_id = \Yii::$app->user->id;



            if ($model->save()) {
                return $this->redirect('index');
            }


        } else {
            return $this->render('create', [
                'model' => $model,
                'targetList' => $targetList,
            ]);
        }
    }

    /**
     * Updates an existing SchedulePeriod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SchedulePeriod model.
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
     * Finds the SchedulePeriod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SchedulePeriod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SchedulePeriod::findOne($id)) !== null) {
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
}
