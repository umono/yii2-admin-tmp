<?php
namespace backend\modules\system\models;

use yii\base\Model;
use yii\web\UnauthorizedHttpException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    public $pwd;

    /**
     * @var \backend\modules\system\models\Admin
     */
    private $_user;


    public function init()
    {
        $this->_user = Admin::findOne(\Yii::$app->user->id);
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
