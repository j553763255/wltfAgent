<?php if (!defined('THINK_PATH')) exit();?>﻿<title>卧龙腾飞加盟系统-用户登录</title>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-cn">
<head>
    <META name="Pragma" CONTENT="no-cache">
    <META name="Cache-Control" CONTENT="no-cache">
    <META name="Expires" CONTENT="0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>卧龙腾飞加盟商平台</title>
    <link rel="stylesheet" href="/wltfagent/Public/home/css/common.css">
    <link rel="stylesheet" href="/wltfagent/Public/home/css/home.css">
    <link rel="stylesheet" href="/wltfagent/Public/home/css/style.css">
    <script type="application/javascript" src="/wltfagent/Public/home/js/jquery.js"></script>
    <script type="application/javascript" src="/wltfagent/Public/home/layer/layer.js"></script>
    <!--<script type="application/javascript" src="/wltfagent/Public/home/layer_mobile/layer.js"></script>-->
    <script type="application/javascript" src="/wltfagent/Public/home/js/common.js"></script>
    <script type="application/javascript" src="/wltfagent/Public/home/js/style.js"></script>
</head>
<iframe name="niu-frame" style="display: none"></iframe>
<link rel="stylesheet" href="/wltfagent/Public/home/css/login.css">
<body>
<a class="register" href="<?php echo U('home/login/register');?>"><img src="/wltfagent/Public/home/images/hands.png">&nbsp;注册</a>
<!--<a href="/Public/download/turboet_20170526.apk" download="turboet" style="font-size: 0.01rem;color: #373446">游戏下载</a>-->
<div class="logo">
    <img src="/wltfagent/Public/home/images/logo.png">
</div>
<form method="post" action="<?php echo U('home/login/index');?>" target="niu-frame">
    <table class="form">
        <tr class="username">
            <td>
                <span><img src="/wltfagent/Public/home/images/username.png"></span>用户名
                <input type="text" name="username">
            </td>
        </tr>
        <tr class="password">
            <td>
                <span><img src="/wltfagent/Public/home/images/password.png"></span>密&nbsp;&nbsp;&nbsp;码
                <input type="password" name="password">
            </td>
        </tr>
    </table>
    <button class="button" type="submit">登录</button>
</form>
<iframe name="niu-frame" style="display: none"></iframe>
</body>