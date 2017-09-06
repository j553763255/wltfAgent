<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
use Think\Model;

class RechargeLogModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_recharge_log';

    public function addRechargeLog($uid,$money,$type,$status)
    {
        $data = array(
            "user_id" => $uid,
            "money" => $money,
            "type" => $type,
            "create_time" => time(),
            "create_ip" => getIP(),
            "status" => $status
        );
        $this->add($data);
    }
}