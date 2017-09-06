<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
use Think\Model;

class PointsFlowModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_points_flow';

    public function getFlow($type,$time,$uid)
    {
        if ($type == "expend"){ //判断为支付或者收入
            $maps['points'] = array("lt",0);
        }elseif($type == "income"){
            $maps['points'] = array("gt",0);
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
        $maps['user_id'] = $uid;
        $flow = $this->where($maps)->order('id desc')->select();
        $count = 0;
        $points = 0;
        foreach ($flow as $item) {
            $count += 1;
            $points += $item['points'];
        }
        $arr = array(
            "count" => $count,
            "points" => $points,
            "flow"  => $flow
        );
        return $arr;
    }

    public function getFlowById($id)
    {
        if (!empty($id)){
            $flowObj = $this->where("id =" . $id)->find();
        }else{
            $flowObj = null;
        }
        return $flowObj;
    }

    public function addFlow($uid,$points,$remarks)
    {
        $data['user_id'] = $uid;
        $data['points'] = $points;
        $data['remarks'] = $remarks;
        $data['create_time'] = time();
        $addFlow = $this->add($data);
        if ($addFlow == true){
            return true;
        }else{
            return false;
        }
    }
}