<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\Admin */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">
    <div class="user-info-img">
        <img  src="<?=
        $model->avatar ? $model->avatar:'http://pic1.16pic.com/00/53/84/16pic_5384253_b.jpg'
        ?>" alt="">
    </div>

    <?= DetailView::widget([
        'options' => ['class' => 'layui-table'],
        'model' => $model,
        'attributes' => [
            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email',
            'name',
//            'avatar',
            'last_ip',
            'last_time:datetime',
            'now_ip',
            'now_time:datetime',
            ['label'=>'角色','value'=>
                implode(',',\backend\modules\system\models\Admin::getUserRole($model->id))
            ],
            'address',
            'status',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
        ],
    ]) ?>
</div>
