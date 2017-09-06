<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 13:46
 */
namespace Home\Model;
use Think\Model;

class RatioModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_ratio';

    public function getGerm(){
        $term = $this->where("id = 3")->find();
        return $term['value'] * 12;
    }
}