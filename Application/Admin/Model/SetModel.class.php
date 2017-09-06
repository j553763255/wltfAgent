<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/27
 * Time: 16:56
 */
namespace Admin\Model;
use Think\Model;
class SetModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_config_setting";

    /**
     * 更新数据
     * @param $condition
     * @param $data
     * @return bool
     */
    public function updateMessage($condition,$data){
        $result = $this->where($condition)->save($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取提现时间设置状态
     * @return bool|mixed
     */
    public function getStatus(){
        $result = $this->select();
        if($result){
            if(empty($result)){
                $status['free'] = 2;
                $status['fixed'] = 2;
            }else{
                foreach($result as $value){
                    if($value['id'] == 1){
                        if($value['status'] == 1){
                            $status['free'] = 1;
                            $status['start_time'] = $value['value'];
                        }else{
                            $status['free'] = 2;
                            $status['start_time'] = $value['value'];
                        }
                    }
                    if($value['id'] == 2){
                        if($value['status'] == 1){
                            $status['free'] = 1;
                            $status['end_time'] = $value['value'];
                        }else{
                            $status['free'] = 2;
                            $status['end_time'] = $value['value'];
                        }
                    }
                    if($value['id'] == 3){
                        if($value['status'] == 1){
                            $status['fixed'] = 1;
                            $status['week'] = $value['value'];
                        }else{
                            $status['fixed'] = 2;
                            $status['week'] = $value['value'];
                        }
                    }
                }
            }
            return $status;
        }else{
            return false;
        }
    }
}