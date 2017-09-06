<?php if (!defined('THINK_PATH')) exit();?><title>设置</title>
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
<link rel="stylesheet" href="/wltfagent/Public/home/css/set.css">
<body>
<div id="header">
    <span class="header-title">设置</span>
    <a href="<?php echo U('home/index/index');?>" class="fl-left">&lt;</a>
</div>
<div class="list-main">
    <a href="<?php echo U('home/set/changePwd');?>"><div class="list-file bottom-line-block">
        <span>修改登录密码</span><img src="/wltfagent/Public/home/images/1_06.png"/>
    </div></a>
    <?php if($is_set == 1): ?><a href="<?php echo U('home/set/setPayPwd');?>"><div class="list-file">
        <span>设置支付密码</span><img src="/wltfagent/Public/home/images/1_06.png"/>
    </div></a>
    <?php else: ?>
    <a href="<?php echo U('home/set/resetPayPwd');?>"><div class="list-file">
        <span>重置支付密码</span><img src="/wltfagent/Public/home/images/1_06.png"/>
    </div></a><?php endif; ?>
</div>
<div class="list-foot">
    <div class="list-file">
        <span>客服中心</span><img src="/wltfagent/Public/home/images/1_06.png"/>
    </div>
</div>
<a class="login-out" href="<?php echo U('home/login/login_out');?>"><div class="login-out"><span>退出登录</span></div></a>
</body>
</html>