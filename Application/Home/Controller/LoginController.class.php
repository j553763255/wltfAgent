<?php
/**
 * 我的任务模块
 * 1.分享店铺 2.分享会员 3.任务完成奖励列表
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 16:01
 */
namespace Home\Controller;

use Think\Controller;

session_start();
class LoginController extends Controller
{
    /*三种弹窗*/
    protected function agentInfo($message, $jumpUrl = '', $time = 3000)
    {
        $str = '<script>';
        $str .= 'parent.mobileBoxInfo("' . $message . '","' . $jumpUrl . '","' . $time . '");';
        $str .= '</script>';
        die($str);
    }

    protected function loadingBox($message)
    {
        $str = '<script>';
        $str .= 'parent.loadingBox("' . $message . '");';
        $str .= '</script>';
        echo($str);
    }

    protected function agentMsg($message, $jumpUrl = '', $time = 3000)
    {
        $str = '<script>';
        $str .= 'parent.boxMsg("' . $message . '","' . $jumpUrl . '","' . $time . '");';
        $str .= '</script>';
        die($str);
    }

    public function login()
    {
        $this->display();
    }

    /*登录信息验证*/
    public function index()
    {
        session_start();
        $_SESSION['uid'] = 0;
        if (IS_POST) {
            session_start();
            $userName = $_POST['username'];//登录用户名
            $password = md5($_POST['password']);//登录密码
            $user = D('User');
            //获取用户信息
            if (empty($userName)) {
                $this->agentMsg("请输入用户名！");
            } else {
                if (empty($password)) {
                    $this->agentMsg("请输入密码！");
                } else {
                    $userMassage = $user->loginMessage($userName);
                    if (empty($userMassage)) {
                        $this->agentMsg("用户名不存在！");
                    } elseif ($userMassage['status'] == 2) {
                        $this->agentMsg("您的账户已被冻结！");
                    } else {
                        if ($password == $userMassage['pwd']) {
                            session('uid', $userMassage['id']);
                            $this->agentMsg("登录成功", U("home/index/index"), 1000);
                        } else {
                            $this->agentMsg("用户名或密码错误！");
                        }
                    }
                }
            }

//            $code = $_POST['passcode'];//登录验证码
//            $Verify = new \Think\Verify();
//            if ($Verify->check($code)){
//                if($user->checkLogin($name,$user_name)){
//                    $user_message=$user->getMessage(array('user_name'=>$name));
//                    if ($upwd == $user_message['pwd']){
//                        $time['last_login_time'] = time();
//                        $user->updateMessage($time,array('user_name'=>$name));
//                        session('uid',$user_message['id']);//用户id
//                        session('rank_id',$user_message['rank_id']);
//                        session('login_type','user');
//                        session('login_key',true);//登录成功标志
////                        $this->redirect('home/Index/index');
//                        //判断是否将个人信息完善
//                        if(empty($user_message['real_name']) || empty($user_message['cardid']) || empty($user_message['contact_name']) || empty($user_message['contact_mobile'])){
//                                $this->error('请完善个人信息，否则系统无法正常使用！',U('home/index/edit'));
//                            }else {
//                                $this->redirect('home/Index/index');
//                            }
//                    }else{
//                        $this->error('用户名或密码错误');
//                    }
//                }else{
//                    $this->error('请输入正确的用户名或密码');
//                }
//            }else{
//                $this->error('验证码输入错误');
//            }
        }
        $this->display("login");
    }

    /**代理注册**/
    public function register()
    {
        $address = D("Address");
        $provinces = $address->getProvince();
        $rank = D("Rank");
        $ranks = $rank->getAllRank();
//        unset($ranks[0]);//删除一级加盟商注册（请勿删除）
        $user = D("User");
        $exchangeMsg = $user->getUserMassage($_SESSION['uid']);
        if (IS_POST) {
            $refereeMsg = $user->getUserMassage($_POST['referee_id']);
            $rankMsg = $rank->getRankMassage($_POST['rank']);
            $msg = testUserMsg($_POST);//验证信息
            if ($msg == 1) {
                $datas = $_POST;
                $datas['service_number'] = $refereeMsg['service_number'];
                $datas['dividend'] = $rankMsg['bouns_dot'];
                $datas['join_fee'] = $rankMsg['need_money'];
                $bool = $user->addUser($datas);
//                $bool = 1;
                if (!$bool) {
                    $this->agentInfo("添加加盟商失败！");
                } else {
                    /**注册成功，上级产生奖励流水**/
//                      $reduce = $user->updateUserMsgReduce($_SESSION['uid'], "register_coin", $rankMsg['need_money']);//减少推荐人注册币
//                        $exchangeFlow['pay_id'] = 1;
//                        $exchangeFlow['rec_id'] = $_POST['referee_id'];
//                        $exchangeFlow['type'] = 4;
//                        $exchangeFlow['money'] = $rankMsg['need_money'];
//                        $exchangeFlow['remarks'] = "用户" . $_POST['mobile'] . "加盟费用。";
//                        $exchangeFlow['fee'] = 0;
//                        addFlow($exchangeFlow);
//                        service_recharge($refereeMsg['service_number'], $rankMsg['need_money']);//服务奖励
                    $noticeObj = D("Notice");
                    if ($_POST['referee_id'] != 2 && $_POST['referee_id'] != 3 && $_POST['referee_id'] != 4) {
                        $refereeBouns = shareAwards($_POST['referee'], $rankMsg['need_money'], $_POST['rank']);//直推奖励
                        if ($refereeBouns) {
                            $content = "恭喜您成功分享一名伙伴：" . $_POST['mobile'] .
                                "，您获得分享奖励" . $refereeBouns['money'] . "元；分享赠送分红点" . $refereeBouns['dot'];
                            $title = "分享加盟商消息";
                            $noticeObj->addNotice($_POST['referee_id'], $content, $title);//推荐人消息
                        } else {
                            $this->agentInfo("分享奖励未发放！");
                        }
                    }
                    $saveAchievement = $user->updateUserMsg($_POST['referee_id'], "achievement", $rankMsg['need_money']);//兑换中心增加业绩
                    $savePoints = $user->updateUserMsg($_POST['referee_id'], "points", $rankMsg['need_money'] * 0.15);//兑换中心增加积分
                    if (!$saveAchievement){
                        $this->agentInfo("业绩未发放");
                    }else if (!$savePoints){
                        $this->agentInfo("积分未增加");
                    }else{
                        $content = "恭喜您的办事处添加一位新的成员：" . $_POST['mobile'] .
                            "，您获得" . $rankMsg['need_money'] .
                            "业绩和" . $rankMsg['need_money'] * 0.15 . "积分。";
                        $title = "加盟商注册消息";
                        $noticeObj->addNotice($_SESSION['uid'], $content, $title);
//                        $this->agentInfo($content);
                    }
                    $this->agentInfo("添加加盟商成功！", U('home/login/index'));
                }
            } else {
                $this->agentInfo($msg);
            }
        }
        $this->assign("ranks", $ranks);
        $this->assign("province", $provinces);
        $this->assign("user_message", $exchangeMsg);
        $this->display();
    }

    /*登出管理*/
    public function login_out()
    {

        session_start();
        session('uid', null);
        $this->redirect("home/login/login");
    }

    /**
     * 根据省id查询所有市
     */
    public function getCity()
    {
        $address = D("Address");
        $provinceId = $_POST['provinceId'];
        $city = $address->getCity($provinceId);
        $this->ajaxReturn($city);
    }

    /**
     * 根据市id查询所有省
     */
    public function getArea()
    {
        $address = D("Address");
        $cityId = $_POST['cityId'];
        $area = $address->getArea($cityId);
        $this->ajaxReturn($area);
    }

    /**查询推荐人**/
    public function getReferee()
    {
        $mobile = $_POST['referee'];
        $user = D("User");
        $refereeMsg = $user->getUserByMobile($mobile);
        $this->ajaxReturn($refereeMsg);
    }
}