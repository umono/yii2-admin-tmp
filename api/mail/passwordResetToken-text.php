<?php

/* @var $this yii\web\View */
/* @var $user api\modules\v1\models\user\Member */

?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:
<p>您的验证码是：</p>
<h3 style="font-weight: 700"><?= $user->access_token?></h3>
