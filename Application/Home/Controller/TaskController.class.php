<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 14:28
 */
namespace Home\Controller;
use Think\Controller;

session_start();
class TaskController extends CommonController{

    /**任务列表**/
    public function taskList(){
        $this->display();
    }
}