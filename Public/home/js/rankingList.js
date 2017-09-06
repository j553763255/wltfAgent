/**
 * Created by ju on 2017/3/1.
 */
$(function () {
    $(".nav div").click(function () {
        var className = $(this).attr("id");
        $(".nav div").removeClass("active");
        $(".rankPanel").hide();
        $(this).addClass("active");
        $("." + className).show();
    });
});