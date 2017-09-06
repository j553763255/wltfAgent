<?php
namespace Admin\controller;
use app\admin\model\Admin;
use Think\Controller;
use think\Model;
use Think\Verify;
class LoginController extends Controller
{
    //登录首页
    public function index()
    {
        $this->display('login');
    }

    public function verify()
    {
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->fontSize = 40;
        $Verify->length   = 4;
        $Verify->fontttf   = '2.ttf';
        $Verify->useNoise = false;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
    //验证
    public function login()
    {
        function deep_in_array($value, $array) {
            foreach($array as $item) {
                if(!is_array($item)) {
                    if ($item == $value) {
                        return true;
                    } else {
                        continue;
                    }
                }
                if(in_array($value, $item)) {
                    return true;
                } else if(deep_in_array($value, $item)) {
                    return true;
                }
            }
            return false;
        }
        session_start();
        $uname=$_POST['admin'];
        $upwd=md5($_POST['password']);
        $dladmin=D('daili_admin');
        $sql=$dladmin->field('admin')->select();
        $code = $_POST['passcode'];
        $Verify = new \Think\Verify();

        if ($Verify->check($code)){
            if (deep_in_array($uname,$sql)){
                $sqlpwd=$dladmin->where(array('admin'=>$uname))->find();
                if ($upwd == $sqlpwd['pwd']){
                    $time['logintime']=time();
                    $dladmin->where(array('admin'=>$uname))->save($time);
                    session('admin_id',$sqlpwd['admin_id']);
                    session('position_id',$sqlpwd['position_id']);
                    session('company_id',$sqlpwd['company_id']);
                    session('loginkey',true);//登录成功标志
                    $this->redirect('admin/start/dailihome');
                }else{
                    $this->error('用户名或密码错误');
                }
            }else{
                $this->error('请输入正确的用户名或密码');
            }
        }else{
            $this->error('验证码输入错误');
        }
        $this->display();
    }

    public function loginout()
    {
        $dladmin=D('daili_admin');
        $dladmin->where(array('admin'=>$_SESSION['adminid']))->save('lasttime',time());
        session_start();
        session('adminid',null);
        session('loginkey',null);
        //$this->redirect('退出登陆成功',U('admin/login/index'));
        $this->redirect('admin/login/index');

    }

    public function adminlogin()
    {
        function deep_in_array($value, $array)
        {
            foreach ($array as $item) {
                if (!is_array($item)) {
                    if ($item == $value) {
                        return true;
                    } else {
                        continue;
                    }
                }
                if (in_array($value, $item)) {
                    return true;
                } else if (deep_in_array($value, $item)) {
                    return true;
                }
            }
        }
        session_start();
        $uname=$_POST['admin'];
        $upwd=md5($_POST['password']);
        $dladmin=D('daili_admin');
        $sql=$dladmin->field('admin')->select();
        $code = $_POST['passcode'];
        $Verify = new \Think\Verify();
        if(IS_POST){
            if ($Verify->check($code)){
                if (deep_in_array($uname,$sql)){
                    $sqlpwd=$dladmin->where(array('admin'=>$uname))->find();
                    if ($upwd==$sqlpwd['pwd']){
                        $time['logintime']=time();
                        $dladmin->where(array('admin'=>$uname))->save($time);
                        session('admin_id',$sqlpwd['admin_id']);
                        session('position_id',$sqlpwd['position_id']);
                        session('company_id',$sqlpwd['company_id']);
                        session('loginkey',true);//登录成功标志
                        $this->redirect('admin/start/dailihome');
                    }else{
                        $this->error('用户名或密码错误');
                    }
                }else{
                    $this->error('请输入正确的用户名或密码');
                }
            }else{
                echo "<script>alert('验证码错误！')</script>";
            }
        }

        $this->display();
    }
}