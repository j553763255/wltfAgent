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
<body>
<div id="header">
    <a href="<?php echo U('home/'.$url.'/index');?>" class="fl-left">&lt;</a>
    钱包
</div>
<div class="banner">
    <div class="img-bg">
        <img src="/wltfagent/Public/home/images/ceshi.png">
    </div>
    <p>Michael<!--<?php echo ($user["real_name"]); ?>--></p>
</div>
<div class="moneySum">
    <div class="money">
        <p class="value">￥666666<!--<?php echo ($user["money"]); ?>--></p>
        <p class="name">余额</p>
    </div>
    <div class="integral">
        <p class="value">999<!--<?php echo ($user["points"]); ?>--></p>
        <p class="name">积分</p>
    </div>
    <!--<div class="coin">-->
        <!--<p class="value"><?php echo ($user["join_fee"]); ?></p>-->
        <!--<p class="name">注册金</p>-->
    <!--</div>-->
</div>
<div class="detailed">
    <div class="title">
       <p> 资产明细</p>
    </div>
    <ul class="detailed-box clearfix">
        <li>
            <a href="<?php echo U('/home/wallet/moneyDetail');?>">
                <img src="/wltfagent/Public/home/images/money1.png" />
                <span>余额</span>
            </a>
        </li>
        <li>
            <a href="<?php echo U('/home/wallet/pointsDetail');?>">
                <img src="/wltfagent/Public/home/images/integral1.png" />
                <span>积分</span>
            </a>
        </li>
        <li>
            <a href="<?php echo U('home/wallet/recharge');?>">
                <img src="/wltfagent/Public/home/images/recharge1.png" />
                <span>注册币充值</span>
            </a>
        </li>
        <li>
            <a href="<?php echo U('/home/wallet/bindingBank');?>">
                <img src="/wltfagent/Public/home/images/bank1.png" />
                <span>绑定银行卡</span>
            </a>
        </li>
    </ul>
</div>
<div class="money-tx-box">
    <a href="<?php echo U('home/wallet/cash');?>">提现</a>
    <a href="#">转账</a>
</div>
</body>
<!--
<ul class="content">
    <a href="<?php echo U('/home/wallet/moneyDetail');?>">
        <li class="money">
            <img class="icon" src="/wltfagent/Public/home/images/money.png">
            <span>余额</span>
            <img class="right" src="/wltfagent/Public/home/images/right.png">
        </li>
    </a>
    <a href="<?php echo U('/home/wallet/pointsDetail');?>">
        <li class="integral">
            <img class="icon" src="/wltfagent/Public/home/images/integral.png">
            <span>积分</span>
            <img class="right" src="/wltfagent/Public/home/images/right.png">
        </li>
    </a>
    &lt;!&ndash;<li class="integral" style="border-top: 1px solid #cccccc">&ndash;&gt;
    &lt;!&ndash;<img class="icon" src="/wltfagent/Public/home/images/money.png">&ndash;&gt;
    &lt;!&ndash;<span>游戏余额</span>&ndash;&gt;
    &lt;!&ndash;<span style="float: right;margin-right: 0.15rem"><?php echo ($user["fixed_recharge"]); ?></span>&ndash;&gt;
    &lt;!&ndash;</li>&ndash;&gt;
</ul>
<div class="detailed2">
    <ul class="content">
        &lt;!&ndash;<a href="javascript:;" onclick="mobileBoxMsg('暂未开放充值功能，敬请期待！')">&ndash;&gt;
        <a href="<?php echo U('home/wallet/recharge');?>">
            <li class="money">
                <img class="icon" src="/wltfagent/Public/home/images/recharge.png">
                <span>注册币充值</span>
                <img class="right" src="/wltfagent/Public/home/images/right.png">
            </li>
        </a>
        <?php if($bankUrl == 1): ?><a href="<?php echo U('/home/wallet/bindingBank');?>">
                <li class="integral">
                    <img class="icon" src="/wltfagent/Public/home/images/bank.png">
                    <span>绑定银行卡</span>
                    <img class="right" src="/wltfagent/Public/home/images/right.png">
                </li>
            </a>
            <?php else: ?>
            <a href="<?php echo U('/home/wallet/myBank');?>">
                <li class="integral">
                    <img class="icon" src="/wltfagent/Public/home/images/bank.png">
                    <span>绑定银行卡</span>
                    <img class="right" src="/wltfagent/Public/home/images/right.png">
                </li>
            </a><?php endif; ?>
    </ul>
</div>
<div class="foot-seize"></div>
<div class="foot">
    <?php if($code == 1): ?><a class="cash" href="<?php echo U('home/wallet/cash');?>">提现</a>
        &lt;!&ndash;<a class="cash weihu">提现</a>&ndash;&gt;
        <?php else: ?>
        <a class="cash notCash">提现</a><?php endif; ?>
    &lt;!&ndash;<a class="transfer" href="<?php echo U('home/wallet/transfer');?>">转账</a>&ndash;&gt;
    <a class="transfer weihu">转账</a>
</div>
<script>
    $(function () {
        $(".notCash").click(function () {
            layer.open({
                content: '未到可提现时间！<br>可提现时间为每周三10:00-22:00。'
                ,btn: '我知道了'
            })
        })
        $(".weihu").click(function () {
            layer.open({
                content: '功能维护中，请耐心等待！'
                ,btn: '我知道了'
            })
        })
    });
</script>-->