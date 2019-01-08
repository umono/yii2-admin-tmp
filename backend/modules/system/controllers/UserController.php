<?php

namespace backend\modules\system\controllers;

use backend\controllers\Controller;
use backend\modules\system\models\CreateForm;
use backend\modules\system\models\ResetPasswordForm;
use backend\tools\PageParam;
use Yii;
use backend\modules\system\models\Admin;
use yii\db\Exception;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    protected $page;

    protected $modelClass = 'backend\modules\system\models\Admin';

    public function init()
    {
        $this->page = new PageParam();
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**列表数据
     * @return mixed
     */
    public function actionUserData()
    {
        $param = Yii::$app->request->get();
        return $this->page->getPageParam($this->modelClass,$param);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CreateForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
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


    public function actionUpdate($id)
    {
        //如果是更新编辑管理员的
        if ($id == Admin::SUPER_ADMIN_ID){
            if (Yii::$app->user->id != Admin::SUPER_ADMIN_ID){
                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }
        }

        //如果不是自己的看是否为管理员
        if (Yii::$app->user->id != $id){
            if (Yii::$app->user->id != Admin::SUPER_ADMIN_ID) {
                $role = Yii::$app->authManager->getRolesByUser($id);
                foreach ($role as $k => $v) {
                    if ($k == 'admin' || $k == 'administrator') {
                        throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
                    }
                }
            }
        }
        $model = $this->findModel($id);
        $img = $model->avatar;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $file = UploadedFile::getInstance($model, 'avatar');
                if (isset($file)) {
                    //调用模型中的属性  返回上传文件的名称
                    $basePath = \Yii::$app->basePath . "/web/uploads/avatar/";
                    $name = md5($file->name . time()) . '.' . $file->extension;;
                    //定义上传文件的二级目录
                    $path = date('Y-m-d', time());
                    //拼装上传文件的路径
                    $rootPath = $basePath . $path . "/";
                    FileHelper::createDirectory($rootPath);
                    if (!file_exists($rootPath)) {
                        throw  new UnauthorizedHttpException('创建目录失败');
                    }
                    //调用模型类中的方法 保存图片到该路径
                    if ($file->saveAs($rootPath . $name)){}else{
                        throw  new UnauthorizedHttpException('图片上传失败,请稍候重新尝试。');
                    }
                    //属性赋值
                    $model->avatar = '/uploads/avatar/' . $path . '/' . $name;
                } else {
                    $model->avatar = $img;
                    $model->updated_id = Yii::$app->user->id;
                }
                if ($model->save()) {
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'id' => $id,
                    ]);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }catch (Exception $e){
                $transaction->rollBack();
                throw  new UnauthorizedHttpException($e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $model,
            'id'=>$id,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $avatar = Yii::getAlias("@backend").'/web'.  $model->avatar;
        if ($model->delete()){
            if ($model->avatar != ''){unlink($avatar);}
            return true;
        }else{
            return false;
        }

    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * 个人信息
     * @return string
     * @throws UnauthorizedHttpException
     */
    public function actionMe()
    {
        $model = Admin::findOne(Yii::$app->user->id);
        $img = $model->avatar;
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $file = UploadedFile::getInstance($model, 'avatar');
                if (isset($file)) {
                    //调用模型中的属性  返回上传文件的名称
                    $basePath = \Yii::$app->basePath . "/web/uploads/avatar/";
                    $name = md5($file->name . time()) . '.' . $file->extension;;
                    //定义上传文件的二级目录
                    $path = date('Y-m-d', time());
                    //拼装上传文件的路径
                    $rootPath = $basePath . $path . "/";
                    FileHelper::createDirectory($rootPath);
                    if (!file_exists($rootPath)) {
                        throw  new UnauthorizedHttpException('创建目录失败');
                    }
                    //调用模型类中的方法 保存图片到该路径
                    if ($file->saveAs($rootPath . $name)) {
                    } else {
                        throw  new UnauthorizedHttpException('图片上传失败,请稍候重新尝试。');
                    }
                    //属性赋值
                    $model->avatar = '/uploads/avatar/' . $path . '/' . $name;
                } else {
                    $model->avatar = $img;
                    $model->updated_id = Yii::$app->user->id;
                }
                if (Yii::$app->request->post('name') != '') {
                    $model->name = Yii::$app->request->post('name');
                }
                if (Yii::$app->request->post('email') != '') {
                    $model->email = Yii::$app->request->post('email');
                }
                if ($model->save()) {
                } else {
                    return $this->render('me', [
                        'model' => $model,
                    ]);
                }
                $transaction->commit();
                if ($img != $model->avatar) {
                    $avatar = Yii::getAlias("@backend") . '/web' . $img;
                    if ($img != '') {
                        unlink($avatar);
                    }
                }
                return $this->render('me', [
                    'model' => $model,
                ]);
            }catch (Exception $e){
                $transaction->rollBack();
            }
        }

        return $this->render('me', [
            'model' => $model,
        ]);
    }

    /**修改密码
     * @return string
     */
    public function actionResetPassword()
    {
        if (Yii::$app->request->isPost) {
            $model = new ResetPasswordForm();
            $pwd = Yii::$app->request->post('pwd');
            $password = Yii::$app->request->post('password');
            $model->pwd = $pwd;
            $model->password = $password;
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->session->setFlash('success', '密码修改成功！');
            }else{
                Yii::$app->session->setFlash('error', '旧密码输入有误！');
            }
            return $this->render('reset-password');

        }
        return $this->render('reset-password');
    }
}
