/**
 * Created by ju on 2017/3/12.
 */
$(".notRegister").click(function () {
    console.log(1);
    layer.open({
        content:"兑换中心才可使用此功能，请将注册人信息发送给你所属的兑换中心注册！",
        skin:"msg",
        time:3
    })
});