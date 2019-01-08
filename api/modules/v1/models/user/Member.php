<?php
namespace api\modules\v1\models\user;

use api\models\BaseModel;
use backend\modules\system\models\Admin;
use filsh\yii2\oauth2server\models\OauthAccessTokens;
use filsh\yii2\oauth2server\models\OauthRefreshTokens;
use Prophecy\Exception\InvalidArgumentException;
use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;
use yii\httpclient\Client;
use yii\web\UnauthorizedHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $age
 * @property string $sex
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $access_token
 * @property integer $allowance
 * @property integer $allowance_updated_at
 * @property string $password write-only password
 */
class Member extends BaseModel implements IdentityInterface,\OAuth2\Storage\UserCredentialsInterface
{

    public $avatar;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function findByEmail($email)
    {
        return static::findOne(['email'=>$email,'status'=> self::STATUS_ACTIVE]);
    }

    public static function findByPhone($phone)
    {
        return static::findOne(['phone'=>$phone,'status'=> self::STATUS_ACTIVE]);
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /** @var \filsh\yii2\oauth2server\Module $module */
        $module = Yii::$app->getModule('oauth2');
        $token = $module->getServer()->getResourceController()->getToken();
        return !empty($token['user_id'])
            ? static::findIdentity($token['user_id'])
            : null;
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public function checkUserCredentials($username, $password)
    {
        if (strpos($username,'@')){
            $user = static::findByEmail($username);
        }else  if (is_numeric($username) && strlen($username)==11) {
            $user = static::findByPhone($username);
        }else{
            $user = static::findByUsername($username);
        }
        if (empty($user)) {
            return false;
        }
        return $user->validatePassword($password);
    }

    /**
     * Implemented for Oauth2 Interface
     */
    public function getUserDetails($username)
    {
        if (strpos($username,'@')){
            $user = static::findByEmail($username);
        }else  if (is_numeric($username) && strlen($username)==11) {
            $user = static::findByPhone($username);
        }else{
            $user = static::findByUsername($username);
        }
        return ['user_id' => $user->getId()];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname','sex','age'],'safe'],
            [['age'],'integer'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => '用户名',
            'nickname' => '昵称',
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'age' => '年龄',
            'sex' => '性别',
            'member_type' => '会员类型',
            'phone' => '电话号码',
            'status' => '账户状态',
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'access_token' => Yii::t('app', 'Access Token'),
            'allowance' => Yii::t('app', 'Allowance'),
            'allowance_updated_at' => Yii::t('app', 'Allowance Updated At'),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
            'status' => self::STATUS_ACTIVE,
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

    /* --------------------------------------
     * API相关操作
     * --------------------------------------
     */


    //用户修改密码
    public function r_Pwd()
    {

    }

    /**
     * @return array
     */

    public function fields()
    {
        return [
            // 字段名和属性名相同
            'id',
            'username',
            'nickname',
            'age',
            'sex',
            'phone',
            'created_at',
            'member_type',
            'status',
            'email',
            'avatar' => function () {
                return $this->getAvatar();
            },
        ];
    }


    /**
     * 注册用户
     * @return Member
     * @throws Exception
     * @throws UnauthorizedHttpException
     */
    public function register()
    {
        //取值验证
        $this->attributes = $params = Yii::$app->request->post();

        if(!isset($params['username']) || !isset($params['password']) || !isset($params['email'])){
            throw new UnauthorizedHttpException('参数错误');
        }

        if(empty($params['username']) || empty($params['password']) || empty($params['email'])){
            throw new UnauthorizedHttpException('账户密码邮箱不能为空');
        }

        if (!$this->validate()){
            throw new UnauthorizedHttpException('账号已存在');
        }
        $user = new Member();
        $user->username = $params['username'];
        $user->email = $params['email'];
        $user->setPassword($params['password']);
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->generateAuthKey();

        if ($user->save(false))
            return $user;
        throw new Exception('注册失败，请稍候重试。');
    }

    /**
     * 用户登录
     * @return array|string
     * @throws UnauthorizedHttpException
     */
    public function login()
    {
        $params = Yii::$app->request->post();
        if(empty($params['username']) || empty($params['password'])){
            throw new UnauthorizedHttpException('账户密码不能为空');
        }

        $username = $params['username'];
        $password = $params['password'];

        $is_user = self::find()->where(['username' => $username])
            ->orWhere(['phone'=>$username])
            ->orWhere(['email'=>$username])
            ->one();

        if (!$is_user){
            throw new UnauthorizedHttpException('账号不存在');
        }
        if (!$is_user->validatePassword($password)){
            throw new UnauthorizedHttpException('密码错误！');
        }
        $id = $is_user->id;
        if ($is_user->status == self::STATUS_DELETED){
            throw new UnauthorizedHttpException('账户已被禁止登录！');
        }

        // return $this->oauth($username,$password,$id);
        return $is_user; //判断为真在进行访问token；
    }

    /**
     * 获取token并返回用户信息与令牌
     * @param $accessToken
     * @param $id
     * @return array
     */
    // public function getToken($accessToken,$id)
    // {
    //     $headers = [
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . $accessToken
    //     ];
    //     $client = new Client(['baseUrl' => 'http://api.shop-admin.com']);
    //     $rp = $client->get('v1/user/'.$id,'', $headers)->send();
    //     $data = $rp->getData();
    //     return array_merge($data['data'],['access_token' => $accessToken]);
    // }


    /**
     * 获取访问令牌
     * @param $username
     * @param $password
     * @param $id
     * @return array
     * @throws UnauthorizedHttpException
     */
    // public function oauth($username,$password,$id)
    // {

    //     $data =  [
    //         'grant_type' => 'password',
    //         'client_id' => 'testclient',
    //         'client_secret' => 'testpass',
    //         'username' => $username,
    //         'password' => $password,
    //     ];

    //     $client = new Client(['baseUrl' => 'http://api.shop-admin.com']);
    //     $rp = $client->post('oauth2/token', $data)->send();
    //     $data = $rp->getData();
    //     if (!empty($data['data']['access_token'])){
    //         return $this->getToken($data['data']['access_token'],$id);
    //     }else{
    //         throw new UnauthorizedHttpException('获取令牌失败，请稍候重试！');
    //     }
    // }

    /**
     * 用户登出
     * @return string
     */
    public function logout()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            OauthAccessTokens::deleteAll('user_id=:user_id', [':user_id' => Yii::$app->user->id]);
            OauthRefreshTokens::deleteAll('user_id=:user_id', [':user_id' => Yii::$app->user->id]);
            $transaction->commit();
            return '注销成功';
        }catch (Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }
    }


    /**
     * -------------------------------
     * --------- 数据相关操作 ----------
     * -------------------------------
     */

    public function getAvatar()
    {
        $photo = $this->getPhoto(Yii::$app->user->id);
        if (empty($photo)){
            return null;
        }
        return $photo[0]['url'];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert); //
        if ($insert){
            return true;
        }else{
           	return true;
            //$model = $this->attributes;
            //if(Yii::$app->user->id == Admin::SUPER_ADMIN_ID) {
            //    return  true;
            //}
            //if (Yii::$app->user->id == $model['id']) {
            //    return true;
            //};
            //throw new UnauthorizedHttpException('当前访问无权限。');
        }
    }

}
