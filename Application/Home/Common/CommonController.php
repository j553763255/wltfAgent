<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function __construct()
    {
        $_SESSION = 1;
       //加载被覆盖的基类构造器
        parent::__construct();
        // 验证用户是否登录
      //  if (!session('login_key'))
      //  {
//            return $this->error('请先登录!', U('home/login/index'));
      //  }
    }
}