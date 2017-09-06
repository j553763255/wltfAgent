<?php if (!defined('THINK_PATH')) exit();?><title>排行榜</title>
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
<link rel="stylesheet" href="/wltfagent/Public/home/css/rankingList.css">
<script src="/wltfagent/Public/home/js/rankingList.js"></script>
<body>
<div id="header">
    <a href="javascript:;" onclick="back();return false;" class="fl-left">&lt;</a>
    排行榜
</div>
<div class="nav">
    <div class="active 1" id="incomeRanking"><p>收入</p></div>
    <div id="achievementRanking"><p>业绩</p></div>
    <div id="pointsRanking"><p>积分</p></div>
</div>

<div class="rankPanel incomeRanking">
    <?php if(is_array($incomeRanking)): $i = 0; $__LIST__ = $incomeRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["id"] == $_SESSION['uid']): ?><div class="mySelf rankingList">
                <p class="ranking"><?php echo ($key + 1); ?></p>
                <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php else: ?>-->
                <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
                <p class="value"><?php echo ($vo["income"]); ?></p>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    <?php if(is_array($incomeRanking)): $i = 0; $__LIST__ = $incomeRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key < 3): ?><div class="rankingList active2 ">
                <p class="ranking"><?php echo ($key + 1); ?></p>
                <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php else: ?>-->
                <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
                <p class="value"><?php echo ($vo["income"]); ?></p>
            </div>
            <?php else: ?>
            <div class="rankingList">
                <p class="ranking"><?php echo ($key + 1); ?></p>
                <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php else: ?>-->
                <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
                <p class="value"><?php echo ($vo["income"]); ?></p>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="rankPanel achievementRanking">
<?php if(is_array($achievementRanking)): $i = 0; $__LIST__ = $achievementRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["id"] == $_SESSION['uid']): ?><div class="mySelf rankingList">
            <p class="ranking"><?php echo ($key + 1); ?></p>
            <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                    <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                    <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php else: ?>-->
                    <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
            <p class="value"><?php echo ($vo["achievement"]); ?></p>
        </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
<?php if(is_array($achievementRanking)): $i = 0; $__LIST__ = $achievementRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key < 3): ?><div class="rankingList active2 ">
            <p class="ranking"><?php echo ($key + 1); ?></p>
            <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                    <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                    <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php else: ?>-->
                    <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
            <p class="value"><?php echo ($vo["achievement"]); ?></p>
        </div>
        <?php else: ?>
        <div class="rankingList">
            <p class="ranking"><?php echo ($key + 1); ?></p>
            <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                    <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                    <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php else: ?>-->
                    <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
            <p class="value"><?php echo ($vo["achievement"]); ?></p>
        </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="rankPanel pointsRanking">
    <?php if(is_array($pointsRanking)): $i = 0; $__LIST__ = $pointsRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["id"] == $_SESSION['uid']): ?><div class="mySelf rankingList">
                <p class="ranking"><?php echo ($key + 1); ?></p>
                <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                    <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                    <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php else: ?>-->
                    <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
                <p class="value"><?php echo ($vo["points"]); ?></p>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    <?php if(is_array($pointsRanking)): $i = 0; $__LIST__ = $pointsRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($key < 3): ?><div class="rankingList active2 ">
                <p class="ranking"><?php echo ($key + 1); ?></p>
                <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                    <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                    <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php else: ?>-->
                    <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
                <p class="value"><?php echo ($vo["points"]); ?></p>
            </div>
            <?php else: ?>
            <div class="rankingList">
                <p class="ranking"><?php echo ($key + 1); ?></p>
                <img src="/wltfagent/Public/home/images/ceshi.png">
            <span>
                <p class="name"><?php echo substr_replace($vo['tel'],'****',3,4);?></p>
                <!--<?php if($vo["rank_id"] == 1): ?>-->
                    <!--<p class="rank level1"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php elseif($vo["rank_id"] == 2): ?>-->
                    <!--<p class="rank level2"><?php echo ($vo["rank_name"]); ?></p>-->
                    <!--<?php else: ?>-->
                    <!--<p class="rank level3"><?php echo ($vo["rank_name"]); ?></p>-->
                <!--<?php endif; ?>-->
            </span>
                <p class="value"><?php echo ($vo["points"]); ?></p>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
</body>