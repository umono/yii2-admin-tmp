<?php

namespace backend\modules\system\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\VarDumper;

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
class AdminLog extends ActiveRecord
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


    public static function getTypeDescription($type = null)
    {
        $mixReturn = [
            self::TYPE_CREATE => '创建',
            self::TYPE_CREATE => '创建',
            self::TYPE_UPDATE => '修改',
            self::TYPE_DELETE => '删除',
            self::TYPE_OTHER => '其他',
            self::TYPE_UPLOAD => '上传',
        ];
        if ($type !== null) {
            $mixReturn = isset($mixReturn[$type]) ? $mixReturn[$type] : null;
        }

        return $mixReturn;
    }

    /**
     * 创建日志
     * @param integer $type 类型
     * @param array $params 请求参数
     * @param string $index 数据唯一标识
     * @return bool
     */
    public static function create($type, $params = [], $index = '')
    {
        $log = new AdminLog();
        $log->type = $type;
        $log->params = Json::encode($params);
        $log->controller = Yii::$app->controller->id;
        $log->action = Yii::$app->controller->action->id;
        $log->url = Yii::$app->request->url;
        $log->index = Json::encode($index);
        $log->created_id = Yii::$app->user->id;
        $log->created_at = date('Y-m-d H:s:i');
        return $log->save();
    }
}
