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
<link rel="stylesheet" href="/wltfagent/Public/home/css/wallet.css">
<script src="/wltfagent/Public/home/js/wallet.js"></script>
<body>
<div id="header">
    <a href="javascript:;" onclick="back();return false;">
        <img class="back" src="/wltfagent/Public/home/images/left.png">
    </a>
    提现
</div>
<form class="transfer" action="<?php echo U('home/wallet/cash');?>" method="post" target="niu-frame">
    <div class="first-page">
        <div class="form-input" >
            <span class="target_account">提现金额</span>
            <input class="target_mobile" name="cash_money" placeholder="请输入提现金额">
        </div>
        <div class="bank">
            <div class="target_account">提现账号</div>
            <div class="bank_msg">
                <?php if(empty($defaultBank)): ?><a href="<?php echo U('home/wallet/bindingBank');?>"><span style="color:#666666;">绑定银行卡>></span></a>
                    <?php else: ?>
                    <img src="/wltfagent/Public/home/images/Bank-logo.png">
                    <span><?php echo ($defaultBank["bank_name"]); ?><span>尾号<?php echo ($defaultBank["bankLast"]); ?></span></span><?php endif; ?>
            </div>
        </div>
        <p class="notes" style="color: #d81e06"><img src="/wltfagent/Public/home/images/wonder.png">每日只可提现一次且提现金额不得小于200元！</p>
        <button type="submit">提交</button>
    </div>
</form>
</body>
</html>