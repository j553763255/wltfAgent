<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Admin\Model;
use Think\Model;

class AddressModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_address';

    public function getProvince()
    {
        $provinces = $this->where("level = 1")->select();
        return $provinces;
    }

    public function getCity($code)
    {
        $citys = $this->where("parentId = ".$code)->select();
        return $citys;
    }
    public function getArea($code)
    {
        $area = $this->where("parentId = ".$code)->select();
        return $area;
    }

    /**
     * 通过ID获取名称
     * @param $id
     * @return mixed
     */
    public function getNameById($id){
        $name = $this->where(array('code'=>$id))->getField('name');
        return $name;
    }
}