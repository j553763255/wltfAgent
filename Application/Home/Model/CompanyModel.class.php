<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 17:35
 */
namespace Home\Model;
use Think\Model;

class CompanyModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_company";
    public $classOne;
    public $classTwo;
    public $allMessages;

    public function addCompany($data)
    {
        
    }
    /**
     * 获取兑换中心信息（单条）
     * @param $condition
     * @return bool|mixed
     */
    public function getMessage($condition){
        $result = $this->where($condition)->find();
        //获取用户名
        if($result) {
            $user_name = M('daili_user')->where(array('id' => $result['user_id']))->getField('user_name');
            $result['user_name'] = $user_name;
            return $result;
        }else{
            return false;
        }
    }

    /**
     * 获取兑换中心信息
     * @param $maps
     * @return bool|mixed
     */
    public function getCompanyMsg($maps)
    {
        $company = $this->where($maps)->find();
        if ($company){
            return $company;
        }else{
            return false;
        }
    }

    /**
     * 重新提交成立兑换中心申请
     * @param $id
     * @return bool
     */
    public function reapply($id)
    {
        $reapplyResult = $this->where("apply_id =" . $id)->setField("status",1);
        if ($reapplyResult){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 通过兑换中心标识获取用户ID
     * @param $service_number
     * @return mixed
     */
    public function getUserId($service_number){
        $map['id'] = $service_number;
        $result = $this->field('apply_id,company_class,superior_number')->where($map)->find();
        return $result;
    }
    /**
     * 获取直属一级服务中心
     * @param $superior_number 上级兑换中心ID（service_number）
     * @return mixed
     */
    public function getClassOne($superior_number){
        if($superior_number != 0){
            $service_message = $this->where(array('id'=>$superior_number))->find();
            if(!empty($service_message)){
                if($service_message['company_class'] == 1){
                    $this->classOne = $service_message;
                }else{
                    $this->getClassOne($service_message['superior_number']);
                }
            }
        }else{
            return false;
        }
    }
    /**
     * 获取直属二级服务中心
     * @param $superior_number
     * @return mixed
     */
    public function getClassTwo($superior_number){
        if($superior_number != 0){
            $service_message = $this->where(array('id'=>$superior_number))->find();
            if(!empty($service_message)){
                if($service_message['company_class'] < 3){//1.一级兑换中心 2.二级兑换中心
                    if($service_message['company_class'] == 2){
                        $higherService['second'] = $service_message;
                        //查找一级兑换中心
                        $this->getClassOne($service_message['superior_number']);
                        $first_service = $this->classOne;
                        if($first_service){
                            $higherService['first'] = $first_service;
                        }else{
                            $higherService['first'] = "";
                        }
                        $this->classTwo = $higherService;
                    }else{
                        $higherService['second'] = "";
                        $higherService['first'] = $service_message;
                    }
                }else{
                    $this->getClassTwo($service_message['superior_number']);
                }
            }else{
                return false;
            }
        }else{
            echo false;
        }
    }
    /**
     * 兑换中心收取相应得服务奖励（一级）
     * @param $money
     * @return mixed
     */
    public function oneClassServiceCharge($money){
        //一级兑换中心收取服务费比例
        $service_ratio = D('Rank')->getServiceRatio();
        //兑换中心收取的服务费
        $service_fee = $money * $service_ratio['one']/100;
        return $service_fee;
    }
    /**
     * 兑换中心收取相应得服务奖励（二级）
     * @param $money
     * @return mixed
     */
    public function twoClassServiceCharge($money){
        //兑换中心收取服务费比例
        $service_ratio = D('Rank')->getServiceRatio();
        //二级兑换中心收取的服务费
        $service_fee['two'] = $money * $service_ratio['two']/100;
        //上级兑换中心（一级兑换中心）收取服务奖励
        $new_ratio = $service_ratio['one'] - $service_ratio['two'];
        if($new_ratio < 0){//安全处理
            $new_ratio = 0;
        }
        $service_fee['one'] = $money * $new_ratio/100;
        return $service_fee;
    }
    /**
     * 换中心收取相应得服务奖励（三级）
     * @param $money
     * @param $type
     * @return mixed
     */
    public function threeClassServiceCharge($money,$type){
        //兑换中心收取服务费比例
        $service_ratio = D('Rank')->getServiceRatio();
        //三级兑换中心收取的服务费
        $service_fee['three'] = $money * $service_ratio['three']/100;
        if($type == 1){//有直属二级兑换中心，也有直属一级兑换中心
            //上级兑换中心（二级兑换中心）收取服务奖励
            $new_ratio = $service_ratio['two'] - $service_ratio['three'];
            if($new_ratio < 0){//安全处理
                $new_ratio = 0;
            }
            $service_fee['two'] = $money * $new_ratio/100;
            //上上级兑换中心（一级兑换中心）收取服务奖励
            $last_ratio = $service_ratio['one'] - $service_ratio['two'];
            if($last_ratio <0){
                $last_ratio = 0;
            }
            $service_fee['one'] = $money * $last_ratio/100;
        }
        if($type == 2){//只有一级兑换中心
            $new_ratio = $service_ratio['one'] - $service_ratio['three'];
            if($new_ratio < 0){//安全处理
                $new_ratio = 0;
            }
            $service_fee['one'] = $money * $new_ratio/100;
            $service_fee['two'] = 0;

        }
        if($type == 3){//只有二级兑换中心
            $new_ratio = $service_ratio['two'] - $service_ratio['three'];
            if($new_ratio < 0){//安全处理
                $new_ratio = 0;
            }
            $service_fee['two'] = $money * $new_ratio/100;
            $service_fee['one'] = 0;
        }
        return $service_fee;
    }

    /**
     * 新版加盟商系统中所有的上级兑换中心
     * @param $service_number
     */
    public function serviceCharge($service_number){
        $map['status'] = 2;
        $map['id'] = $service_number;
        $result = $this->field('id,apply_id,company_class,superior_number,service_ratio')->where($map)->find();
        $this->allMessages[] = $result;
        if($result['superior_number'] != 1){
            $this->serviceCharge($result['superior_number']);
        }
    }
}