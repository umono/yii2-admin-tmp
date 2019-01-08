<?php
namespace backend\modules\system\models;

use api\modules\v1\models\user\Member;
use yii\base\Model;
use yii\helpers\VarDumper;
use Yii;
/**
 * CreateForm form
 */
class CreateMember extends Model
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $password_repeat;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\api\modules\v1\models\user\Member', 'message' => '用户名已存在.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\api\modules\v1\models\user\Member', 'message' => '邮箱地址已注册.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'compare', 'compareAttribute' => 'password','message' => '两次输入的密码不一致.'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' =>  Yii::t('app', 'ID'),
            'username' =>   Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password Hash'),
            'password_repeat' => Yii::t('app', 'Password Repeat'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    public function Create()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new Member();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->access_token = md5(date('Y-m-d H:i:s'));
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save()?$user:false;
    }
}
