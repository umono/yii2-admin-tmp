<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '密码重置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="">
        <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
            <div style="background: #fff;padding: 50px;justify-content: center;margin-top: 150px;">
            <h3 class="layui-header" style="height: 40px;"><?= Html::encode($this->title) ?></h3>

            <p style="color:#999;">请填写你的电子邮件。将在那里发送重置密码的链接.</p>

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true,'class'=>'layui-input']) ?>

                <div class="form-group">
                    <?= Html::submitButton('发送', ['class' => 'layui-btn-normal layui-btn btn-block']) ?>
                </div>

            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>