<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 21:45
 */
namespace Home\Controller;
use Think\Controller;

session_start();
class InformationController extends CommonController{

    /**消息首页**/
    public function index(){

        $this->display();
    }

    /**系统信息**/
    public function systemNews(){
        $notice = D('notice');
        $map['type'] = 1;
        $result = $notice->getMessages($map);
        $this->assign('list',$result);
        $this->display();
    }

    /**中心信息**/
    public function normalNews(){

        $this->display();
    }
}