<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link rel="stylesheet" href="/wltfagent/Public/home/css/RabbitBao.css">
<link rel="stylesheet" href="/wltfagent/Public/home/css/task.css">
<script src="/wltfagent/Public/home/js/RabbitBao.js"></script>
<script src="/wltfagent/Public/admin/js/echarts.js"></script>
<script src="/wltfagent/Public/admin/js/china.js"></script>
    <style>
        span{
            color : green;
        }
    </style>
</head>
<body>
<div id="header">
    <a href="javascript:;" onclick="back()" class="fl-left">&lt;</a>
    转账记录
</div>

<div>
    <?php if(is_array($transfer)): $i = 0; $__LIST__ = $transfer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="panel">
            <div class="head"></div>
            <div class="body">
                <p class="title"><?php echo ($vo["title"]); ?></p>
                <p class="time"><?php echo (date("Y",$vo["create_time"])); ?>年<?php echo (date("m",$vo["create_time"])); ?>月<?php echo (date("d",$vo["create_time"])); ?>日</p>
                <?php if($vo["pay_id"] == $_SESSION['uid']): ?><p class="content">您在<span><?php echo (date("Y年m月d日H:i:s",$vo["create_time"])); ?></span>，向<span><?php echo ($vo["username"]); ?></span>转账<span><?php echo (number_format($vo["money"],2)); ?></span>注册币，手续费<span><?php echo (number_format($vo["fee"],2)); ?></span>注册币。</p>
                <?php elseif($vo["rec_id"] == $_SESSION['uid']): ?>
                    <p class="content"><span><?php echo ($vo["username"]); ?></span>在<span><?php echo (date("Y年m月d日H:i:s",$vo["create_time"])); ?></span>，向您转账<span><?php echo (number_format($vo["money"],2)); ?></span>注册币。</p>
                <?php else: ?>
                    <p class="content">发生未知错误，请联系客服。</p><?php endif; ?>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>

</body>