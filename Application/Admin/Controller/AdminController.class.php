<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
use Admin\Controller\CommonController;
class AdminController extends CommonController {
    public function index()
    {
        $users =D('bao_users');
        $nickname=$users->where(array('user_id'=>35))->find();
        $this->assign('nickname',$nickname);
        $this->display();
    }
}