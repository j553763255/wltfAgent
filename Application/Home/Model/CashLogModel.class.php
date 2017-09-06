<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
use Think\Model;

class CashLogModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_cash_log';

    /**添加提现记录**/
    public function addCashLog($uid,$bank,$money,$fee)
    {
        $data['user_id'] = $uid;
        $data['money'] = $money;
        $data['create_time'] = time();
        $data['cash_ip'] = getIP();
        $data['card'] = $bank['bank_card'];
        $data['card_holder'] = $bank['holder_name'];
        $data['bank'] = $bank['bank_name'];
        $data['fee'] = $fee;
        $addSql = $this->add($data);
        if ($addSql){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取今日提现记录
     * @param $uid
     * @return bool
     */
    public function getCashLog($uid)
    {
        $maps['user_id'] = $uid;
        $maps['create_time'] = array("gt",strtotime(date('Y-m-d 00:00:00')));
        $log = $this->where($maps)->select();
        if (empty($log)){
            return false;
        }else{
            return true;
        }
    }
}