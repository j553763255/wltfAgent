<?php if (!defined('THINK_PATH')) exit();?><title>企业认证</title>
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
    <span class="header-title">成为中心</span>
    <a href="<?php echo U('home/support/index');?>"><span><img src="/wltfagent/Public/home/images/1_02.png"/></span></a>
</div>
<form method="post" enctype="multipart/form-data" action="<?php echo U('home/support/upload');?>" target="niu-frame">
<div class="first-step">
    <div class="label">以下信息用于核实您的身份信息，不会对外展示。</div>
    <div class="file-upload">
        <div class="file-title">身份证照</div>
        <div class="file-info">
            <div class="img-upload">
                <img src="/wltfagent/Public/home/images/3_13.png" id="img-card"/>
            </div>
            <div class="operate-upload">
                <p class="right-word">需要清晰展示五官和文字信息不可自拍，不可只拍身份证</p>
                <p class="right-button">
                    <input type="file" name='photo[]' id="file0"/>
                    <input type="text" name="" class="btn-select" value="上传"/>
                </p>
            </div>
        </div>
    </div>
    <div class="file-upload">
        <div class="file-title">营业执照</div>
        <div class="file-info">
            <div class="img-upload">
                <img src="/wltfagent/Public/home/images/3_19.png" id="img-cards"/>
            </div>
            <div class="operate-upload">
                <p class="right-word">需文字清晰、边框完整、露出国徽</p>
                <p class="right-button">
                    <input type="file" name="photo[]" id="file1"/>
                    <input type="text" name="" class="btn-select" value="上传"/>
                </p>
            </div>
        </div>
    </div>
    <div class="check-step">下一步</div>
</div>
<div class="next-step" style="display: none">
    <div class="form">
        <div class="form-input real_name">
            <span>姓名：</span>
            <input name="real_name" type="text" value="<?php echo ($user_message["real_name"]); ?>" readonly>
            <input type="hidden" name="higher_service" value="<?php echo ($user_message["service_number"]); ?>" />
        </div>
        <div class="form-input mobile">
            <span>电话：</span>
            <input name="mobile" type="text" value="<?php echo ($user_message["tel"]); ?>" readonly />
        </div>
        <div class="form-input card_id">
            <span>身份证号：</span>
            <input name="card_id" type="text" value="<?php echo ($user_message["card_id"]); ?>" readonly />
        </div>
        <div class="form-input addr">
            <span>地址：</span>
            <input name="addr" type="text" value="<?php echo ($user_message["addr"]); ?>" readonly />
        </div>
        <div class="form-input address">
            <span>详细地址：</span>
            <input name="address" type="text" value="<?php echo ($user_message["address"]); ?>" readonly />
        </div>
        <div class="form-input rank">
            <span>加盟商级别：</span>
            <input name="rank_name" type="text" value="<?php echo ($user_message["rank_name"]); ?>" readonly />
            <input name="rank" type="hidden" value="<?php echo ($user_message["rank_id"]); ?>" />
            <input name="service_number" type="hidden" value="<?php echo ($user_message["service_number"]); ?>"/>
            <input name="service_ratio" type="hidden" value="<?php echo ($user_message["service_ratio"]); ?>" />
        </div>
    </div>
    <div class="button-refer"><input type="submit" value="提交申请" /></div>
</div>
</form>
</body>
<iframe name="niu-frame" style="display:none"></iframe>
<script>
    $("#file0").change(function(){
        // getObjectURL是自定义的函数，见下面
        // this.files[0]代表的是选择的文件资源的第一个，因为上面写了 multiple="multiple" 就表示上传文件可能不止一个
        // ，但是这里只读取第一个
        var objUrl = getObjectURL(this.files[0]) ;
        if (objUrl) {
            // 在这里修改图片的地址属性
            $("#img-card").attr("src", objUrl) ;
        }
    }) ;
    $("#file1").change(function(){
        // getObjectURL是自定义的函数，见下面
        // this.files[0]代表的是选择的文件资源的第一个，因为上面写了 multiple="multiple" 就表示上传文件可能不止一个
        // ，但是这里只读取第一个
        var objUrl = getObjectURL(this.files[0]) ;
        if (objUrl) {
            // 在这里修改图片的地址属性
            $("#img-cards").attr("src", objUrl) ;
        }
    }) ;
    $('.check-step').on('click',function(){
        $('.first-step').css('display','none');
        $('.next-step').css('display','block');
    });
    //建立一個可存取到該file的url
    function getObjectURL(file) {
        var url = null ;
        // 下面函数执行的效果是一样的，只是需要针对不同的浏览器执行不同的 js 函数而已
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>
</html>