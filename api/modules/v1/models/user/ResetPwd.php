<?php
namespace api\modules\v1\models\user;

use yii\base\Model;

/**
 * Password reset form
 */
class ResetPwd extends Model
{
    public $password;

    public $pwd;

    /**
     *  $_user api\modules\v1\models\user\Member
     */
    private $_user;


    public function init()
    {
        $this->_user = Member::findOne(\Yii::$app->user->id);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['pwd', 'required'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
//        VarDumper::dump($this->password);die;
        $is_true = $this->_user->validatePassword($this->pwd);
        if ($is_true) {
            $user = $this->_user;
            $user->setPassword($this->password);
            $user->removePasswordResetToken();
            return $user->save(false);
        }

    }
}
