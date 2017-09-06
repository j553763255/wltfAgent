<?php
/**
 * 兑换中心参数设置模板
 * Created by PhpStorm.
 * User: ju
 * Date: 2017/2/27
 * Time: 11:12
 */
namespace Admin\Model;
use Think\Model;

class CompanyModel extends Model{
    protected $pk = "id";
    protected $tableName =  'daili_company_setting';

    /**
     * 获取单条信息
     * @param $condition
     * @return bool|mixed
     */
    public function getMessage($condition){
        $result = $this->where($condition)->find();
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * 获取多条信息
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

    /**
     * 更新信息
     * @param $condition
     * @param $data
     * @return bool
     */
    public function updateMessage($condition,$data){
        $result = $this->where($condition)->save($data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function getRatio()
    {
        $companyObj = $this->select();
        foreach ($companyObj as $k => $item) {
            if ($item['id'] == 1){
                $companyObj[$k]['rank_name'] = "一级兑换中心";
            }elseif($item['id'] == 2){
                $companyObj[$k]['rank_name'] = "二级兑换中心";
            }else{
                $companyObj[$k]['rank_name'] = "三级兑换中心";
            }
        }
        return $companyObj;
    }

    public function modifyRatio($data)
    {
        $saveData['achievement']        = $data['achievement'];
        $saveData['service_fee_ratio']  = $data['service_fee_ratio'];
        $saveData['subsidy_ratio']      = $data['subsidy_ratio'];
        $bool = $this->where("id =" . $data['submit'])->save($saveData);
        return $bool;
    }
}