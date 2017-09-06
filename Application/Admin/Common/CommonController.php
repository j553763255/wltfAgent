<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function __construct()
    {
       //加载被覆盖的基类构造器
        parent::__construct();
        // 验证用户是否登录
        if (!session('loginkey'))
        {
            return $this->error('请先登录!', url('admin/index/login'));
        }
    }
}