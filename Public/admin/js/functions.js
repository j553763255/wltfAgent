/**
 * Created by ju on 2016/12/28.
 */

function transform(obj){
    var arr = [];
    for(var item in obj){
        arr.push(obj[item]);
    }
    return arr;
}
function getDays(year,mouth) {
//构造当前日期对象
    var date = new Date();

//获取年份
    if (year==null){
         year = date.getFullYear();
    }

//获取当前月份
    if (mouth==null){
         mouth = date.getMonth() + 1;
    }
//定义当月的天数；
    var days;

//当月份为二月时，根据闰年还是非闰年判断天数
    if (mouth == 2) {
        days = year % 4 == 0 ? 29 : 28;

    }
    else if (mouth == 1 || mouth == 3 || mouth == 5 || mouth == 7 || mouth == 8 || mouth == 10 || mouth == 12) {
        //月份为：1,3,5,7,8,10,12 时，为大月.则天数为31；
        days = 31;
    }
    else {
        //其他月份，天数为：30.
        days = 30;

    }

    //输出天数
    return days;
}