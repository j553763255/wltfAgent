<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
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

    public function getProvinceName($id)
    {
        $map['code'] = $id;
        $province_name = $this->where($map)->find();
        return $province_name['name'];
    }
}