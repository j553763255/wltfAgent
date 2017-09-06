<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10 0010
 * Time: 10:24
 */
namespace Home\Model;
use Think\Model;

class TransferModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_transfer_log";

    /**
     * @param $uid
     * @return bool|mixed
     * 根据 pay_id 从 daili_transfer_log 表中查找信息
     */
    public function getTransfer($uid)
    {
        $TransferObj = $this->where("pay_id =" . $uid)->order('create_time desc')->select();
        if (empty($TransferObj)){
            return false;
        }else{
            return $TransferObj;
        }
    }

    /**
     * 记录流水
     * @param $pay_id
     * @param $rec_id
     * @param $money
     * @param $fee
     * @return bool
     */
    public function addTransfer($pay_id,$rec_id,$money,$fee){
        $data['pay_id'] = $pay_id;
        $data['rec_id'] = $rec_id;
        $data['money'] = $money;
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
     * 根据pay_id获取转账信息
     * @param $uid
     * @return bool
     */
    public function getTransferPay($uid){
        $where_transfer['pay_id'] = $uid;
        $TransferObj = $this->where($where_transfer)->order('create_time desc')->select();
        if (empty($TransferObj)){
            return false;
        }else{
            return $TransferObj;
        }
    }

    /**
     * 根据rec_id获取转账信息
     * @param $uid
     * @return bool
     */
    public function getTransferRec($uid){
        $where_transfer['rec_id'] = $uid;
        $TransferObj = $this->where($where_transfer)->order('create_time desc')->select();
        if (empty($TransferObj)){
            return false;
        }else{
            return $TransferObj;
        }
    }

    /**
     * 根据pay_id或者rec_id获取转账信息
     * @param $uid
     * @return bool
     */
    public function getTsfer($uid){
        $where_transfer['rec_id'] = $uid;
        $where_transfer['pay_id'] = $uid;
        $where_transfer['_logic'] = 'OR';
        $TransferObj = $this->where($where_transfer)->order('create_time desc')->select();
        if (empty($TransferObj)){
            return false;
        }else{
	foreach($TransferObj as $key=>$val){
		if($val['status'] != 2){
			unset($TransferObj[$key]);
		}
	}
            return $TransferObj;
        }
    }

    /**
     * @return bool
     */
    public function changeStatus($statu)
    {
        $where['status'] = array('neq',1);
        $where['create_time'] = time();
        $status = $this->where($where)->setField("status",$statu);
        if ($status){
            return true;
        }else{
            return false;
        }
    }

}