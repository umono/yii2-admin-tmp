<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\modules\system\models\ResetPasswordFormAdmin */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '重置密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="">
        <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
            <div style="background: #fff;padding: 50px;justify-content: center;margin-top: 150px;">
            <div class="site-reset-password">
                <h3 class="layui-header" style="height: 40px;"><?= Html::encode($this->title) ?></h3>

                <p style="color:#999;">请选择您的新密码:</p>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true,'class'=>'layui-input']) ?>

                <div class="form-group">
                    <?= Html::submitButton('保存', ['class' => 'layui-btn-normal layui-btn btn-block']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
    </div>
