<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 18:01
 */
namespace Home\Model;
use Think\Model;

class ConfigSettingModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_config_setting';

    /**
     * @return bool|mixed
     */
    public function getTime()
    {
        $times = $this->where(array("status"=>1))->select();
        if (empty($times)){
            return false;
        }else{
            return $times;
        }
    }
}