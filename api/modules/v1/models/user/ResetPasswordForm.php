<?php
namespace api\modules\v1\models\user;

use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;
use yii\helpers\VarDumper;
use yii\web\UnauthorizedHttpException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $code;
    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('密码重置令牌不能为空.');
        }
        $this->_user = Member::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('错误的密码重置令牌.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['code', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @return bool
     * @throws UnauthorizedHttpException
     */
    public function resetPassword()
    {
        $user = $this->_user;
        if ($user->access_token == $this->code){
            $user->setPassword($this->password);
            $user->removePasswordResetToken();
            $user->access_token = $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
            return $user->save(false);
        }else{
            throw new UnauthorizedHttpException('错误的验证码.');
        }

    }
}