/**
 * Created by ju on 2017/3/6.
 */
$(function () {
    $(".tab-head a").click(function () {
        var className = $(this).attr("href");
        $(".nav-body .tab-body").hide();
        $(className).show();
    });
});
