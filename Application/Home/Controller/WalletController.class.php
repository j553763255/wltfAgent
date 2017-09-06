<?php
/**
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2016/12/20
 * Time: 15:40
 */
namespace Home\Controller;
use Think\Controller;
use Home\Controller\CommonController;

session_start();
class WalletController extends CommonController{
    public function index()
    {
        $users = D("User");
        $bank = D("UserBank");
        $banks = $bank->bankMsg($_SESSION['uid']);
        if (empty($banks)){
            $bankUrl = 1;
        }else{
            $bankUrl = 2;
        }
        $configObj = D("ConfigSetting");
        $times = $configObj->getTime();
        if ($times){
            $nowWeek = null;$startTime = null;$endTime = null;
            foreach ($times as $time) {
                if ($time['id'] == 3){
                    $cashWeek = $time['value'];
                }elseif($time['id'] == 1){
                    $startTime = $time['value'];
                }elseif($time['id'] == 2){
                    $endTime = $time['value'];
                }
            }
            if (time() < $endTime && time() >$startTime){
                $code = 1;
            }elseif($cashWeek == getTimeWeek(time())){
                if (time() > strtotime(date('Y-m-d 10:00:00')) && time()< strtotime(date('Y-m-d 22:00:00'))){
                    $code = 1;
                }else{
                    $code = 2;
                }
            }else{
                $code = 2;
            }
        }else{
            $code = 2;
        }
        $tabCode = $_GET['code'];
        if ($tabCode == 1){
            $url = 'index';
        }else{
            $url = 'support';
        }
        $this->assign("url",$url);
        $user = $users->getUserMassage($_SESSION['uid']);
        $this->assign("code",$code);
        $this->assign("user",$user);
        $this->assign("bankUrl",$bankUrl);
        $this->display();
    }

    /**转账记录**/
    public function moneyDetail(){
        $flows = D("CashFlow");
        if (IS_POST){
            $time = $_POST['time'];
            $state = $_POST['state'];
            if ($state == "expend"){
                $flow = $flows->getFlow("expend",$time,$_SESSION['uid']);
            }elseif ($state == "income"){
                $flow = $flows->getFlow("income",$time,$_SESSION['uid']);
            }
            foreach ($flow['flow'] as $k => $value) {
                $flow['flow'][$k]['time'] = date('Y-m-d', $value['create_time']);
            }
            $data = array(
                "flow" => $flow,
                "type" => $_POST['state']
            );
            $this->ajaxReturn($data);
        }
        $expend = $flows->getFlow("expend",null,$_SESSION['uid']);
        $income = $flows->getFlow("income",null,$_SESSION['uid']);
        $this->assign("expend",$expend);
        $this->assign("income",$income);
        $this->display();
    }

    public function singleDetailed()
    {
        if ($_GET['type'] = 1){
            $type = "余额";
            $flows = D("CashFlow");
        }else{
            $flows = D("Points");
            $type = "积分";
        }
        $flow_id = $_GET['flow_id'];
        $flow = $flows->getFlowById($flow_id);
        if ($flow['type'] == 4){
            $flow['integral'] = -floor($flow['money'] * 0.15);
        }else{
            $flow['integral'] = 0;
        }
        $this->assign("type",$type);
        $this->assign("flow",$flow);
        $this->display();
    }

    public function pointsDetail()
    {
        $flows = D("PointsFlow");
        if (IS_AJAX){
            $time = $_POST['time'];
            if (I('state') == "expend"){
                $flow = $flows->getFlow("expend",$time,$_SESSION['uid']);
            }elseif (I('state') == "income"){
                $flow = $flows->getFlow("income",$time,$_SESSION['uid']);
            }
            foreach ($flow['flow'] as $k => $value) {
                $flow['flow'][$k]['time'] = date('Y-m-d', $value['create_time']);
            }
            $data = array(
                "flow" => $flow,
                "type" => $_POST['state']
            );
            $this->ajaxReturn($data);
        }
        $expend = $flows->getFlow("expend",null,$_SESSION['uid']);
        $income = $flows->getFlow("income",null,$_SESSION['uid']);
        $this->assign("expend",$expend);
        $this->assign("income",$income);
        $this->display();
    }

    /**绑定银行卡**/
    public function bindingBank()
    {
        if (IS_POST){
            if (in_array("",$_POST)){
                $this->agentMsg("请完善银行卡信息！");
            }else{
                $user = D("User");
                $bank = D("UserBank");
                $userMsg = $user->getUserMassage($_SESSION['uid']);
                if(in_array("",$_POST)){
                    $this->agentMsg("请完善银行卡信息！");
                }
                if($_POST['holder-name'] != $userMsg['real_name']){
                    $this->agentMsg("持卡人必须是用户自身！");
                }else{
                    if ($_POST['card-id'] != $userMsg['card_id']){
                        $this->agentMsg("身份证信息错误！");
                    }else{
                        $data['user_id'] = $_SESSION['uid'];
                        $data['create_time'] = time();
                        $data['bank_account'] = $_POST['bank-account'];
                        $data['bank_card'] = $_POST['bank-card'];
                        $data['bank_name'] = $_POST['bank-name'];
                        $data['holder_name'] = $_POST['holder-name'];
                        $data['mobile'] = $_POST['mobile'];
                        $sql = $bank->addBank($data);
                        if ($sql){
                            $this->agentMsg("添加银行卡成功！",U("home/wallet/myBank"));
                        }else{
                            $this->agentMsg("添加银行卡失败！");
                        }
                    }
                }
            }
        }
        $this->display();
    }
    /**我的银行卡**/
    public function myBank()
    {
        $bank = D("UserBank");
        $bankMsg = $bank->bankMsg($_SESSION['uid']);
        foreach ($bankMsg as $k => $item) {
            $bankMsg[$k]['bank_card'] = substr($item['bank_card'],0,4) . " **** **** **** " . substr($item['bank_card'],-3);
        }
        $this->assign("bankMsg",$bankMsg);
        $this->display();
    }

    /**设置默认银行卡**/
    public function setBank()
    {
        $bankObj = D("UserBank");
        $bank_id = $_POST['bank_id'];
        $setResult = $bankObj->setDefaultBank($bank_id,$_SESSION['uid']);
        $this->ajaxReturn($setResult);
    }

    /**删除银行卡**/
    public function delBank()
    {
        $bankObj = D("UserBank");
        $bank_id = $_POST['bank_id'];
        $delResult = $bankObj->delBank($bank_id);
        $this->ajaxReturn($delResult);
    }

    /**转账**/
    public function transfer()
    {
        if(IS_POST){
            $userObj = D("User");
            $transfer = D("Transfer");
            $flow = D('cashFlow');
            $rec_message = $userObj->getUserByMobile($_POST['rec_mobile']);
            $addCashFlow = $flow -> addFlow($_SESSION['uid'],$rec_message['id'],1,$_POST['need_pay'],'注册币转账',0);
            if ($addCashFlow){
                $transferResult = $userObj->transfer($_POST['rec_mobile'],$_SESSION['uid'],$_POST['need_pay']);
                $transfer -> addTransfer($_SESSION['uid'],$rec_message['id'],$_POST['need_pay'],0);   //写入转账表
                $transfer -> changeStatus($transferResult);
                if($transferResult == 2){
                    $this->agentMsg("转账成功！",U('home/wallet/index'));
                }elseif($transferResult == 4){
                    $this->agentMsg("余额不足");
                }else{
                    $this->agentMsg("转账失败,错误代码 $transferResult ,请尽快联系客服。");
                }
            }else{
                $this->agentMsg('转账失败！');
            }
        }
        $this->display();
    }

    /**查询用户**/
    public function queryUser()
    {
        $userObj = D("User");
        $mobile = $_POST['mobile'];
        $user = $userObj->getUserByMobile($mobile);
        $this->ajaxReturn($user);
    }

    /**提现**/
    public function cash()
    {
        $bankObj = D("UserBank");
        $bankMsg = $bankObj->getDefaultBank($_SESSION['uid']);
        if (IS_POST){
            if($_POST['cash_money'] < 200){
                $this->agentMsg("提现金额不得小于200元!");
            }else{
                $user = D("User");
                $cashLog = D("CashLog");
                $userMsg = $user->getUserMassage($_SESSION['uid']);
                $todayLog = $cashLog->getCashLog($_SESSION['uid']);
                if ($todayLog){
                    $this->agentInfo("今日提现机会已用完。");
                }else{
                    $rank = D("Rank");
                    $fee = $rank->getTransferFee($userMsg['rank_id'],$_POST['money']);
                    if(!empty($bankMsg)){
                        $cashFlow = D("CashFlow");
                        $remarks = "用户提现，提现金额：".$_POST['cash_money'];
                        //添加流水记录
                        $addCashFlow = $cashFlow->addFlow(1,$_SESSION['uid'],2,$_POST['cash_money'],$remarks,$fee);
                        if($addCashFlow){
                            //账户扣款
                            $cashResult = $user->userCash($_SESSION['uid'],$_POST['cash_money']);
                            if($cashResult == 1){
                                //提现日志
                                $addCashLog = $cashLog->addCashLog($_SESSION['uid'],$bankMsg,$_POST['cash_money'],$fee);
                                if($addCashLog){
                                    $this->agentInfo("申请提现成功，请耐心等待公司处理！");
                                }else{
                                    $this->agentMsg("网络故障申请失败，已扣款，请赶紧截图并联系公司！");
                                }
                            }else{
                                $this->agentMsg($cashResult);
                            }
                        }else{
                            $this->agentMsg("申请提交失败!");
                        }
                    }else{
                        $this->agentMsg("请先绑定银行卡！");
                    }
                }
            }
        }
        $bankMsg['bankLast'] = substr($bankMsg['bank_card'],-4);
        $this->assign("defaultBank",$bankMsg);
        $this->display();
    }
    /**充值**/
    public function recharge()
    {
        if(IS_AJAX){
            $pay_pwd = md5($_POST['pwd']);
            $need_pay = $_POST['money'];
            $type = $_POST['code'];
            $userObj = D("User");
            $userMsg = $userObj->getUserMassage($_SESSION['uid']);
//            if (empty($userMsg['pay_pwd'])){
//                $msg = "请先设置支付密码";
//            }else{
//                if ($pay_pwd != $userMsg['pay_pwd']){
//                    $msg = "支付密码错误";
//                }else{
//                    if ($userMsg['money'] < $need_pay){
//                        $msg = "余额不足";
//                    }else{
//                        $result = $userObj->turnCoin($_SESSION['uid'],$need_pay);
//                        if($result){
//                            D("RechargeLog")->addRechargeLog($_SESSION['uid'],$need_pay,$type);
//                            $msg = "注册币充值成功！";
//                        }else{
//                            $msg = "注册币充值失败！";
//                        }
//                    }
//                }
//            }
            if(empty($_POST['money'])){
                $msg = "请充值输入金额。";
            }else{
                if(!is_numeric($_POST['money'])){
                    $msg = "充值金额要是数字哦。";
                }else{
                    if ($userMsg['money'] < $need_pay){
                        $msg = "余额不足";
                    }else{
                        $result = $userObj->turnCoin($_SESSION['uid'],$need_pay);
                        D("RechargeLog")->addRechargeLog($_SESSION['uid'],$need_pay,$type,$result);
                        D("CashFlow")->addFlow($_SESSION['uid'],$_SESSION['uid'],11,$need_pay,'注册币充值',0);
                        if($result == 1){
                            $msg = "注册币充值成功!";
                        }else{
                            $msg = "注册币充值失败,错误代码 $result ，请尽快联系客服!";
                        }
                    }
                }
            }
            $this->ajaxReturn($msg);
        }
        $this->display();
    }
}