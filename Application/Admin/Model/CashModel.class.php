<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/27
 * Time: 13:35
 */
namespace Admin\Model;
use Think\Model;
class CashModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_cash_log";

    /**
     * 获取所有的信息
     * @return bool|mixed
     */
    public function getInfo(){
        $result = $this->select();
        if($result){
            if(!empty($result)){
                foreach($result as $value){
                    $user_ids[] = $value['user_id'];
                }
                $user_names = D('User')->field('id,user_name,tel,real_name')->where(array('id'=>array('in',$user_ids)))->select();
                foreach($result as $key=>$val){
                    foreach($user_names as $item){
                        if($item['id'] == $val['user_id']){
                            $result[$key]['user_name'] = $item['user_name'];
                            $result[$key]['real_name'] = $item['real_name'];
                            $result[$key]['tel'] = $item['tel'];
                        }
                    }
                }
            }
            return $result;
        }else{
            return false;
        }
    }

    /**
     * 根据条件获取相应的信息（多条）
     * @param $condition
     * @return bool|mixed
     */
    public function getMessages($condition){
        $result = $this->where($condition)->select();
        if($result){
            if(!empty($result)){
                foreach($result as $value){
                    $user_ids[] = $value['user_id'];
                }
                $user_names = D('User')->field('id,user_name,tel,real_name')->where(array('id'=>array('in',$user_ids)))->select();
                foreach($result as $key=>$val){
                    foreach($user_names as $item){
                        if($item['id'] == $val['user_id']){
                            $result[$key]['user_name'] = $item['user_name'];
                            $result[$key]['real_name'] = $item['real_name'];
                            $result[$key]['tel'] = $item['tel'];
                        }
                    }
                }
            }
            return $result;
        }else{
            return false;
        }
    }

    public function updateMessage($condition,$data){
        $result = $this->where($condition)->save($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }

}