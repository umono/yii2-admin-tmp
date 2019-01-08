<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\role\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">
<h3><?= $model->type ==2 ? '权限信息':'角色信息';?></h3>
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'layui-table'],
        'attributes' => [
            'name',
            'description:ntext',
            'rule_name',
            'data',
            [
                'label'=> '创建时间',
                'value' => date('Y-m-h H:i:s',$model->created_at)
            ],
            [
                'label'=> '更新时间',
                'value' => date('Y-m-h H:i:s',$model->updated_at)
            ],
        ],
    ]) ?>

</div>

<?php

$data = \backend\modules\role\models\AuthItem::find()->with('authItemChildren.child0')
    ->where(['name' => $model->name ])->asArray()->all();
if ($model->type == 1 && !empty($data)):

//    print_r($data);die;
?>
    <h3>权限列表</h3>
<table class="layui-table">
    <tbody>
<?php   foreach ($data as $key => $val):
        foreach ($val['authItemChildren'] as $k => $v):?>
        <tr>
            <td><?= $v['child']?></td>
            <td><?= $v['child0']['description']?></td>
        </tr>

    <?php endforeach;
    endforeach;
    ?>
    </tbody>
</table>

<?php endif;?>