<?php

namespace backend\controllers;

use backend\modules\system\models\Admin;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class Controller 后台的基础控制器
 * @author  liujx
 * @package backend\controllers
 */
class Controller extends BaseController
{


    protected $admins = null;
    /**
     * 请求之前的数据验证
     * @param \yii\base\Action $action
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($_ENV['DEBUG'] == 'false') {
            // 主控制器验证
            if (parent::beforeAction($action)) {
                // 验证权限
                if (!Yii::$app->user->can(
                        $action->controller->module->id . '/' . $action->controller->id . '/' . $action->id
                    )
                    && Yii::$app->getErrorHandler()->exception === null
                ) {
                    if (Yii::$app->request->isAjax){
                        return "您暂无权限访问！";
                    }else {
                        echo '
                    <style>body{background: #f2f2f2;}</style>
                    <div style="min-width: 100%;min-height: 100%">
                    <div style="text-align: center;padding-top:200px;font-weight: 700;">
                    <p>您暂无权限访问！</p>
                    <p>该权限名称为:</p>
                    <p>';
                        echo $this->getAction() .'</p>
                    <p>若为管理员，请添加至权限管理中心</p>
                    </div>
                    </div>';
                        die;
                    }
                }

                return true;
            }
            return false;
        }else{
            return true;
        }
    }

    public function getAction()
    {
        $action = $this->module->id .'/'.$this->id . '/' . $this->action->id;

        return $action;
    }

    protected $model;

    public function init()
    {
        $id = $this->id;
        $id = str_replace("-"," ",$id);
        $id = ucwords($id);
        $id = str_replace(' ', '', $id);
        $name =  '\\backend\\modules\\'.$this->module->id.'\\models\\'.$id;
        $this->model = new $name;
    }


    /**
     * ==============================
     * 增删改查
     * ==============================
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetDataIndex()
    {
        $data = $this->model->index();
        return $this->asJson($data);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = $this->model;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Material model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->post('status');
        if (empty($status)){
            $status = 99;
        }
        $model->status = $status;

        return $model->save()?true:false;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = $this->model->findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
