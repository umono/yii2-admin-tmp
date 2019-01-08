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

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'class' => 'layui-input']) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true,'class' => 'layui-input']) ?>

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