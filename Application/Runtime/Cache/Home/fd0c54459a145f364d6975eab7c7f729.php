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
<link rel="stylesheet" href="/wltfagent/Public/home/css/set.css">
<body>
<div id="header">
    <a class="comeBack fl-left">&lt;</a>
    <span class="header-title">设置支付密码</span>
</div>
<div class="step-two">
    <form  method="post" class="form-x">
        <div class="pay-user"><span>账户名称</span><input class="input-position" type="text" name="user_name" value="" /></div>
        <div class="pay-nav">
            <div class="pay-pwd bottom-line-block">支付密码<input class="input pays-pwd" type="password" name="pay-pwd" value="" placeholder="6位纯数字"/></div>
            <div class="pay-pwd">确认密码<input class="input pay-rewrite" type="password" name="" value="" placeholder="请再次输入"/></div>
        </div>
        <!--<div class="check-button check-main button-submit">完成</div>-->
        <div class="check-button check-sub button-submit" id="button-submit">完成</div>
    </form>
</div>
<div class="step-one">
    <form  method="post" class="form-x">
        <div class="check-content">
            <div class="check-file bottom-line-block">真实姓名<input class="real-name" type="text" name="" value="" placeholder="请输入姓名"/></div>
            <div class="check-file bottom-line-block">身份证号<input class="cardID" type="text" name="" value="" placeholder="请输入身份证号"/></div>
            <div class="check-file">确认输入<input class="rewrite-id" type="text" name="" value="" placeholder="请再次输入身份证号"/></div>
        </div>
        <div class="check-button check-main step">下一步</div>
        <div class="check-button check-sub step" id="step">下一步</div>
    </form>
</div>
</body>
<script>
    $(".comeBack").click(function () {
        var display = $('.step-one').css('display');
        if (display == 'none'){
            $(".step-one").hide();
        }else {
            javascript :history.back(-1);return false;
        }
    });
    $('.check-file').on('keyup',function(){
//        $('.real-name').on('change',function(){
//            var real_name = $('.real-name').val();
//            if(isNaN(pwd) || pwd.length < 6){
//                layer.msg('支付密码只能是6位数字',{icon:2});
//            }
//        });
        var real_name = $('.real-name').val();
        var card_ID = $('.cardID').val();
        var rewrite_id = $('.rewrite-id').val();
        if(real_name != "" && card_ID != "" && rewrite_id != ""){
            $(".check-main").css('display','none');
            $(".check-sub").css('display','block');
        }else{
            $(".check-sub").css('display','none');
            $(".check-main").css('display','block');
        }
    });

    $('#step').on('click',function(){
        var real_name = $('.real-name').val();
        var card_ID = $('.cardID').val();
        var rewrite_id = $('.rewrite-id').val();
        if(card_ID == rewrite_id){
            $.ajax({
                url: "<?php echo U('home/set/setPayPwd');?>",
                type: "post",
                dataType: "json",
                data: {real_name: real_name, type: 'next_step',card_id: card_ID},
                success: function (data) {
                    if(data['code'] == 1){
                        layer.msg('账户名称不存在',{icon:2});
                    }
                    if(data['code'] == 2){
                        layer.msg('身份证号不正确！',{icon:2});
                    }
                    if(data['code'] == 3){
                        $('.step-two').css('display','block');
                        $('.step-one').css('display','none');
                        $('input[name="user_name"]').val(data['user_name']);
                    }
                }
            });
        }
    });
//    $('.pay-pwd').on('keyup',function(){
//        var pay_pwd = $('.pay-pwd').val();
//        var pay_rewrite = $('.pay-rewrite').val();
//        if(pay_pwd != "" && pay_rewrite != ""){
//            $(".check-main").css('display','none');
//            $(".check-sub").css('display','block');
//        }else{
//            $(".check-sub").css('display','none');
//            $(".check-main").css('display','block');
//        }
//    });

    $('.input').on('blur',function(){
        var pwd = $(this).val();
        if(isNaN(pwd) || pwd.length != 6){
            layer.msg('支付密码只能是6位数字',{icon:2});
        }
    });
    $('#button-submit').on('click',function(){
        var pay_pwd = $('.pays-pwd').val();
        var pay_rewrite = $('.pay-rewrite').val();
        if(pay_pwd == pay_rewrite){
            $.ajax({
                url: "<?php echo U('home/set/setPayPwd');?>",
                type: "post",
                dataType: "json",
                data: {pay_pwd: pay_pwd, type: 'submit'},
                success: function (data) {
                    if(data['code'] == 2){
                        layer.msg('支付密码设置成成功！',{icon:1});
                        function _url(){
                            window.location.href = '<?php echo U("home/set/setList");?>'
                        }
                        setTimeout(_url,2000);
                    }
                    if(data['code'] == 1){
                        layer.msg('支付密码只能是6位数字',{icon:2});
                    }
                    if(data['code'] == 3){
                        layer.msg('网络故障，请重新设置！',{icon:2});
                    }
                }
            });
        }
    });
</script>
</html>