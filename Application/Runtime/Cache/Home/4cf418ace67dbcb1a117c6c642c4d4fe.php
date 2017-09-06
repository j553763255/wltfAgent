<?php if (!defined('THINK_PATH')) exit();?><title>系统通知</title>
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
<link rel="stylesheet" href="/wltfagent/Public/home/css/news.css">
<body>
<div id="header">
    <span class="header-title">系统通知</span>
    <a href="<?php echo U('home/information/index');?>" class="fl-left">&lt;</a>
</div>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="news-list">
        <div class="list-title">江苏兔尔宝文化发展有限公司</div>
        <div class="list-type">类型：系统通知</div>
        <div class="list-time">发布时间：<?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?><span class="read-message" data-id="<?php echo ($vo["id"]); ?>">查看详情</span></div>
    </div>
    <div class="info-dialogs">
        <div class="message-title title<?php echo ($vo["id"]); ?>" style="text-align: center;"><?php echo ($vo["title"]); ?></div>
        <div class="message-content content<?php echo ($vo["id"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($vo["content"]); ?></div>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
</body>
<script>
    $('.read-message').on('click',function(){
        var id = $(this).attr('data-id');
        layer.open({
            title:$('.title'+id).html(),
            content: $('.content'+id).html(),
            btn: '我知道了'
        });
    });
</script>
</html>