<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/19
 * Time: 下午5:53
 */

namespace backend\modules\system\controllers;

use Yii;
use api\modules\v1\models\user\Member;
use backend\modules\system\models\CreateMember;
use backend\tools\PageParam;
use yii\filters\VerbFilter;
use backend\controllers\Controller;
use yii\web\NotFoundHttpException;

class MemberController extends Controller
{
    private  $model;

    public $modelClass = 'api\modules\v1\models\user\Member';

    public function init()
    {
        $this->model = new PageParam();
    }


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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetData()
    {
       $param =  \Yii::$app->request->get();
       return $this->model->getPageParam($this->modelClass,$param);
    }


    public function actionCreate()
    {
        $model = new CreateMember();
        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $user = $model->Create()) {
                return $this->redirect(['view', 'id' => $user->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->post('status');
        $model->status = $status;
        return $model->save()?true:false;
    }



    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}