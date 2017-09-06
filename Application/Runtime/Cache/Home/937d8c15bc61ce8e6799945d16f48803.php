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
    <span class="header-title">修改登录密码</span>
    <a href="<?php echo U('home/set/setList');?>" class="fl-left">&lt;</a>
</div>
<div class="main">
    <div class="demo ft14">
        <form action="#" method="post" class="cPwd-form">
            <ul>
                <li>
                    <label for="pwd">当前密码：</label>
                    <input type="text" id="pwd" placeholder="请输入旧密码">
                </li>
                <li>
                    <label for="pwd1">新 密 码：</label>
                    <input type="text" id="pwd1" placeholder="请输入新密码">
                </li>
                <li>
                    <label for="pwd2">确认密码：</label>
                    <input type="text" id="pwd2" placeholder="请确认新密码">
                </li>
            </ul>
        </form>
    </div>
</div>
<div class="form-remark">6-16个字符组成，区分大小写，且不能为纯数字，不能有空格和中文字符。</div>
<div class="foot check-button check-main">确定</div>
<div class="foot check-button check-sub">确定</div>
</body>
<script>
$('.input').keyup(function(){
    $('.input').on('change',function(){
        var pwd = $(this).val();
        if(pwd == "" || pwd == null || pwd.length > 16 || pwd.length < 6 || !judgeReg(pwd)){
            layer.msg('请按规定填写正确密码',{icon:2});
        }
    });
    var old_pwd = $('.old-pwd').val();
    var new_pwd = $('.new-pwd').val();
    var re_pwd = $('.re-pwd').val();
    if(old_pwd != "" && new_pwd != "" && re_pwd != ""){
        $(".check-main").css('display','none');
        $(".check-sub").css('display','block');
    }else{
        $(".check-sub").css('display','none');
        $(".check-main").css('display','block');
    }
})
$('.check-sub').on('click',function(){
    var new_pwd = $('.new-pwd').val();
    var old_pwd = $('.old-pwd').val();
    var re_pwd = $('.re-pwd').val();
    $.ajax({
        url: "<?php echo U('home/set/changePwd');?>",
        type: "post",
        dataType: "json",
        data: {old_pwd:old_pwd,type:'check'},
        success:function(data) {
            if (data['code'] == 1) {
                layer.msg('当前密码不存在', {icon: 2});
                return false;
            }else if(data['code'] == 3){
                layer.msg('当前密码格式不正确，请填写正确！', {icon: 2});
                return false;
            }else {
                if (re_pwd == new_pwd) {
                    $.ajax({
                        url: "<?php echo U('home/set/changePwd');?>",
                        type: "post",
                        dataType: "json",
                        data: {new_pwd: new_pwd, type: 'submit'},
                        success: function (data) {
                            if (data['code'] == 1) {
                                layer.msg('修改密码成功', {icon: 1});
                                function _url(){
                                    window.location.href = '<?php echo U("home/set/setList");?>'
                                }
                                setTimeout(_url,2000);
                            }
                            if(data['code'] == 3){
                                layer.msg('新密码格式不正确，请填写正确！', {icon: 2});
                                return false;
                            }
                        }
                    });
                } else {
                    layer.msg('两次密码填写不一致', {icon: 2});
                    return false;
                }
            }
        }
    });
});
</script>
</html>
<!--
<div class="form-group">
                <span class="label">
                    <label class="pwd">当前密码：</label>
                </span>
    <span class="field">
                    <p><input type="password" class="input input-auto old-pwd" name="pwd" value=""/></p>
                </span>
</div>
<div class="form-group">
                <span class="label">
                    <label class="pwd">新 密 码：</label>
                </span>
    <span class="field">
                    <p><input type="password" class="input input-auto new-pwd" name="pwd" value=""/></p>
                </span>
</div>
<div class="form-group group-dif">
                <span class="label">
                    <label class="pwd">确认密码：</label>
                </span>
    <span class="field">
                    <p><input type="password" class="input input-auto re-pwd" name="pwd" value=""/></p>
                </span>
</div>-->