<?php
namespace Home\Controller;
use Think\Controller;
session_start();
class CommonController extends Controller{
    public function __construct()
    {
       //加载被覆盖的基类构造器
        parent::__construct();
        // 验证用户是否登录
       // if ($_SESSION['uid'] == 0)
       // {
       //     $this->error('请先登录!',U('home/login/index'));
       // }
    }

    protected function agentMsg($message, $jumpUrl = '', $time = 3000){
        $str = '<script>';
        $str .= 'parent.mobileBoxMsg("' . $message . '","' . $jumpUrl . '","' . $time . '");';
        $str .= '</script>';
        die($str);
    }
    protected function agentInfo($message, $jumpUrl = '', $time = 3000){
        $str = '<script>';
        $str .= 'parent.mobileBoxInfo("' . $message . '","' . $jumpUrl . '","' . $time . '");';
        $str .= '</script>';
        die($str);
    }
    protected function loadingBox($message){
        $str = '<script>';
        $str .= 'parent.loadingBox("' . $message . '");';
        $str .= '</script>';
        echo($str);
    }
}