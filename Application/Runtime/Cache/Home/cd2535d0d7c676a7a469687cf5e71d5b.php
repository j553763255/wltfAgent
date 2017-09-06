<?php if (!defined('THINK_PATH')) exit();?><title></title>
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
<link rel="stylesheet" href="/wltfagent/Public/home/css/myInfo.css">
<body>
<div id="header">
    <span class="header-title">个人详情</span>
    <a href="<?php echo U('home/index/index');?>" class="fl-left">&lt;</a>
</div>
<div class="middle">
    <!--<a class="changeFace" href="javascript:;"><div class="main-dif"><span class="sp-name">头像</span><img src="/wltfagent/Public/home/images/1_05.png"></div></a>-->
    <div class="main-dif"><span class="sp-name">头像</span><img src="/wltfagent/Public/home/images/1_05.png"></div>
    <div class="main-same"><span class="sp-name">姓名</span><span class="sp-val"><?php echo ($user_message["real_name"]); ?></span></div>
    <div class="main-same"><span class="sp-name">级别</span><span class="sp-val"><?php echo ($user_message["rank_name"]); ?></span></div>
    <div class="main-same"><span class="sp-name">我的分红点</span><span class="sp-val"><?php echo ($user_message["dividend"]); ?>&nbsp;</span></div>
    <div class="main-same"><span class="sp-name">奖励分红点</span><span class="sp-val"><?php echo ($user_message["reward_bonus"]); ?>&nbsp;</span></div>
    <div class="main-same"><span class="sp-name">注册时间</span><span class="sp-val"><?php echo (date("Y-m-d",$user_message["create_time"])); ?>&nbsp;</span></div>
    <div class="main-same"><span class="sp-name">我的身份证号</span><span class="sp-val"><?php echo ($user_message["card_id"]); ?></span></div>
</div>
<div class="foot">
    <div class="user-tel"><span class="sp-name">手机号</span><span class="sp-val"><?php echo ($user_message["tel"]); ?></span></div>
    <div class="foot-same"><span class="sp-name">所属公司</span><span class="sp-val"><?php echo ($user_message["service"]); ?></span></div>
</div>
</body>
<div id="change-face" style="display: none">
    <div><img src="/wltfagent/Public/home/images/1_05.png"></div>
    <form action="" method="post">
        <input type="button" name="" value="请选择图片">
    </form>
</div>
<script>
    $(".changeFace").click(function () {
        layer.open({
            type: 1,
            title: "头像上传",
            closeBtn: 0,
            shadeClose: true,
            skin: 'layui-layer-molv',
            content:$("#change-face").html()
        });
    });

</script>