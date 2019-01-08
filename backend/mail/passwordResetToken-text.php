<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
您好: <?= $user->username ?>,

按照下面的链接重置密码：

<?= $resetLink ?>
