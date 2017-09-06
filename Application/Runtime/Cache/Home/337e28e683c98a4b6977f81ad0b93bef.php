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
<link rel="stylesheet" href="/wltfagent/Public/home/css/support.css">
<body>
<div id="header">
    <span class="header-title">收入管理</span>
    <a href="<?php echo U('home/support/index');?>" class="fl-left">&lt;</a>
</div>
<div class="table-title">
    <ul>
        <li>时间</li>
        <li>奖金<hr class="line"></li>
        <li>额外奖金<hr class="line"></li>
        <li>操作<hr class="line"></li>
    </ul>
</div>
<?php if($list == ''): ?><br/>
    <div style="text-align: center;font-size: 0.2rem">暂无信息</div>
<?php else: ?>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="table-content">
    <div class="table-list">
        <ul>
            <li><?php echo ($vo["date"]); ?><hr></li>
            <li><?php echo ($vo["amount"]); ?></li>
            <li><?php echo ($vo["task"]); ?></li>
            <li class="drop-down" data-id="<?php echo ($vo["id"]); ?>">查看<img class="img img<?php echo ($vo["id"]); ?>" src="/wltfagent/Public/home/images/3_30.png"/></li>
        </ul>
    </div>
    <div class="table-hidden table-hidden<?php echo ($vo["id"]); ?>">
        <ul>
            <li>推荐奖励 <?php echo ($vo["recommend"]); ?></li>
            <li>服务奖励 <?php echo ($vo["service"]); ?></li>
            <li>项目分红 <?php echo ($vo["bonus"]); ?></li>
            <li>任务奖励 <?php echo ($vo["task"]); ?></li>
        </ul>
    </div>
</div><?php endforeach; endif; else: echo "" ;endif; ?>
<div class="end" data-num="<?php echo ($num); ?>" data-time="<?php echo ($close_time); ?>"></div>
<div class="icon" style="text-align: center;font-size: 0.2rem"></div><?php endif; ?>
</body>
<script>
    $('.drop-down').on('click',function(){
        var id = $(this).attr('data-id');
        $('.img'+id).addClass('img-animal');
        var ds = $('.table-hidden'+id).css('display');
        if(ds == "none"){
            $('.table-hidden'+id).slideDown();
        }else{
            $('.img'+id).removeClass('img-animal');
            $('.table-hidden'+id).slideUp();
        }
    });

    //滚动加载事件
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= scrollHeight){
            ajaxFollowList();//后台查询数据
        }
    });

    // 后台接口加载数据函数
    function ajaxFollowList(){
        var num = $('.end').attr('data-num');
        var time = $('.end').attr('data-time');
        var url = "<?php echo U('home/support/income');?>";//后台接口
        $.ajax({
            type:"post",
            url:url, //接口
            dataType:'json',
            data:{time:time,num:num},
            success:function(data) {
                var html = '';
                if(data != null){
                    if (data.length > 0) {
                        //获取所见页面的最后一条数据的id
                        var length = data.length - 1;
                        var new_num = data[length].id;
                        var new_time = data[length].time;
                        for (var i = 0; i < data.length; i++) {
                            html += '<div class="table-content">';
                            html += '<div class="table-list">';
                            html += ' <ul>';
                            html += '<li>'+data[i]["date"]+'<hr></li>';
                            html += '<li>'+data[i]["amount"]+'<hr></li>';
                            html += '<li>'+data[i]["task"]+'<hr></li>';
                            html += '<li class="drop-down" data-id="'+data[i]["id"]+'">查看<img class="img img'+data[i]["id"]+'" src="/wltfagent/Public/home/images/3_30.png"/></li>';
                            html += '</ul>';
                            html += '</div>';
                            html += '<div class="table-hidden table-hidden'+data[i]["id"]+'">';
                            html += '<ul>';
                            html += '<li>推荐奖励'+ data[i]["recommend"]+'</li>';
                            html += '<li>服务奖励'+ data[i]["service"]+'</li>';
                            html += '<li>项目分红'+ data[i]["bonus"]+'</li>';
                            html += '<li>任务奖励'+ data[i]["task"]+'</li>';
                            html += '</ul>';
                            html += '</div>';
                            html += '</div>';
                        }
                        $(".end").append(html);//查询到的数据进行页面上的布置
                        $(".end").attr('data-num',new_num);
                        $(".end").attr('data-time',new_time);
                        console.log($(".end").attr('data-time'));
                    }else{
                        html += "暂无数据可加载..."
                        $('.icon').html(html);
                    }
                }else{
                    html += "暂无数据可加载..."
                    $('.icon').html(html);
                }
            }
        });
    }
</script>
</html>