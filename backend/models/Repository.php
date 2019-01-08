<?php
/**
 * Created by PhpStorm.
 * User: Black.Mr
 * Date: 2018/7/10
 * Time: 9:30
 */

namespace backend\models;


use backend\modules\shop\models\Order;
use backend\modules\system\models\Admin;
use backend\modules\system\models\AdminLog;
use backend\tools\PageParam;
use common\tools\Upload;
use yii\base\Model;
use yii\db\ActiveRecord;
use Yii;

abstract class Repository extends ActiveRecord implements RepositoryInterface
{
    protected $model;

    public function init()
    {
        $this->makeModel();
        return parent::init();
    }
    /**
     * 指定模型名称
     *
     * @return mixed
     */
    abstract function model();
    /**
     * 根据模型名创建实例
     * @return Model|bool
     */
    public function makeModel()
    {
        $model = $this->model();
        return $this->model = new $model;
    }

//    /**
//     * 查询指定建
//     * @param $id
//     * @param array $columns
//     * @return mixed
//     */
//    public function findById($id, $columns = array('*'))
//    {
//        return $this->model->find()->select($columns)->where('id=:id',[":id"=>$id])->one();
//    }
//    /**
//     * 根据指定条件查询一条数据
//     * @param $attribute
//     * @param $value
//     * @param array $columns
//     * @return mixed
//     */
//    public function findByWhere($attribute, $value, $columns = array('*'))
//    {
//        return $this->model->find()->where(['=', $attribute, $value])->select($columns)->one();
//    }
//
//    /**
//     * 返回所有的数据
//     * @param array $columns
//     * @return mixed
//     */
//    public function All($columns = array('*'))
//    {
//        return $this->model->find()->select($columns)->asArray()->all();
//    }

    /**
     * 删除
     * @param $ids
     * @return mixed
     */
    public function findDelete($ids)
    {
        return $this->model->findModel($ids)->delete();
    }

    public function getPageParam($where = [])
    {
        $columns = $this->getColumn();
        $query = $this->model->find();
        if (count($where)>=3) {
            foreach ($where as $k => $v){
                if (in_array($k,$columns)){
                    $query->andWhere(['like', $k, $v]);
                }else{}
            };
        }
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy([
                'created_at'=>SORT_DESC,
            ])
            ->all();
        return [
            'code'=>0,
            'count'=>(int)$count,
            'data'=>$data,
            'msg'=>'获取成功',
        ];
    }
    /**
     * 获取多表分页数据
     * @param array $where
     * @param array $table  多表关联的字段
     * @return mixed|string
     */
    public function getTablesParam($where = [],$table = [])
    {
        $column = $this->getColumn();
        $query = $this->model->find();
        // 判断是否是需要join表的
        if (!empty($where['tables'])){
            $table = explode(',',$where['tables']);
            unset($where['tables']);
        }
        if ($this->model == new Order()){
            $query = $this->model->find()
                ->andWhere(['<>','order.status',99])
                ->andWhere(['<>','order.status',9]);
            if (array_key_exists('status',$where)){
                $query->andWhere(['like','order.status',$where['status']]);
                unset($where['status']);
            }
        }
        $query->JoinWith($table)->orderBy('')->asArray()->all();
        if (count($where)>=3) {
            foreach ($where as $k => $v){
                if ($k == 'page' || $k == 'limit'){}
                else {
                    if (in_array($k,$column)){
                        $query->andWhere(['like', $k, $v]);
                    }else{
                        foreach ($table as $k1 => $v1)
                        {
                            $columnName = $this->backColumn($v1);
                            if (in_array($k,$columnName)){
                                $query->andWhere(['like',$v1.'.'.$k,$v]);
//                                var_dump($query->createCommand()->getRawSql());die;
                            }
                        }
                    }
                }
            };
        }
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy([
                'created_at'=>SORT_DESC,
            ])
            ->all();
        return [
            'code'=>0,
            'count'=>(int)$count,
            'data'=>$data,
            'msg'=>'获取成功',
        ];
    }
    public function getColumn()
    {
        $m_str = $this->makeModel();
        $model = new $m_str;
        $table =  $model->tableName();
        $tableSchema = Yii::$app->db->schema->getTableSchema($table);
        return $tableSchema->columnNames;
    }
    public function backColumn($table)
    {
        $tableSchema = Yii::$app->db->schema->getTableSchema($table);
        return $tableSchema->columnNames;
    }
    /**
     * 获取该表的所有列名
     * @return mixed
     */


    //绝对条件相等
    public function getIsParamTrue($where = [])
    {
        $columns = $this->getColumn();
        $query = $this->model->find();
        if (count($where)>=3) {
            foreach ($where as $k => $v){
                if (in_array($k,$columns)){
                    $query->andWhere([ $k =>$v]);
                }
            };
        }
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return [
            'code'=>0,
            'count'=>(int)$count,
            'data'=>$data,
            'msg'=>'获取成功',
        ];
    }

    public function getPhoto($id)
    {
        $this->makeModel();
        $item = Upload::find()->select(['url','id'])->where('type_id=:type_id',[':type_id'=>$id])
            ->andWhere('type_model=:type_model',[':type_model'=>$this->model->className()])
//            ->andWhere('user_id=:user_id',[':user_id'=>Yii::$app->user->id])
//            ->createCommand()->getRawSql();
            ->asArray()
            ->all();
        return $item;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($insert){
            $this->created_at = date('Y-m-d H:i:s');
            return true;
        }else{
            return true;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $admin = new Admin();
        if($admin->is_password){return true;}
        if($insert) {
            $data = $this->attributes;
            $index = Yii::$app->request->post();
            AdminLog::create(AdminLog::TYPE_CREATE,$data,$index);
        } else {
            $data = $this->attributes;
            $index = Yii::$app->request->post();
            AdminLog::create(AdminLog::TYPE_UPDATE,$data,$index);
        }
    }

    /**
     * 删除操作必须是管理员以上级别
     * @return bool
     */
    public function beforeDelete()
    {
        parent::beforeDelete(); // TODO: Change the autogenerated stub
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        foreach ($role as $k => $v){
            if(($k == 'admin' || $k == 'administrator')){
                return true;
            }else{
                $this->addError('username', '暂无权限');
                return false;
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $index = Yii::$app->request->post('id');
        $data = Yii::$app->request->post();
        AdminLog::create(AdminLog::TYPE_DELETE,$data,$index);
    }

    public function index()
    {
        $params = Yii::$app->request->get();
        if (empty($params['status'])){
            $params['status'] = 1;//必须是已启用的
        }
        return $this->getTablesParam($params);
    }

}