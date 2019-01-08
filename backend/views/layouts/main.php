<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="padding:20px;background: #f4f8fc;">
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
<script>
    layui.use('form', function(){
        var form = layui.form;
        form.on('submit(*)', function(data){
            var action=data.form.action;
            $.post(action,data.field,function(res){
                if (res == 1){
                    layer.msg('操作成功');
                    var index = parent.layer.getFrameIndex(window.name);
                    setTimeout(function(){
                        parent.layer.close(index);
                        window.parent.location.reload();
                    }, 500);
                }else{
                    layer.msg('操作失败 OR 您无此权限');
                };
            });
            return false;
        });
    });
</script>
</html>
<?php $this->endPage() ?>
