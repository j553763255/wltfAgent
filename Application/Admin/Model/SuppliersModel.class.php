<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/12 0012
 * Time: 18:45
 */
namespace Admin\Model;
use Think\Model;

class SuppliersModel extends Model
{
    protected $pk = "id";
    protected $tableName = 'daili_suppliers';

    /**
     * 获取合作企业
     * @return bool|mixed
     */
    public function getMessage($status)
    {
        $map['status'] = $status;
        $result = $this->where($map)->select();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 获取多条信息
     * @param $condition
     * @return bool|mixed
     */
    public function getMessages($condition)
    {
        $result = $this->where($condition)->select();
        if ($result) {
	    if(!empty($result)){
                foreach($result as $key=>$value){
                    $result[$key]['real_name'] = D('user')->where(array('id'=>$value['apply_id']))->getField('real_name');
                }
            }	
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 更新信息
     * @param $condition
     * @param $data
     * @return bool
     */
    public function updateMessage($condition, $data)
    {
        $result = $this->where($condition)->save($data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}