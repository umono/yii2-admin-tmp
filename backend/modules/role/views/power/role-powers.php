<?php
/**
 * Created by PhpStorm.
 * User: moment
 * Date: 2018/6/5
 * Time: 下午9:01
 */
use yii\widgets\ActiveForm;
?>
<div style="padding: 20px" class="layui-card">
<h3 style="margin-bottom: 10px;"><?=$id?> - 当前角色权限</h3>
<?php $form = ActiveForm::begin([
    'action' => ['role-powers'],
    'method' => 'get']); ?>
<div class="layui-inline">
    <input class="layui-input" type="hidden" name="id" value="<?= $id?>">
</div>
<div class="layui-inline">
    <input class="layui-input" name="name"  autocomplete="off" placeholder="权限名称">
</div>
<div class="layui-inline">
    <input class="layui-input" name="description" autocomplete="off" placeholder="描述">
</div>
<button class="layui-btn" id="search" data-type="reload">搜索</button>

<?php ActiveForm::end(); ?>
<table id="test" class="layui-table" lay-filter="test">
    <thead>
    <tr>
        <th lay-data="{field:'username',sort:true}">权限名称</th>
        <th lay-data="{field:'experience', sort:true}">权限描述</th>
        <th lay-data="{field:'sign',sort:true}">权限状态</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($power as $k => $v):?>
        <tr>
            <td><?= $k?></td>
            <td><?= $v?></td>
            <td>
                <?php
                if (in_array($v,$arr)):?>
                    <input type="checkbox" name="power[]" value="<?= $k?>"  checked lay-filter="switch" lay-skin="switch" lay-text="开|关">
                <?php else:?>
                    <input type="checkbox" name="power[]" value="<?= $k?>"  lay-filter="switch" lay-skin="switch" lay-text="开|关">
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
</div>
<script>
    layui.use('form', function(){
        var form = layui.form;
        var role_id = '<?= $id?>';
        var url = '/role/power/role-power';
        var del = '/role/power/del-role-power';
        form.on('switch(switch)', function(data){
            if (data.elem.checked){
                $.post(url,{id:data.value,role:role_id},function (result) {
                    if (result == 1){
                        layer.msg('操作成功！');
                    }else{
                        layer.msg('操作失败:(');
                    }
                })
            }
            if (data.elem.checked == false){
                $.post(del,{id:data.value,role:role_id},function (result) {
                    if (result == 1){
                        console.log(result);
                        layer.msg('操作成功！');
                    }else{
                        layer.msg('操作失败:(');
                    }
                })
            }
        });
    });
    layui.use('table',function () {
        var table = layui.table;
        //转换静态表格
        table.init('test', {
            height: 315 //设置高度
            ,id:'test'
            ,page:true
            ,limits:[10,20,50,100]
            ,limit: 5
            //注意：请务必确保 limit 参数（默认：10）是与你服务端限定的数据条数一致
            //支持所有基础参数
        });
        $('#search').on('click', function(){
            table.reload('test',{
                where: {
                    name:  $('#name').val(),
                    description : $('#description').val(),
                    type : $('#type').val(),
                }
            });
        });
    })
</script>