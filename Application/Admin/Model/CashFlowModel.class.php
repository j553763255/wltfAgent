<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Admin\Model;
use Think\Model;

class CashFlowModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_cash_flow';

    /**
     * @param $type
     * @param $time
     * @param $uid
     * @return array
     */
    public function getFlow($type,$time,$uid)
    {
        if ($type == "expend"){ //判断为支付或者收入
            $maps['money'] = array("lt",0);
        }elseif($type == "income"){
            $maps['money'] = array("gt",0);
        }
        if (empty($time)){ //判断为本月或用户选定月份
            $thisMonth = date('m');
            $thisYear = date('Y');
            $startDay = $thisYear . '-' . $thisMonth . '-1';
            $b_time  = strtotime($startDay);
            $maps['create_time'] = array("gt",$b_time);
           
        }else{
            $startDay = $time . '-1';
            $endDay = $time . '-' . date('t', strtotime($startDay));
            $b_time  = strtotime($startDay);
            $e_time  = strtotime($endDay);
            $maps['create_time'] = array(array("gt",$b_time),array("lt",$e_time));
        }
        $maps['rec_id'] = $uid;
        $flow = $this->where($maps)->select();
        $count = 0;
        $money = 0;
        foreach ($flow as $item) {
            $count += 1;
            $money += $item['money'];
        }
        $arr = array(
            "count" => $count,
            "money" => $money,
            "flow"  => $flow
        );
        return $arr;
    }

    /**
     * @param $type
     * @return bool|mixed
     */
    public function getFlowByType($type)
    {
        if (!empty($type)){
            $maps['type'] = array("in",$type);
            $flows = $this->where($maps)->order("id desc")->select();
            if (empty($flows)){
                return false;
            }else{
                return $flows;
            }
        }else{
            return false;
        }
    }
    /**
     * @param $id
     * @return mixed|null
     */
    public function getFlowById($id)
    {
        if (!empty($id)){
            $flowObj = $this->where("id =" . $id)->find();
        }else{
            $flowObj = null;
        }
        return $flowObj;
    }
    /**
     * @return bool
     */
    public function changeStatus()
    {
        $where['status'] = array('neq',1);
        $status = $this->where($where)->setField("status",1);
        if ($status){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取收入信息
     * @param $rec_id
     * @return mixed|null
     */
    public function getFlowByRec($rec_id)
    {
        if (!empty($rec_id)){
            $flowObj = $this->where("rec_id =" . $rec_id)->select();
        }else{
            $flowObj = null;
        }
        return $flowObj;
    }

    /**
     * @param $uid
     * @return mixed|null
     */
    public function getTodayFlow($uid)
    {
        if (!empty($uid)){
            $startTime =  strtotime(date('Y-m-d'));
            $endTime = strtotime(date('Y-m-d',strtotime('+1 day')));
            $where['create_time'] = array(array("GT",$startTime),array("LT",$endTime));
            $where['rec_id'] = $uid;
            $flowObj = $this->where($where)->select();
        }else{
            $flowObj = null;
        }
        return $flowObj;
    }
    /**
     * 记录流水
     * @param $pay_id
     * @param $rec_id
     * @param $type
     * @param $money
     * @param $remarks
     * @param $fee
     * @return bool
     */
    public function addFlow($pay_id,$rec_id,$type,$money,$remarks,$fee)
    {
        $data['pay_id'] = $pay_id;
        $data['rec_id'] = $rec_id;
        $data['type'] = $type;
        $data['money'] = $money;
        $data['remarks'] = $remarks;
        $data['create_time'] = time();
        $data['fee'] = $fee;
        $addSql = $this->add($data);
        if ($addSql){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 每日奖励收入统计
     * @param $start_time
     * @param $end_time
     * @param $rec_id
     * @param $num 最后一条信息ID
     * @return array
     */
    public function income($start_time,$end_time,$rec_id,$num){
        $map['type'] = array('in',array(3,5,7,8));
        $map['create_time'] = array('gt',$start_time);
        $map['create_time'] = array('lt',$end_time);
        $map['rec_id'] = $rec_id;
        $result = $this->field('type,create_time,money')->where($map)->select();
        //列出最近十天的信息（倒序）
        FOR ($i = 0; $i <= 9; $i++) {
            $times = $end_time - $i*86400;
            $dayTime[$i]['start_time'] = $times;
            $dayTime[$i]['end_time'] = $times + 86399;
            $dayTime[$i]['date'] = date('m-d',$times);
        }
        if(!empty($result)){
            foreach($dayTime as $key=>$value){
                $income[$key]['amount'] = 0;
                $income[$key]['service'] = 0;
                $income[$key]['task'] = 0;
                $income[$key]['bonus'] = 0;
                $income[$key]['recommend'] = 0;
                foreach($result as $val){
                    if($val['create_time'] >= $value['start_time'] && $val['create_time'] <= $value['end_time']){
                        $income[$key]['amount'] += $val['money'];//总奖金
                        if($val['type'] == 3){
                            $income[$key]['bonus'] += $val['money'];//项目分红奖励
                        }
                        if($val['type'] == 5){
                            $income[$key]['task'] += $val['money'];//任务奖励
                        }
                        if($val['type'] == 7){
                            $income[$key]['service'] += $val['money'];//服务奖励
                        }
                        if($val['type'] == 8){
                            $income[$key]['recommend'] += $val['money'];//直推奖励
                        }
                    }
                }
                $income[$key]['date'] = $value['date'];
                $income[$key]['id'] = ($num+$key+1);
            }
            foreach($income as $key=>$val){
                if($val['amount'] == 0){
                    unset($income[$key]);
                }else{
                    $income_result[] = $income[$key];
                }
            }
            return $income_result;
        }else{
            return false;
        }
    }
}