<?php

namespace halumein\schedule\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index']
                    ],

                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

}
