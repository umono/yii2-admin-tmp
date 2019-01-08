<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\role\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '网站用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="background: #fff;padding: 20px;">
    <div class="dataTable layui-row">
        <div class="layui-inline">
            <input class="layui-input" name="id" id="id" autocomplete="off" placeholder="id">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="email" id="email" autocomplete="off" placeholder="邮箱">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="phone" id="phone" autocomplete="off" placeholder="电话">
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
            url:'/system/member/get-data'
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
            <th lay-data="{field:'nickname', sort: true}">昵称</th>
            <th lay-data="{field:'email', sort: true}">邮箱</th>
            <th lay-data="{field:'username', sort: true}">用户名</th>
            <th lay-data="{field:'phone', sort: true}">电话</th>
            <th lay-data="{field:'sex'}">性别</th>
            <th lay-data="{field:'member_type',width:100,toolbar:'#MemberType',sort: true}">会员类型</th>
            <th lay-data="{field:'status',width:100,toolbar:'#MemberStatus',sort: true}">启用状态</th>
            <th lay-data="{field:'right',width:170,toolbar: '#barDemo',fixed: 'right'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="MemberType">
    {{# if (d.member_type == 1 ){ }}
    <div class="layui-table-cell laytable-cell-1-status">  <button class="layui-btn layui-btn-xs  layui-bg-black">特殊会员</button>  </div>
    {{# }else{ }}
    <div class="layui-table-cell laytable-cell-1-status">  <button class="layui-btn layui-btn-primary layui-btn-xs">普通用户</button>  </div>
    {{# } }}
</script>
<script type="text/html" id="MemberStatus">
    {{# if (d.status == 10 ){ }}
    <div class="layui-table-cell laytable-cell-1-status">  <button class="layui-btn layui-btn-xs layui-btn-normal ">启用中</button>  </div>
    {{# }else{ }}
    <div class="layui-table-cell laytable-cell-1-status">  <button class="layui-btn layui-bg-black layui-btn-xs">禁用中</button>  </div>
    {{# } }}
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <?php if (Yii::$app->user->can('system/member/delete')):?>
    {{# if (d.status == 10 ){ }}
    <a class="layui-btn layui-btn-xs  layui-bg-red" lay-event="del">禁用</a>
    {{# }else{ }}
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="open">启用</a>
    {{# } }}
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
                var url = '/system/member/view?id='+data.id;
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
                //禁用
                layer.confirm('真的要禁用么？', function(index){
                    var url = '/system/member/delete?id='+data.id;
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    $.ajax({
                        type: 'POST',
                        url: url,//发送请求
                        data:{
                            _csrf:csrfToken,
                            status:0,
                        },
                        dataType : "html",
                        success: function(result) {
                            if (result == 1){
                                layer.msg('禁用成功');
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                layer.close(index);
                                table.reload('test',{
                                });
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
            }else if(layEvent === 'open'){
                //禁用
                layer.confirm('真的要启用么？', function(index){
                    var url = '/system/member/delete?id='+data.id;
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    $.ajax({
                        type: 'POST',
                        url: url,//发送请求
                        data:{
                            _csrf:csrfToken,
                            status:10,
                        },
                        dataType : "html",
                        success: function(result) {
                            if (result == 1){
                                layer.msg('启用成功');
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                layer.close(index);
                                table.reload('test',{
                                });
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
            }
        });

        $('#search').on('click', function(){
            table.reload('test',{
                where: {
                    email:  $('#email').val(),
                    username:  $('#username').val(),
                    id : $('#id').val(),
                }
            });
        });
        $('#created').on('click', function(){
            var url = '/system/member/create';
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
