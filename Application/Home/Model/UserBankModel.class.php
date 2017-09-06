<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
use Think\Model;

class UserBankModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_user_bank';

    public function addBank($data)
    {
        $bool = $this->add($data);
        return $bool;
    }

    public function bankMsg($uid)
    {
        $banks = $this->where("user_id = " . $uid)->select();
        return $banks;
    }

    /**
     * 获取默认银行卡
     * @param $uid
     * @return mixed|string
     */
    public function getDefaultBank($uid)
    {
        $getSql = $this->where(array("user_id"=>$uid,"status"=>1))->find();
        if (empty($getSql)){
            return false;
        }else{
            return $getSql;
        }
    }

    /**设置默认银行**/
    public function setDefaultBank($bank_id,$uid)
    {
        $initialize = $this->where(array("user_id"=>$uid,"status"=>1))->setField("status","0");
        $setResult = $this->where("id =" . $bank_id)->setField("status","1");
        if ($setResult){
            return true;
        }else{
            return false;
        }
    }

    /**删除银行**/
    public function delBank($bank_id)
    {
        $delResult = $this->where("id =" . $bank_id)->delete();
        return $delResult;
    }
}