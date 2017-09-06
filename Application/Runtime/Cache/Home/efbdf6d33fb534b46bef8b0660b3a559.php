<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <link rel="stylesheet" href="/wltfagent/public/admin/css/pintuer.css">
    <!--<style type="text/css">-->
        <!--*{ padding: 0; margin: 0; }-->
        <!--body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 6px; }-->
        <!--.message{width: 400px;height: 150px;margin:auto;border:1px solid #1B8F24;margin-top: 30px;}-->
        <!--.head{width: 100%;height: 30px;background: rgb(222,245,194);text-align: center;padding-top: 5px;}-->
        <!--.content{height: 120px;width: 100%;}-->
        <!--.success ,.error{text-align: center;margin-top: 30px;}-->
        <!--.jump{text-align: center;margin-top: 20px;}-->
    <!--</style>-->
</head>
<body>
<div class="dialog open" style="position: fixed;z-index: 999;top: 0;left: 0;right:0;bottom: 0;width: 30%;height:25%;margin: auto;">
    <div class="dialog-head"><span>提示</span></div>
    <div class="dialog-body">
        <?php if(isset($message)) {?>
        <p class="text-main"> <?php echo($message); ?><b id="wait"><?php echo($waitSecond); ?></b><span class="icon-check-circle-o"></span></p>
        <?php }else{?>
        <p class="text-dot"> <?php echo($error); ?><b id="wait"><?php echo($waitSecond); ?></b><span class="icon-times-circle-o"></span></p>
        <?php }?>
        <p class="detail"></p>

        <p class="jump">
            <a id="href" href="<?php echo($jumpUrl); ?>"><button class="button float-right">确定</button></a>
        </p>
    </div>
</div>
<!--<div class="message">-->
    <!--<div class="head"><span>Ace Admin提示信息:</span></div>-->
    <!--<div class="content">-->
        <!--<?php if(isset($message)) {?>-->
        <!--<p class="success">:) <?php echo($message); ?></p>-->
        <!--<?php }else{?>-->
        <!--<p class="error">:( <?php echo($error); ?></p>-->
        <!--<?php }?>-->
        <!--<p class="detail"></p>-->
        <!--<p class="jump">-->
            <!--<a id="href" href="<?php echo($jumpUrl); ?>">如果你的浏览器没有自动跳转，请点击这里...</a>-->
            <!--<br />-->
            <!--等待时间： <b id="wait"><?php echo($waitSecond); ?></b>-->
        <!--</p>-->
    <!--</div>-->
<!--</div>-->
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>