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
<script type="application/javascript" src="/wltfagent/Public/home/layer_mobile/layer.js"></script>
<link rel="stylesheet" href="/wltfagent/Public/home/css/register.css">
<script src="/wltfagent/Public/home/js/register.js"></script>
<body>
<div id="header">
    <a href="#" onClick="javascript :history.back(-1);return false;" class="fl-left">&lt;</a>
    添加企业
</div>
<div class="form">
    <form class="" action="<?php echo U('home/support/register');?>" method="post" target="niu-frame">
        <div class="form-input real_name">
            <span>企业名称：</span>
            <input name="name" type="text" placeholder="请填写企业全称">
        </div>
        <div class="form-input type">
            <span>企业类别：</span>
            <input name="type" type="text" placeholder="请填写企业类别">
        </div>
        <div class="form-input mobile">
            <span>联系方式：</span>
            <input name="mobile" type="number" placeholder="请填写对接企业的电话号码">
        </div>
        <div class="form-input explain">
            <span>企业说明：</span>
            <textarea name="explain" placeholder="请简单描述你所对接的企业。"></textarea>
        </div>
        <div class="form-input addr">
            <span>企业地址：</span>
            <select id="province" name="province">
                <option selected="selected" value="">-省-</option>
                <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select id="city" name="city">
                <option selected='selected'  value=''>-市-</option>
            </select>
            <select id="area" name="area">
                <option selected="selected"  value="">-区-</option>
            </select>
        </div>
        <div class="form-input address">
            <span>详细地址：</span>
            <input name="address" type="text" placeholder="请输入详细地址">
        </div>
        <div class="form-input registered">
            <span>注册资金：</span>
            <input name="registered" type="number" placeholder="请填写对接企业注册资金">
        </div>
        <div class="form-button">
            <button class="button" type="submit" onclick="loadingBox('用户注册中，请稍后。。。')">提交</button>
        </div>
    </form>
</div>
<script>
    $("#province").change(function () {
        $("#city").html("<option selected='selected'  value=''>请选择市</option>");
        var provinceId = $(this).val();
        $.ajax({
            type:"post",
            url:"<?php echo U('home/support/getCity');?>",
            dataType:"json",
            data:{"provinceId":provinceId},
            success:function (data) {
                for (var i=0; i < data.length; i++){
                    $("#city").append("<option value='" + data[i].code + "'>" + data[i].name +"</option>")
                }
            }
        })
    });
    $("#city").change(function () {
        $("#area").html("<option selected='selected'  value=''>请选择区</option>");
        var cityId = $(this).val();
        $.ajax({
            type:"post",
            url:"<?php echo U('home/support/getArea');?>",
            dataType:"json",
            data:{"cityId":cityId},
            success:function (data) {
                for (var i=0; i < data.length; i++){
                    $("#area").append("<option value='" + data[i].code + "'>" + data[i].name +"</option>")
                }
            }
        })
    });
    $(".referee").keyup(function () {
        var referee = $("input[name='referee']").val();
        if (referee.length == 11){
            $.ajax({
                type:"post",
                url:"<?php echo U('home/support/getReferee');?>",
                data:{"referee":referee},
                dataType:"json",
                success:function (data) {
                    if (data == null){
                        layer.open({
                            content:"未找到推荐人信息",
                            skin:"msg",
                            time:2
                        })
                    }else{
                        $("input[name='referee_id']").val(data.id);
                        layer.open({
                            content: "推荐人姓名：" + data.real_name
                            ,btn: '我知道了'
                        });
                    }
                }
            })
        }
    })
</script>
</body>
<iframe name="niu-frame" style="display: none;color:#ffffff;"></iframe>