<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    /* @var $this yii\web\View */
    /* @var $model backend\modules\system\models\Admin */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="layui-col-md12">
    <?php $form = ActiveForm::begin(
        [
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

    <div class="layui-card">
        <div class="layui-card-header">设置我的资料</div>
        <div class="layui-card-body" pad15="">
            <div class="layui-form" lay-filter="">
                <div class="layui-form-item">
                    <div class=" user-info-img box-shadow-0 padding-rl-0" style="margin: 20px 0;">
                        <img class="layui-input-block" id="avatarPreview" src="<?=
                            $model->avatar ? $model->avatar : '/img/avatar.png'
                        ?>" alt="">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="username" value="<?= $model->username ?>" readonly=""
                               class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">不可修改。一般用于后台登入名</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">昵称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="<?= $model->name ?>" lay-verify="nickname"
                               autocomplete="off" placeholder="请输入昵称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">头像</label>
                    <div class="form-group field-admin-username required">
                        <a class=btn_addPic href="javascript:void(0);"><span>更改头像</span>
                            <input class="filePrew" title=支持jpg、jpeg、gif、png格式，文件小于5M id="avatarSelect" type="file"
                                   name="Admin[avatar]"></a>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" name="email" value="<?= $model->email ?>" lay-verify="email"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <?= Html::submitButton('确认修改', ['class' => 'btn btn-success layui-btn']) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    layui.use('form', function () {
        var form = layui.form;

    });
    $("#avatarSelect").change(function () {
        var obj = $("#avatarSelect")[0].files[0];
        var fr = new FileReader();
        fr.onload = function () {
            $("#avatarPreview").attr('src', this.result);
//                console.log(this.result);
            $("#avatar").val(this.result);
        };
        fr.readAsDataURL(obj);
    })
</script>