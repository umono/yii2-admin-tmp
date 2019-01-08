<?php
namespace backend\controllers;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/4
 * Time: 下午3:58
 */

class  BaseController extends \yii\web\Controller
{
    /**
     * 行为定义类
     * @return array
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }
}