<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="layui-row layui-col-space15">
        <?php $form = ActiveForm::begin(
            [
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

        <div class="layui-col-md12">
            <div class="layui-card">

                <div class="layui-card-header">修改密码</div>
                <div class="layui-card-body" pad15="">
                    <?php if (Yii::$app->session->hasFlash('success')):?>
                        <div class="alert alert-success" role="alert">
                            <?php echo Yii::$app->session->getFlash('success');?>
                        </div>
                    <?php endif?>
                    <?php if (Yii::$app->session->hasFlash('error')):?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo Yii::$app->session->getFlash('error');?>
                        </div>
                    <?php endif?>
                    <div class="layui-form" lay-filter="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">当前密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="pwd" lay-verify="required" lay-vertype="tips" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password" lay-verify="pass" lay-vertype="tips" autocomplete="off" id="LAY_password" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="setmypass">确认修改</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
