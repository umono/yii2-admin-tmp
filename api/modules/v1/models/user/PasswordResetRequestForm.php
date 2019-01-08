<?php
namespace api\modules\v1\models\user;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\VarDumper;
use yii\web\UnauthorizedHttpException;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'api\modules\v1\models\user\Member',
                'filter' => ['status' => Member::STATUS_ACTIVE],
                'message' => '没有这个电子邮件地址的用户.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.

     * @return bool|string
     * @throws UnauthorizedHttpException
     */
    public function sendEmail()
    {
        /* @var $user Member */
        $user = Member::findOne([
            'status' => Member::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            throw new UnauthorizedHttpException('邮箱不存在！');
        }

        if (!Member::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        //生成随机数 发送验证码邮箱 返回token url
        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/reset-password', 'token' => $user->password_reset_token]);
        $user->access_token = $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
        //发送随机数
        if ($user->save()){
            $email =  Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                    ['user' => $user]
                )
                ->setTo($this->email)
                ->setSubject('重置密码 ' . Yii::$app->name)
                ->send();
            if ($email){
                return $resetLink;
            }else{
                throw new UnauthorizedHttpException('请稍候重试');
            }
            //返回验证url
        }
    }
}
