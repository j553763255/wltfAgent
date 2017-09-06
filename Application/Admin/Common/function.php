<?php
/**
 * Created by PhpStorm.
 * User: ju
 * Date: 2017/1/6
 * Time: 10:07
 */

/**
 * 根据地址ID获取地址名称
 * @param $ids
 * @return bool|mixed
 */
function getDetails($ids){
    $map['code'] = array('in',$ids);
    $result = M('daili_address')->where($map)->select();
    if(!empty($result)){
        return $result;
    }else{
        return false;
    }

}
function addFlow($data){
    $cashFlow = D("CashFlow");
    $addFlow = $cashFlow->addFlow($data['pay_id'],$data['rec_id'],$data['type'],$data['money'],$data['remarks'],$data['fee']);
    if ($addFlow){
        return true;
    }else{
        return false;
    }
}
/**
 * 获取星期几信息
 * @param $time
 * @param int $i
 * @return string
 */
function getTimeWeek($time, $i = 0) {
    $week_array = array("一", "二", "三", "四", "五", "六", "日");
    $oneDay = 24 * 60 * 60;
    return $week = "周" . $week_array[date("w", $time + $oneDay * $i)];
}
//$time=time();
//echo getTimeWeek($tiem);

/**
 * 获取不重复随机数
 * @param int $begin
 * @param int $end
 * @param int $limit
 * @return array
 */
function NoRand($begin=0,$end=30000,$limit=20){
    $rand_array = range($begin, $end);
    shuffle($rand_array);//调用现成的数组随机排列函数
    return array_slice($rand_array, 0, $limit);//截取前$limit个
}