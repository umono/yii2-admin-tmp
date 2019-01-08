<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\role\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Auth Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="background: #fff;padding: 20px;">
    <div class="dataTable layui-row">
        <div class="layui-inline">
            <input class="layui-input" name="name" id="name" autocomplete="off" placeholder="权限名称">
        </div>
        <div class="layui-inline">
        <input class="layui-input" name="description" id="description" autocomplete="off" placeholder="描述">
        </div>
        <button class="layui-btn" id="search" data-type="reload">搜索</button>
        <?php if (Yii::$app->user->can('role/power/create-power')):?>
            <button class="layui-btn layui-bg-blue" id="created">创建权限</button>
        <?php endif?>
    </div>
    <table class="layui-table" lay-data="{
            url:'/role/power/power-data'
            ,cellMinWidth: 80
            ,id:'test'
            ,page:true
            ,limits:[10,20,50,100]
            ,limit:20
            ,height: 'full-100'
            }" lay-filter="test">
        <thead>
        <tr>
<!--            <th lay-data="{type:'checkbox', fixed: 'left'}"></th>-->
            <th lay-data="{field:'name', sort: true}">权限名称</th>
            <th lay-data="{field:'description', sort: true}">描述</th>
            <th lay-data="{field:'rule_name', sort: true}">规则</th>
            <th lay-data="{field:'created_at',templet: '<div>{{ dateFormat(d.created_at)}}</div>'}">创建时间</th>
            <th lay-data="{field:'updated_at',templet: '<div>{{ dateFormat(d.updated_at)}}</div>'}">更新时间</th>
            <th lay-data="{field:'right',toolbar: '#barDemo',fixed: 'right'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <?php if (Yii::$app->user->can('role/power/update')):?>
    <a class="layui-btn layui-btn-xs layui-bg-cyan" lay-event="edit">编辑</a>
    <?php endif?>

    <?php if (Yii::$app->user->can('role/power/delete')):?>
    <a class="layui-btn layui-bg-red layui-btn-xs" lay-event="del">删除</a>
    <?php endif?>
</script>
<script>
    layui.use('table', function(){
        var table = layui.table;
        table.on('tool(test)', function(obj){
            //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的DOM对象
            if(layEvent === 'detail'){ //查看
                var url = '/role/power/view?id='+data.name;
                layer.open({
                    type: 2,
                    title: '创建权限信息',
                    shadeClose: true,
                    shade: 0.3,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['80%', '90%'],
                    content: url,
                });
            } else if(layEvent === 'del'){
                //删除
                layer.confirm('您确定要删除么？', function(index){
                    var url = '/role/power/delete?id='+data.name;
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    $.ajax({
                        type: 'POST',
                        url: url,//发送请求
                        data:{
                            _csrf:csrfToken,
                            id:data.name,
                        },
                        dataType : "html",
                        success: function(result) {
                            if (result == 1){
                                layer.msg('删除成功');
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                layer.close(index);
                            }else{
                                layer.msg('操作失败 OR 您无此权限');
                                layer.close(index);
                            };
                        },
                        error:function (result) {
                            layer.msg('操作失败 OR 您无此权限');
                        }
                    });
                });
            } else if(layEvent === 'edit'){ //编辑
                var url = '/role/power/update?id='+data.name;
                layer.open({
                    type: 2,
                    title: '编辑',
                    shadeClose: true,
                    shade: 0.3,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['80%', '90%'],
                    content: url,
                    cancel: function(){
                        table.reload('test',{
                        });
                    }
                });
            }
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
        $('#created').on('click', function(){
            var url = '/role/power/create-power';
            layer.open({
                type: 2,
                title: '信息',
                shadeClose: true,
                shade: 0.3,
                maxmin: true, //开启最大化最小化按钮
                area: ['80%', '90%'],
                content: url,
                cancel: function(){
                    table.reload('test',{
                    });
                }
            });
        });
        $('.actionTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
</script>
