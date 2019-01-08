<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\modules\v1\models\user\Member */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-view">

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'layui-table'],
        'attributes' => [
            'id',
            'username',
            'nickname',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email',
            'age',
            'sex',
            ['label'=>'会员类型','value'=>$model->member_type == 0?'普通会员':'特殊会员'],
            'phone',
            ['label'=>'状态','value'=>$model->status == 0?'禁用中':'启用中'],
            'created_at',
            'updated_at',
//            'access_token',
//            'allowance',
//            'allowance_updated_at',
        ],
    ]) ?>

</div>
