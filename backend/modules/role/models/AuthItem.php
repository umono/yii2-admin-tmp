<?php

namespace backend\modules\role\models;

use backend\models\BaseModel;
use backend\modules\system\models\Admin;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('{{%auth_item_child}}', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('{{%auth_item_child}}', ['child' => 'name']);
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->created_at=time();
                $this->updated_at=time();
            }else{
                $this->created_at=time();
                $this->updated_at=time();
            }
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 获取角色的所有权限信息：路由
     * @return mixed
     */
    public static function getData()
    {
        //如果是超级管理员，则全部显示
        //如果是管理员，则显示管理员及其他。不显示超级管理员
        $id = Yii::$app->user->id;
        if ($id === Admin::SUPER_ADMIN_ID){
            $data = self::find()->where('type=1')->all();
            if (!empty($data)) {
                foreach ($data as $k => $v) {
                    $arr[$v['name']] = $v['description'];
                }
            }
        }
        $role = Yii::$app->authManager->getRolesByUser($id);
        foreach ($role as $k => $v){
            if($k == 'admin'){
                $data = self::find()->where('type=1')->all();
                if (!empty($data)) {
                    foreach ($data as $k => $v) {
                        if ($v['name'] == 'administrator'
                            || $v['name'] == 'admin'
                            || $v['name'] == 'rule-power-admin'){

                        }else {
                            $arr[$v['name']] = $v['description'];
                        }
                    }
                }
            }
        }

        return $arr;
    }
    /**
    * 删除权限与角色操作 必须是超级用户ID为1
    * @return bool
    */
    public function beforeDelete()
    {
        parent::beforeDelete(); // TODO: Change the autogenerated stub
        if (Yii::$app->user->id  === Admin::SUPER_ADMIN_ID) {
            return true;
        }else{
            $this->addError('username', '暂无权限');
            return false;
        }
    }
}
