<?php

namespace api\models;

use yii\data\Pagination;
// use yii\helpers\Json;
use yii\helpers\VarDumper;
use Yii;
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/6
 * Time: 下午10:32
 */
class PageParam extends Pagination
{
    public $pageSizeParam = 'limit';


    /**
     * 获取单表分页数据
     * @param $model //模型传入类名
     * @param array $where //传入条件
     * @return mixed|string
     */
    public function getPageParam($model,$where = [])
    {
        $columns = $this->getColumn($model);
        $query = $model::find();
        if (count($where)>=1) {
            foreach ($where as $k => $v){
                if (in_array($k,$columns)){
                    $query->andWhere(['like', $k, $v]);
                }else{}
            };
        }else{}
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $data;
    }
    /**
     * 获取多表分页数据
     * @param $model
     * @param array $where
     * @param array $table  多表关联的字段
     * @return mixed|string
     */
    public function getTablesParam($model,$where = [],$table = [])
    {
        $column = $this->getColumn($model);
        $query = $model::find();
        $query->JoinWith($table)->orderBy('')->asArray()->all();
        if (count($where)>=1) {
            foreach ($where as $k => $v){
                if ($k == 'page' || $k == 'limit'){}
                else {
                    if (in_array($k,$column)){
                        $query->andWhere(['like', $k, $v]);
                    }else{
                        foreach ($table as $k1 => $v1)
                            {
                                $query->andWhere(['like',$v1.'.'.$k,$v]);

                            }
                    }
                }
            };
        }else{}
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $data;
    }

    /**
     * 获取该表的所有列名
     * @param $model
     * @return array
     */
    public function getColumn($model)
    {
        $table = $model::tableName();
        $tableSchema = Yii::$app->db->schema->getTableSchema($table);
        return $tableSchema->columnNames;
    }
    //绝对条件相等
    public function getIsParamTrue($model,$where = [])
    {
        $columns = $this->getColumn($model);
        $query = $model::find();
        if (count($where)>=1) {
            foreach ($where as $k => $v){
                if (in_array($k,$columns)){
                    $query->andWhere([ $k =>$v]);
                }else{}
            };
        }else{}
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $data;
    }


    //获取
    public function getCateParam($model,$where = [])
    {
        $columns = $this->getColumn($model);
        $query = $model::find();
        if (count($where)>=1) {
            foreach ($where as $k => $v){
                if ($k == 'cate_id'){
                    $query->andWhere([
                        '=',$k,$v
                    ]);
                }elseif (in_array($k,$columns)){
                    $query->andWhere(['like', $k, $v]);
                }else{}
            };
        }else{}
        $count = $query->count();
        $pagination = new PageParam(['totalCount' => $count]);
        $data = $query->offset($pagination->offset)
            ->limit($pagination->limit)
//            ->createCommand()->getRawSql();
            ->all();
        return $data;
    }

}