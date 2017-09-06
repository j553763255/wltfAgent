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
}