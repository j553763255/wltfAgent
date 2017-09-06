/**
 * Created by ju on 2017/3/1.
 */
$(function(){
    $(".detailBanner div").click(function () {
        var child_class = $(this).attr("id");
        $(".detailBanner div").removeClass("action");
        $(".detailBody .hidden").hide();
        $(this).addClass("action");
        $("." + child_class).show();
    });
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    var nowDate = year + "-" + month;
    $(".time input").val(nowDate);
});
$(function () {
    $(".next").click(function () {
        var card1 = $("input[name = 'bank-card']").val();
        var card2 = $("input[name = 'bank-card2']").val();
        if (card1 == 0){
            layer.open({
                content: '请输入银行卡号！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            if (card2 == 0){
                layer.open({
                    content: '请确认银行卡号！'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }else{
                if (card1 == card2){
                    if(luhmCheck(card1)){
                        $(".input-card").hide();
                        $(".input-massage").show();
                    }else {
                        layer.open({
                            content: '请输入正确的银行卡号！'
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                }else{
                    layer.open({
                        content: '两次输入银行号不一致！'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        }

    });
    $(".goBank").click(function () {
        var display =$('.last-page').css('display');
        if (display == "none"){
            window.history.back();
            return false;
        }else{
            $(".first-page").show();
            $(".last-page").hide();
        }
    });
   
});
