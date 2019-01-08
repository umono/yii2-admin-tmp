<?php

namespace backend\modules\system\models;

use backend\models\BaseModel;
use backend\modules\role\models\AuthAssignment;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $name
 * @property string $avatar 头像
 * @property string $last_time 上一次登录时间
 * @property string $last_ip 上一次登录的IP
 * @property string $now_time 当前登录时间
 * @property string $now_ip 当前登录的IP
 * @property string $role 管理员角色
 * @property string $address 地址信息
 * @property int $status 状态
 * @property string $created_at
 * @property int $created_id 创建用户
 * @property string $updated_at
 * @property int $updated_id 修改用户
 */
class Admin extends BaseModel implements IdentityInterface
{

    const SUPER_ADMIN_ID =1;
    const STATUS_ACTIVE =10;

    public $is_password = true;

    public $pass_word;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['last_time', 'now_time', 'created_at', 'updated_at'], 'safe'],
            [['status', 'created_id', 'updated_id'], 'integer'],
            [['username', 'email', 'role'], 'string', 'max' => 64],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 30],
            [['last_ip', 'now_ip'], 'string', 'max' => 15],
            [['address'], 'string', 'max' => 100],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],

            ['pass_word', 'string', 'min' => 6],
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'gif', 'jpeg']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'name' => Yii::t('app', 'Name'),
            'avatar' => Yii::t('app', 'Avatar'),
            'last_time' => Yii::t('app', 'Last Time'),
            'last_ip' => Yii::t('app', 'Last Ip'),
            'now_time' => Yii::t('app', 'Now Time'),
            'now_ip' => Yii::t('app', 'Now Ip'),
            'role' => Yii::t('app', 'Role'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_id' => Yii::t('app', 'Created ID'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_id' => Yii::t('app', 'Updated ID'),
            'pass_word' => Yii::t('app', 'Password Hash'),

        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function beforeSave($insert)
    {
        //  如果是修改，不能修改管理员级别以上的单位
        parent::beforeSave($insert); // TODO: Change the autogenerated stub
        if ($insert){
            return true;
        }else {
            if ($this->is_password){return true;}
            if ($this->isNewRecord || (!$this->isNewRecord && $this->pass_word)) {
                $this->setPassword($this->pass_word);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }
           //自己的权限
           $me = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
           //当前修改的用户所拥有的角色是否为管理员
           $role = Yii::$app->authManager->getRolesByUser($this->id);
           //如果当前用户是超级管理员 或者是自己修改自己的信息
           if (Yii::$app->user->id == Admin::SUPER_ADMIN_ID){
               return true;
           }
           if (Yii::$app->user->id == $this->id) {
               return true;
           }
           foreach ($me as $key=>$val){
               if ($key == 'admin' || $key == 'administrator'){
                   //是否存在角色，角色若不为管理级以上就返回真
                   if (!empty($role)) {
                       foreach ($role as $k => $v) {
                           //如果修改的用户也是管理员则直接返回false
                           if ($k == 'admin' || $k == 'administrator') {
                               throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
                           } else {
                               return true;
                           }
                       }
                   }
                   return true;
               }
               throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
           }

       }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert) {
            //这里是新增数据
        } else {
            //这里是更新数据
            $auth = Yii::$app->authManager;
            $d = Yii::$app->request->post();
            if (!empty($d['CreateForm'])) {
                $role = $d['CreateForm']['role'];
                unset($role[0]);
                $this->role = $role;
                //移除之前的角色
                $auth->revokeAll($this->id);
                //移除更改的数据
                if (count($this->role) >= 1) {
                    foreach ($this->role as $v) {
                        $reader = $auth->createRole($v);
                        $auth->assign($reader, $this->id);
                    }
                }
            }
        }
    }


    public function getRole()
    {
        return $this->hasMany(AuthAssignment::className(),['user_id'=>'id']);
    }

    /**
     * 获取当前的
     * @param $id
     * @return array
     */
    public static function getUserRole($id)
    {
        $data = self::find()->where(['id'=>$id])->with('role.itemName')->asArray()->all();
        $arr=[];
        if (!empty($data)) {
            foreach ($data[0]['role'] as $k => $v) {
                $arr[$v['item_name']] = $v['itemName']['description'];
            }
        }
        return $arr;
    }

    public function beforeDelete()
    {
        $role = Yii::$app->authManager->getRolesByUser($this->id);
        foreach ($role as $k => $v){
            if(($k == 'admin' || $k == 'administrator')
                && Yii::$app->user->id != self::SUPER_ADMIN_ID){
                $this->addError('username', '暂无权限');
                return false;
            }
        }

        if ($this->id == self::SUPER_ADMIN_ID) {
            $this->addError('username', '不能删除超级管理员');
            return false;
        }

        if ($this->id == Yii::$app->user->id) {
            $this->addError('username', '不能删除自己');
            return false;
        }

        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        // 移出权限信息
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
