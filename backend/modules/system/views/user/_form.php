<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">
    <div class="user-info-img box-shadow-0 padding-rl-0" style="margin: 20px 0;">
        <img id="avatarPreview" src="<?=
        $model->avatar ? $model->avatar:'http://pic1.16pic.com/00/53/84/16pic_5384253_b.jpg'
        ?>" alt="">
    </div>
    <?php $form = ActiveForm::begin(
        ['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="form-group field-admin-username required">
        <a class=btn_addPic href="javascript:void(0);"><span>更改头像</span>
            <input class="filePrew" title=支持jpg、jpeg、gif、png格式，文件小于5M id="avatarSelect" type="file" name="Admin[avatar]" accept="image/gif,image/jpeg,image/jpg,image/png,image/svg"></a>
        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'pass_word')->passwordInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <div class="layui-form form-group">
        <div><label class="control-label">角色权限</label></div>
        <input type="hidden" name="CreateForm[role][]" value="no">
        <?php
        $DATA = \backend\modules\role\models\AuthItem::getData();
        $userRole = \backend\modules\system\models\Admin::getUserRole($id);
        foreach ($DATA as $k=>$v):?>
            <?php if (in_array($v,$userRole)):?>
                <input type="checkbox" name="CreateForm[role][]" value="<?= $k;?>" title="<?=$v?>" lay-skin="primary" checked>
                <div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary">
                    <span><?=$v?></span><i class="layui-icon layui-icon-ok"></i>
                </div>
            <?php else:?>
                <input type="checkbox" name="CreateForm[role][]" value="<?= $k;?>" title="<?=$v?>" lay-skin="primary">
                <div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary">
                    <span><?=$v?></span><i class="layui-icon layui-icon-ok"></i>
                </div>
            <?php endif;?>

        <?php endforeach;?>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success layui-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $("#avatarSelect").change(function () {
        var obj=$("#avatarSelect")[0].files[0];
        if(!/image\/\w+/.test(obj.type)){
          alert("看清楚，这个需要图片！");

          return false;
        }

        var fr=new FileReader();
        fr.onload=function () {
            $("#avatarPreview").attr('src',this.result);
//                console.log(this.result);
            $("#avatar").val(this.result);
        };
        fr.readAsDataURL(obj);
    })
</script>
<script>
    layui.use('form', function(){
        var form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
    });
</script>