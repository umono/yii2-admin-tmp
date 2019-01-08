<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user api\modules\v1\models\user\Member */

?>
<div class="password-reset">
    <p>您好 <?= Html::encode($user->username) ?>,</p>

    <p>您的验证码是：</p>
    <h3 style="font-weight: 700"><?= $user->access_token?></h3>
</div>
