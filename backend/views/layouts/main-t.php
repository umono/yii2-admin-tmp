<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use Yii;
AppAsset::register($this);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理模板系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="./layui/css/layui.css" media="all">
    <link rel="stylesheet" href="./css/admin.css" media="all">
</head>
<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect="">
                    <a href="javascript:;" layadmin-event="refresh" title="刷新" lay-tips="刷新">
                        <i class="layui-icon" style="font-weight: 700">&#xe669;</i>
                    </a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

<!--                <li class="layui-nav-item" lay-unselect>-->
<!--                    <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">-->
<!--                        <i class="layui-icon layui-icon-notice"></i>-->
<!---->
<!--                        <!-- 如果有新消息，则显示小圆点 -->-->
<!--                        <span class="layui-badge-dot"></span>-->
<!--                    </a>-->
<!--                </li>-->
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite><?=  Yii::$app->user->identity->username?></cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="system/user/me">基本资料</a></dd>
                        <dd><a lay-href="system/user/reset-password">修改密码</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <?= Html::beginForm(['/site/logout'], 'post')?>
                    <button  class="layui-btn layui-btn-primary " lay-tips="退出" style="border:none;color: red;"><i class="layui-icon">&#xe643;</i></>
                    <?= Html::endForm()?>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="console.html">
                    <span>LOGO</span>
                </div>
                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    <li data-name="home" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="后台管理" lay-direction="2">
                            <i class="layui-icon layui-icon-home"></i>
                            <cite>后台管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd data-name="控制台" class="layui-this">
                                <a lay-href="site/home">控制台</a>
                            </dd>
                            <?php if (\backend\tools\AuthButton::is_auth()):?>
                            <dd data-name="管理员列表">
                                <a lay-href="system/user/index">管理员列表</a>
                            </dd>
                            <dd data-name="权限列表">
                                <a lay-href="role/power/index">权限管理</a>
                            </dd>
                            <dd data-name="角色列表">
                                <a lay-href="role/power/role">角色管理</a>
                            </dd>
                            <dd data-name="操作日志">
                                <a lay-href="system/log/index">操作日志</a>
                            </dd>
                            <?php endif;?>
                        </dl>
                    </li>
                    <li data-name="用户" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="用户" lay-direction="2">
                            <i class="layui-icon layui-icon-user"></i>
                            <cite>用户</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="system/member/index">网站用户</a></dd>
                        </dl>
                    </li>
                    <li data-name="set" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="设置" lay-direction="2">
                            <i class="layui-icon layui-icon-set"></i>
                            <cite>设置</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a href="javascript:;">我的设置</a>
                                <dl class="layui-nav-child">
                                    <dd><a lay-href="system/user/me">基本资料</a></dd>
                                    <dd><a lay-href="system/user/reset-password">修改密码</a></dd>
                                </dl>
                            </dd>
                        </dl>
                    </li>

                </ul>
            </div>
        </div>

        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <!-- <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div> -->
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="site/home" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>


        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="site/home" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script src="./layui/layui.js"></script>
<script>

    layui.config({
        base: './' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>
</body>
</html>


