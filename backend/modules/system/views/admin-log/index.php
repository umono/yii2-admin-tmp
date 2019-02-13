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
            <input class="layui-input" name="type" id="type" autocomplete="off" placeholder="类型">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="created_id" id="created_id" autocomplete="off" placeholder="操作人ID">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="controller" id="controller" autocomplete="off" placeholder="控制器">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="action" id="action" autocomplete="off" placeholder="动作方法">
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="url" id="url" autocomplete="off" placeholder="URL">
        </div>
        <button class="layui-btn" id="search" data-type="reload">搜索</button>
    </div>
    <table class="layui-table" lay-data="{
            url:'/system/admin-log/get-data-index'
            ,cellMinWidth: 80
            ,id:'test'
            ,page:true
            ,limits:[10,20,50,100]
            ,limit:20
            ,height: 'full-100'
            }" lay-filter="test">
        <thead>
        <tr>
            <th lay-data="{field:'type', sort: true}">类型</th>
            <th lay-data="{field:'controller', sort: true}">控制器</th>
            <th lay-data="{field:'action',sort: true}">动作方法</th>
            <th lay-data="{field:'url',sort: true}">URL</th>
            <th lay-data="{field:'index',}">提交的参数</th>
            <th lay-data="{field:'params',}">返回的参数</th>
            <th lay-data="{field:'created_at',sort: true}">操作时间</th>
            <th lay-data="{field:'created_id',sort: true}">操作人</th>
            <th lay-data="{field:'right',toolbar: '#barDemo',fixed: 'right'}">操作</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看详情</a>
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
                layer.open({
                    type: 1,
                    shade: 0.3,
                    skin: 'layui-layer-demo', //样式类名
                    closeBtn: 1, //不显示关闭按钮
                    shadeClose: true, //开启遮罩关闭
                    area: ['80%', '90%'],
                    content:
                    '<table class="layui-table"><tbody>'+
                    '<tr><th>ID</th><td>'+ data.id  +'</td></tr>'+
                    '<tr><th>类型</th><td>'+ data.type  +'</td></tr>'+
                    '<tr><th>控制器</th><td>'+ data.controller  +'</td></tr>'+
                    '<tr><th>动作方法</th><td>'+ data.action  +'</td></tr>'+
                    '<tr><th>URL</th><td>'+ data.url  +'</td></tr>'+
                    '<tr><th>提交的参数</th><td><pre>'+syntaxHighlight(JSON.parse(data.index)) +'</pre></td></tr>'+
                    '<tr><th>返回的参数</th><td><pre>'+syntaxHighlight(JSON.parse(data.params)) +'</pre></td></tr>'+
                    '<tr><th>操作时间</th><td>'+ data.created_at  +'</td></tr>'+
                    '<tr><th>操作人ID</th><td>'+ data.created_id  +'</td></tr>'+
                    '</tbody></table>'
                });
            }
        });
        $('#search').on('click', function(){
            table.reload('test',{
                where: {
                    created_id:  $('#created_id').val(),
                    action : $('#action').val(),
                    url : $('#url').val(),
                    controller : $('#controller').val(),
                    type : $('#type').val(),
                }
            });
        });
        $('.actionTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });

    function syntaxHighlight(json) {
        if (typeof json != 'string') {
            json = JSON.stringify(json, undefined, 2);
        }
        json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }
</script>
<style>
    pre {
        margin-bottom: 20px;
        padding: 15px;
        font-size: 13px;
        word-wrap: normal;
        white-space: pre;
        overflow: auto;
        border-radius: 4px;
        background: #282c34;
        color: #abb2bf;
    }
    .string { color: green; }
    .number { color: darkorange; }
    .boolean { color: blue; }
    .null { color: magenta; }
    .key { color: red; }
</style>
