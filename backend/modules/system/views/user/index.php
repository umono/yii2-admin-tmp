<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\role\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="background: #fff;padding: 20px;">
    <div class="dataTable layui-row">
        <div class="layui-inline">
            <input class="layui-input" name="id" id="id" autocomplete="off" placeholder="id">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="name" id="name" autocomplete="off" placeholder="姓名">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="email" id="email" autocomplete="off" placeholder="邮箱">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="username" id="username" autocomplete="off" placeholder="用户名">
        </div>
        <button class="layui-btn" id="search" data-type="reload">搜索</button>
        <?php if (Yii::$app->user->can('system/user/create')):?>
            <button class="layui-btn layui-bg-blue" id="created">创建用户</button>
        <?php endif?>
    </div>
    <table class="layui-table" lay-data="{
            url:'/system/user/user-data'
            ,cellMinWidth: 80
            ,id:'test'
            ,page:true
            ,limits:[10,20,50,100]
            ,limit:20
            ,height: 'full-100'
            }" lay-filter="test">
        <thead>
        <tr>
            <th lay-data="{field:'id', sort: true}">ID</th>
            <th lay-data="{field:'name', sort: true}">姓名</th>
            <th lay-data="{field:'email', sort: true}">邮箱</th>
            <th lay-data="{field:'username', sort: true}">用户名</th>
            <th lay-data="{field:'last_ip', sort: true}">上次登录IP</th>
            <th lay-data="{field:'now_ip',}">当前登录IP</th>
<!--            <th lay-data="{field:'created_id',}">创建人ID</th>-->
<!--            <th lay-data="{field:'created_at',}">创建时间</th>-->
<!--            <th lay-data="{field:'updated_id',}">修改人ID</th>-->
<!--            <th lay-data="{field:'updated_at',}">修改时间</th>-->
            <th lay-data="{field:'right',width:170,toolbar: '#barDemo',fixed: 'right'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <?php if (Yii::$app->user->can('system/user/update')):?>
    <a class="layui-btn layui-btn-xs layui-bg-cyan" lay-event="edit">编辑</a>
    <?php endif?>
    <?php if (Yii::$app->user->can('system/user/delete')):?>
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
                var url = '/system/user/view?id='+data.id;
                layer.open({
                    type: 2,
                    title: '查看',
                    shadeClose: true,
                    shade: 0.3,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['80%', '90%'],
                    content: url,
                });
            } else if(layEvent === 'del'){
                //删除
                layer.confirm('真的要删除么？', function(index){
                    var url = '/system/user/delete?id='+data.id;
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
                        error:function () {
                            layer.msg('操作失败 OR 您无此权限');
                        }
                    });
                });
            } else if(layEvent === 'edit'){ //编辑
                var url = '/system/user/update?id='+data.id;
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
                    email:  $('#email').val(),
                    username:  $('#username').val(),
                    name:  $('#name').val(),
                    id : $('#id').val(),
                }
            });
        });
        $('#created').on('click', function(){
            var url = '/system/user/create';
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
