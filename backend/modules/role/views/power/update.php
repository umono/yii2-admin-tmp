<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\role\models\AuthItem */

$this->title = Yii::t('app', 'Update Auth Item: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-update">
    <?php if ($model->type == 1):?>
        <?= $this->render('role_form', [
            'model' => $model,
            'type' => $model->type,
        ]) ?>
    <?php else:?>
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $model->type,
    ]) ?>
    <?php endif;?>
</div>
