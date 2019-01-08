<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model backend\modules\system\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录后台系统';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="">
        <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
            <div style="background: #fff;padding: 50px;justify-content: center;margin-top: 150px;">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true,'class'=>'layui-input','style'=>' letter-spacing:4px;']) ?>

                <?= $form->field($model, 'password')->passwordInput(['class'=>'layui-input','style'=>' letter-spacing:4px;'])?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('登录', ['class' => 'layui-btn btn-block layui-btn-normal', 'name' => 'login-button']) ?>
                </div>
                <div style="color:#999;margin:1em 0">
                    如果你忘记了密码，你可以 <?= Html::a('重置它', ['site/request-password-reset']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
