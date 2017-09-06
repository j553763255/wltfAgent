<?php if (!defined('THINK_PATH')) exit();?><title>合作伙伴</title>
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
<link rel="stylesheet" href="/wltfagent/Public/home/css/support.css">
<body>
<div class="header">
    <span class="header-title">合作伙伴</span>
    <a href="#" onclick="javascript :history.back();return false;"><span><img src="/wltfagent/Public/home/images/1_02.png"/></span></a>
</div>
<div class="partner-list">
    <ul>
        <?php if(is_array($myTeam)): $i = 0; $__LIST__ = $myTeam;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!--<a href="<?php echo U('home/support/nextTeam','uid='.$vo['id']);?>">-->
                <li class="next1" data-id="1">
                    <img src="/wltfagent/Public/home/images/1_03.png">
                    <span><?php echo ($vo["real_name"]); ?></span>
                    <img src="/wltfagent/Public/home/images/1_06.png">
                    <span><?php echo ($vo["tel"]); ?></span>
                </li>
            <!--</a>--><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
</body>
<script>
    $('.next1').on('click',function(){
        var id = $(this).attr('data-id');
        $.post("<?php echo U('home/support/nextTeam');?>",{id:id});
    });
</script>
</html>