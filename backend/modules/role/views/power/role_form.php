<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\role\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin([
            'options' => ['class'=>'layui-form']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'layui-input']) ?>

    <input type="hidden" name="AuthItem[type]" value="<?= $type?>">

    <?= $form->field($model, 'description')->textarea(['rows' => 6,'class'=>'layui-textarea']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'layui-btn btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
