<?php
/**
 * 拨付模板
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 16:21
 */
namespace Admin\Model;
use Think\Model;
class GrantModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_appropriation_log";

    /**
     * 获取所有的信息
     * @return bool|mixed
     */
    public function getInfo(){
        $result = $this->select();
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * 获取多条数据
     * @param $condition
     * @return bool|mixed
     */
    public function getMessages($condition){
        $result = $this->where($condition)->select();
        if($result){
            return $result;
        }else{
            return false;
        }
    }

}