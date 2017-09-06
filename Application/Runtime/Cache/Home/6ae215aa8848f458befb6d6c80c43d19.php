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
    <a href="javascript:;" class="goBank fl-left">&lt;</a>
    绑定银行卡
</div>
<form method="post" action="<?php echo U('home/wallet/bindingBank');?>" target="niu-frame">
<div class="input-card first-page">
    <p>请绑定本人银行卡</p>
    <div class="bank-card">
        <span>银行卡号：</span>
        <input class="" name="bank-card" type="text" placeholder="请输入银行卡号">
    </div>
    <div class="bank-card">
        <span>确认卡号：</span>
        <input class="bank-card2" name="bank-card2" type="text" placeholder="请确认银行卡号">
    </div>
    <div class="Notes">
        注：绑定的银行卡为您在我们平台提现所用的银行卡，不会从你的银行卡中收取任何费用。
    </div>
    <button class="next" type="button">下一步</button>
</div>
<div class="input-massage last-page">
    <p>通过验证银行卡信息确保本人操作</p>
    <form></form>
    <div class="bank-card">
        <span>所属银行：</span>
        <select class="" name="bank-name" style="border: none">
            <option value="" selected>请选择所属银行</option>
            <option value="中国银行">中国银行</option>
            <option value="中国建设银行">中国建设银行</option>
            <option value="中国工商银行">中国工商银行</option>
            <option value="中国农业银行">中国农业银行</option>
        </select>
    </div>
    <ul class="bank-massage">
        <li>
            <span>开户支行：</span>
            <input class="" name="bank-account" type="text" placeholder="请输入开户银行">
        </li>
        <li>
            <span>持卡人：</span>
            <input class="" name="holder-name" type="text" placeholder="持卡人姓名">
        </li>
        <li>
            <span>证件号：</span>
            <input class="" name="card-id" type="text" placeholder="身份证号码">
        </li>
        <li>
            <span>手机号：</span>
            <input class="" name="mobile" type="text" placeholder="银行预留手机号">
        </li>
    </ul>
    <button class="" type="submit">提交</button>
</div>
</form>
</body>
<iframe name="niu-frame" style="display: none"></iframe>
</html>