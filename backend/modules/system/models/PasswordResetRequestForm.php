<?php

    namespace backend\modules\system\models;

    use Yii;
    use yii\base\Model;
    use yii\helpers\VarDumper;

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
                [
                    'email',
                    'exist',
                    'targetClass' => 'backend\modules\system\models\Admin',
                    'filter'      => ['status' => Admin::STATUS_ACTIVE],
                    'message'     => '没有这个电子邮件地址的用户.',
                ],
            ];
        }

        /**
         * Sends an email with a link, for resetting the password.
         *
         * @return bool whether the email was send
         */
        public function sendEmail()
        {
            /* @var $user Admin */
            $user = Admin::findOne(
                [
                    'status' => Admin::STATUS_ACTIVE,
                    'email'  => $this->email,
                ]);

            if (!$user) {
                return false;
            }
            if (!Admin::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
                if (!$user->save()) {
                    return false;
                }

            }
            return Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                    ['user' => $user]
                )
                ->setTo($this->email)
                ->setSubject('重置密码 ' . Yii::$app->name)
                ->send();
        }
    }
