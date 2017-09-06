<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/16
 * Time: 15:04
 */
namespace Admin\Model;
use Think\Model;

class CashLogModel extends Model{
    protected $pk = 'cash_id';
    protected $tableName = 'daili_cash_log';

    /**
     * 添加提现申请
     * @param $data
     * @return bool
     */
    public function addApplication($data){
        $result = $this->add($data);
        if($result){
            return true;
        }
    }

    /**
     * 根据公司id获取下级的提现申请详情
     * @param $conditions
     * @return mixed
     */
    public function getCashInfo($conditions){
        $result = $this->where($conditions)->order("cash_id desc")->select();
        if(!empty($result)) {
            foreach ($result as $v) {
                $admin_ids[] = $v['admin_id'];
            };
            //获取员工的信息
            $names = D('Admin')->getInfoByIds($admin_ids);
            foreach ($result as $key => $v) {
                foreach ($names as $val) {
                    if ($val['admin_id'] == $v['admin_id']) {
                        $result[$key]['admin'] = $val['admin'];
                        $result[$key]['position'] = $val['position'];
                    }
                }
            };
        }
        return $result;
    }

    /**
     * 根据用户id条件查询结果
     * @param $conditions
     * @return mixed
     */
    public function getMessage($conditions){
        $messages = $this->where($conditions)->select();
        $map['admin_id'] = $conditions['admin_id'];
        $name = D('Admin')->where($map)->find();
        $condition['position_id'] = $name['position_id'];
        $position = D('Position')->where($condition)->find();
        foreach ($messages as $key=>$v){
            $messages[$key]['admin'] = $name['admin'];
            $messages[$key]['position'] = $position['position_name'];
        }
        return $messages;
    }
    /**
     * 更新提现申请
     * @param $conditions
     * @param $data
     * @return bool
     */
    public function updateApplicate($conditions,$data){
        $result = $this->where($conditions)->save($data);
        if($result > 0){
            return $result = "success";
        }else{
            return $result = "false";
        }
    }
}