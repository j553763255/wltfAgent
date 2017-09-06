<?php
namespace Admin\Controller;
use Think\Controller;

class CommondController extends Controller
{
    public function __construct()
    {
        //加载被覆盖的基类构造器
        parent::__construct();
        // 验证用户是否登录
        if (!session('loginkey')) {
            $this->error('请先登录!', U('admin/login/index'));
        }
    }
}