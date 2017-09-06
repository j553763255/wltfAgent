//admin.js

$(function () {
    $(".dialogs").click(function () {
        var url=$(this).val();
        $(".geturl").attr("href",url);
    });
    // $("#navbar1 ul li a").click(function () {
    //     $(".sidebar").html(
    //         "<ul class='list-unstyle' id='Two-level-menu'>" +
    //         "<li class='two-li'>" +
    //         "<div>二级菜单1 <span class='icon-angle-down' style='margin-left: 40px'></span></div>" +
    //         "<ul class='list-unstyle'>" +
    //         "<li><a>三级菜单1</a> </li>" +
    //         "</ul>" +
    //         "</li>" +
    //         "</ul>"
    //     )
    // });
    $("#Two-level-menu .two-li div").click(function () {
        if ($(this).next("ul").css("display") == "none"){
            $(this).children("span").addClass("icon-angle-up");
            $(this).children("span").removeClass("icon-angle-down");
            $(this).next("ul").animate({height: 'toggle', opacity: 'toggle'}, "slow");
        }else {
            $(this).children("span").removeClass("icon-angle-up");
            $(this).children("span").addClass("icon-angle-down");
            $(this).next("ul").animate({height: 'toggle', opacity: 'toggle'}, "slow");
        }
    })
    $("#Two-level-menu .two-li ul a li").click(function () {
        $("#Two-level-menu .two-li ul a li").css("background","#FF7F00");
        $(this).css("background","#FF8E00");
    })
});