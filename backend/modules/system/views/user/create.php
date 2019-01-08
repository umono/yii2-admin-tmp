<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\Admin */

$this->title = Yii::t('app', 'Create Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-create">
    <?php $form = ActiveForm::begin([
            'options' => ['class'=>'layui-form']
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <div class="layui-form form-group">
        <div><label class="control-label">角色权限</label></div>
        <input type="hidden" name="CreateForm[role][]" value="no">
        <?php
        $DATA = \backend\modules\role\models\AuthItem::getData();
        foreach ($DATA as $k=>$v):?>
        <input type="checkbox" name="CreateForm[role][]" value="<?= $k;?>" title="<?=$v?>" lay-skin="primary">
        <div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary">
            <span><?=$v?></span><i class="layui-icon layui-icon-ok"></i>
        </div>
        <?php endforeach;?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success layui-btn']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<script>
    layui.use('form', function(){
        var form = layui.form;
    });
</script>