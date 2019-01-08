<?php
namespace backend\modules\system\models;

use yii\base\Model;
use yii\helpers\VarDumper;
use Yii;
/**
 * CreateForm form
 */
class CreateForm extends Model
{
    public $username;
    public $name;
    public $email;
    public $password;
    public $address;
    public $password_repeat;
    public $role;
    public $avatar;


/**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\modules\system\models\Admin', 'message' => '用户名已存在.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\modules\system\models\Admin', 'message' => '邮箱地址已注册.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'compare', 'compareAttribute' => 'password','message' => '两次输入的密码不一致.'],
            ['name','required'],
            ['name','string','max'=>'20'],

            [['address','role','avatar'],'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' =>  Yii::t('app', 'ID'),
            'username' =>   Yii::t('app', 'Username'),
            'name' => Yii::t('app', 'Name'),
            'password' => Yii::t('app', 'Password Hash'),
            'password_repeat' => Yii::t('app', 'Password Repeat'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'avatar' => Yii::t('app', 'Avatar'),
            'role' => Yii::t('app', 'Role'),
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new Admin();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->address = $this->address;
        $user->role = implode(',',$this->role);
        $user->created_id = Yii::$app->user->id;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if (count($this->role)>=1 && $user->save()) {
            foreach ($this->role as $v) {
                if ($v == 'no'){
                }else {
                    $auth = Yii::$app->authManager;
                    $reader = $auth->createRole($v);
                    $auth->assign($reader, $user->id);
                }
            }
            return $user;
        }
        return $user->save();
    }
}
