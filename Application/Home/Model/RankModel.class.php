<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 15:42
 */
namespace Home\Model;
use Think\Model;
class RankModel extends Model
{
    protected $pk = "id";
    protected $tableName = "daili_rank";

    /**
     * 等级信息
     * @param $condition
     * @return bool|mixed
     */
//    public function getMessage($condition)
//    {
//        $result = $this->where($condition)->find();
//        if ($result) {
//            return $result;
//        } else {
//            return false;
//        }
//    }
/**
     * @return mixed
     */
    public function getAllRank()
    {
        $ranks = $this->select();
        return $ranks;
    }

    /**
     * @param $rank_id
     * @return mixed
     */
    public function getRankMassage($rank_id)
    {
        $rank = $this->where("rank =" . $rank_id)->find();
        return $rank;
    }

    /**
     * @param $condition
     * @return bool|mixed
     */
    public function getMessage($condition){
        $result = $this->where($condition)->find();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 提现手续费
     * @param $rank_id
     * @param $money
     * @return mixed
     */
    public function getTransferFee($rank_id,$money)
    {
        $feeRatio = $this->where("rank =". $rank_id)->setField("transfer_fee");
        $fee = $money * $feeRatio;
        return $fee;
    }
    /**
     * @param $rank
     * @return mixed
     */
    public function getRankNum($rank){
        $result = $this->where(array('rank'=>$rank))->getField('limit_people');
        return $result;
    }
    /**
     * 获取服务费比例
     * @return bool
     */
    public function getServiceRatio(){
        $result = $this->field('rank,service_ratio')->select();
        if(!empty($result) && $result){
            foreach($result as $value){
                if($value['rank'] == 1){
                    $service_ratio['one'] = $value['service_ratio'];
                }
                if($value['rank'] == 2){
                    $service_ratio['two'] = $value['service_ratio'];
                }
                if($value['rank'] == 3){
                    $service_ratio['three'] = $value['service_ratio'];
                }
            }
            return $service_ratio;
        }else{
            return false;
        }
    }
}