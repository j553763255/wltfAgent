<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 19:27
 */
namespace Home\Model;
use Think\Model;

class TaskModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_task";

    public function getMessage($condition){
        $result = $this->where($condition)->select();
    }
}