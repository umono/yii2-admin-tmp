<?php

namespace backend\modules\system\models;

use backend\models\BaseModel;
use backend\models\Repository;
use Yii;

/**
 * This is the model class for table "{{%admin_log}}".
 *
 * @property int $id 日志ID
 * @property int $type 日志类型
 * @property string $controller 控制器
 * @property string $action 方法
 * @property string $url 请求地址
 * @property string $index 数据标识
 * @property string $params 请求参数
 * @property string $created_at 创建时间
 * @property int $created_id 创建用户
 */
class AdminLog extends BaseModel
{
    /**
     * 类型
     */
    const TYPE_CREATE = 1; // 创建
    const TYPE_UPDATE = 2; // 修改
    const TYPE_DELETE = 3; // 删除
    const TYPE_OTHER = 4;  // 其他
    const TYPE_UPLOAD = 5;  // 上传
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'created_id'], 'integer'],
            [['params', 'created_at'], 'required'],
            [['params','index'], 'string'],
            [['created_at'], 'safe'],
            [['controller', 'action'], 'string', 'max' => 64],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'controller' => Yii::t('app', 'Controller'),
            'action' => Yii::t('app', 'Action'),
            'url' => Yii::t('app', 'Url'),
            'index' => Yii::t('app', 'Index'),
            'params' => Yii::t('app', 'Params'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_id' => Yii::t('app', 'Created ID'),
        ];
    }


}
