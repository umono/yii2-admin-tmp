<?php

namespace backend\modules\role\controllers;

use backend\modules\system\models\Log as AdminLog;
use backend\tools\PageParam;
use Yii;
use backend\modules\role\models\AuthItem;
use yii\web\NotFoundHttpException;
use backend\controllers\Controller;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;

/**
 * PowerController implements the CRUD actions for AuthItem model.
 */
class PowerController extends Controller
{

    protected $page;

    protected $modelClass = 'backend\modules\role\models\AuthItem';

    public function init()
    {
        $this->page = new PageParam();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'         => ['POST'],
                    'role-power'     => ['POST'],
                    'del-role-power' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 创建权限
     *
     * @return string|\yii\web\Response
     * @throws UnauthorizedHttpException
     */
    public function actionCreatePower()
    {
        $model = new AuthItem();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if (empty($post['AuthItem']['rule_name'])) {
                unset($post['AuthItem']['rule_name']);
            }
            if ($model->load($post) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }
        }

        return $this->render('create-power', ['model' => $model]);
    }

    /**
     * 创建角色
     *
     * @return string|\yii\web\Response
     * @throws UnauthorizedHttpException
     */
    public function actionCreateRole()
    {
        $model = new AuthItem();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                throw new UnauthorizedHttpException('创建角色，请稍候重试');
            }
        }

        return $this->render('create-role', ['model' => $model]);
    }

    /**
     * 更新
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if ($model->load($post) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            } else {
                throw new UnauthorizedHttpException('更新角色，请稍候重试');
            }
        }

        return $this->render('update', ['model' => $model]);


    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        return $this->findModel($id)->delete() ? true : false;
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * 返回权限信息
     *
     * @return mixed
     */
    public function actionPowerData()
    {
        $param         = Yii::$app->request->get();
        $param['type'] = 2;
        return $this->page->getPageParam($this->modelClass, $param);
    }

    public function actionRoleData()
    {
        $param         = Yii::$app->request->get();
        $param['type'] = 1;
        return $this->page->getPageParam($this->modelClass, $param);
    }

    public function actionRole()
    {
        return $this->render('role');
    }

    //角色添加权限页面
    public function actionRolePowers($id)
    {
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        foreach ($role as $k => $v) {
            if (($k == 'admin' || $k == 'administrator')) {

                $data = AuthItem::find()->with('authItemChildren.child0')
                    ->where(['name' => $id])
                    ->asArray()->all();
                $arr  = [];
                if (!empty($data[0]['authItemChildren'])) {
                    foreach ($data[0]['authItemChildren'] as $key => $val) {
                        $arr[] = $val['child0']['description'];
                    }
                }
                $param        = Yii::$app->request->get();
                $name         = isset($param['name']) ? $param['name'] : '';
                $descriptions = isset($param['description']) ? $param['description'] : '';

                $power  = AuthItem::find()->where('type=2')
                    ->andWhere(['like', 'name', $name])
                    ->andWhere(['like', 'description', $descriptions])
                    ->asArray()
                    ->all();
                $powers = [];
                foreach ($power as $k => $v) {
                    $powers[$v['name']] = $v['description'];
                }
                return $this->render('role-powers', ['power' => $powers, 'arr' => $arr, 'id' => $id]);
            } else {
                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }
        }
    }

    //添加角色权限
    public function actionRolePower()
    {
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        foreach ($role as $k => $v) {
            if (($k == 'admin' || $k == 'administrator')) {
                $auth   = Yii::$app->authManager;
                $data   = \Yii::$app->request->post();
                $role   = $data['role'];
                $power  = $data['id'];
                $parent = $auth->createRole($role);
                $child  = $auth->createPermission($power);
                AdminLog::create(AdminLog::TYPE_CREATE, $data, $power);
                return $auth->addChild($parent, $child) ? true : false;
            } else {
                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }
        }

    }

    //删除角色权限
    public function actionDelRolePower()
    {
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        foreach ($role as $k => $v) {
            if (($k == 'admin' || $k == 'administrator')) {
                $auth   = Yii::$app->authManager;
                $data   = \Yii::$app->request->post();
                $role   = $data['role'];
                $power  = $data['id'];
                $parent = $auth->createRole($role);
                $child  = $auth->createPermission($power);
                AdminLog::create(AdminLog::TYPE_DELETE, $data, $power);
                return $auth->removeChild($parent, $child) ? true : false;
            } else {
                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }
        }
    }


}
