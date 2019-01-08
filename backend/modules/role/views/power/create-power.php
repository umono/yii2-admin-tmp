<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\role\models\AuthItem */

$this->title ='创建权限信息';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <h3 style="margin-bottom: 20px"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', ['model' => $model,'type' =>2]) ?>

</div>
