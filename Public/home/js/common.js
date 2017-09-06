/*
*自适应各种手机屏幕设定rem
* 该版本已iphone6为基准设计font-size=100px的rem
*/
(function (doc, win) {
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) return;
            if(clientWidth>=640){
                docEl.style.fontSize = '100px';
            }else{
                docEl.style.fontSize = 100 * (clientWidth / 375) + 'px';
            }
        };

    if (!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);
/**提示信息**/
function boxMsg(msg, url, timeout, callback) { //信息,跳转地址,时间
    layer.msg(msg);
    if (url) {
        setTimeout(function () {
            window.location.href = url;
        }, timeout ? timeout : 3000);
    } else if (url === 0) {
        setTimeout(function () {
            location.reload(true);
        }, timeout ? timeout : 3000);
    } else {
        eval(callback);
    }
}
/**正则判断**/
function judgeReg(val){
    var space = /^[^\s]{6,16}$/;//含有空格换行等
    var is_num = /^\d+$/g;//纯数字
    var is_chinese = /[\u4e00-\u9fa5]/;//含有中文
    var result1= space.test(val);//match 是匹配的意思   用正则表达式来匹配
    var result2= is_num.test(val);//match 是匹配的意思   用正则表达式来匹配
    var result3= is_chinese.test(val);//match 是匹配的意思   用正则表达式来匹配
    if(!result1 || !result2 || result3){
        return true;
    }else{
        return false;
    }
}
/**功能未开放提示**/
function mobileBoxMsg(msg, url, timeout, callback) {
    layer.open({
        content: msg
        ,skin: 'msg'
        ,time: 2 //2秒后自动关闭
    });
    if (url) {
        setTimeout(function () {
            window.location.href = url;
        }, timeout ? timeout : 3000);
    } else if (url === 0) {
        setTimeout(function () {
            location.reload(true);
        }, timeout ? timeout : 3000);
    } else {
        eval(callback);
    }
}
/**带按钮提示**/
function mobileBoxInfo(msg, url, timeout, callback) {
    layer.open({
        content: msg,
        btn: ['我知道了'],
        shadeClose:false,
        yes:function () {
            if (url) {
                window.location.href = url;
            } else if (url === 0) {
                    location.reload(true);
            } else {
                layer.closeAll();
            }
        }
    });

}
/**loading层**/
function loadingBox(msg) {
    layer.open({
        type: 2
        ,content: msg
        ,shadeClose: false
    })
}
/**返回上一页**/
function back(){
    javascript :history.back(-1);
    return false;
}

/**正则判断**/
function judgeReg(val){
    var space = /^[^\s]{6,16}$/;//含有空格换行等
    var is_num = /^\d+$/g;//纯数字
    var is_chinese = /[\u4e00-\u9fa5]/;//含有中文
    var result1= space.test(val);//match 是匹配的意思   用正则表达式来匹配
    var result2= is_num.test(val);//match 是匹配的意思   用正则表达式来匹配
    var result3= is_chinese.test(val);//match 是匹配的意思   用正则表达式来匹配
    if(!result1 || !result2 || result3){
        return true;
    }else{
        return false;
    }
}

//银行卡号校验
//Description:  银行卡号Luhm校验
//Luhm校验规则：16位银行卡号（19位通用）:
// 1.将未带校验位的 15（或18）位卡号从右依次编号 1 到 15（18），位于奇数位号上的数字乘以 2。
// 2.将奇位乘积的个十位全部相加，再加上所有偶数位上的数字。
// 3.将加法和加上校验位能被 10 整除。
function luhmCheck(bankno){
    if (bankno.length < 16 || bankno.length > 19) {
        //$("#banknoInfo").html("银行卡号长度必须在16到19之间");
        return false;
    }
    var num = /^\d*$/;  //全数字
    if (!num.exec(bankno)) {
        //$("#banknoInfo").html("银行卡号必须全为数字");
        return false;
    }
    //开头6位
    var strBin="10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99";
    if (strBin.indexOf(bankno.substring(0, 2))== -1) {
        //$("#banknoInfo").html("银行卡号开头6位不符合规范");
        return false;
    }
    var lastNum=bankno.substr(bankno.length-1,1);//取出最后一位（与luhm进行比较）

    var first15Num=bankno.substr(0,bankno.length-1);//前15或18位
    var newArr=new Array();
    for(var i=first15Num.length-1;i>-1;i--){    //前15或18位倒序存进数组
        newArr.push(first15Num.substr(i,1));
    }
    var arrJiShu=new Array();  //奇数位*2的积 <9
    var arrJiShu2=new Array(); //奇数位*2的积 >9

    var arrOuShu=new Array();  //偶数位数组
    for(var j=0;j<newArr.length;j++){
        if((j+1)%2==1){//奇数位
            if(parseInt(newArr[j])*2<9)
                arrJiShu.push(parseInt(newArr[j])*2);
            else
                arrJiShu2.push(parseInt(newArr[j])*2);
        }
        else //偶数位
            arrOuShu.push(newArr[j]);
    }

    var jishu_child1=new Array();//奇数位*2 >9 的分割之后的数组个位数
    var jishu_child2=new Array();//奇数位*2 >9 的分割之后的数组十位数
    for(var h=0;h<arrJiShu2.length;h++){
        jishu_child1.push(parseInt(arrJiShu2[h])%10);
        jishu_child2.push(parseInt(arrJiShu2[h])/10);
    }

    var sumJiShu=0; //奇数位*2 < 9 的数组之和
    var sumOuShu=0; //偶数位数组之和
    var sumJiShuChild1=0; //奇数位*2 >9 的分割之后的数组个位数之和
    var sumJiShuChild2=0; //奇数位*2 >9 的分割之后的数组十位数之和
    var sumTotal=0;
    for(var m=0;m<arrJiShu.length;m++){
        sumJiShu=sumJiShu+parseInt(arrJiShu[m]);
    }

    for(var n=0;n<arrOuShu.length;n++){
        sumOuShu=sumOuShu+parseInt(arrOuShu[n]);
    }

    for(var p=0;p<jishu_child1.length;p++){
        sumJiShuChild1=sumJiShuChild1+parseInt(jishu_child1[p]);
        sumJiShuChild2=sumJiShuChild2+parseInt(jishu_child2[p]);
    }
    //计算总和
    sumTotal=parseInt(sumJiShu)+parseInt(sumOuShu)+parseInt(sumJiShuChild1)+parseInt(sumJiShuChild2);

    //计算Luhm值
    var k= parseInt(sumTotal)%10==0?10:parseInt(sumTotal)%10;
    var luhm= 10-k;
    if(lastNum==luhm){
        return true;
    }
    else{
        return false;
    }
}
$("form").submit(function(){
    $(":submit",this).attr("disabled","disabled");
});