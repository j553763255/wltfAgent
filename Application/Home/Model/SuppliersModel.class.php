<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
use Think\Model;

session_start();
class SuppliersModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_Suppliers';

    public function addSuppliers($data)
    {
        $datas['user_id'] = $_SESSION['uid'];
        $datas['suppliers_name'] = $data['name'];
        $datas['suppliers_blurb'] = $data['explain'];
        $datas['suppliers_type'] = 1;
        $datas['registered'] = $data['registered'];
        $datas['create_time'] = time();
        $datas['province_id'] = $data['province'];
        $datas['city_id'] = $data['city'];
        $datas['area_id'] = $data['area'];
        $datas['address'] = $data['address'];
        $datas['tel'] = $data['mobile'];
        $datas['status'] = 1;
        if ($this->add($datas)){
            return true;
        }else{
            return false;
        }
    }
}