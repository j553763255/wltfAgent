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
class RabbitBaoController extends CommonController{

    /**
     *消息
     * 2017-3-5
     * juzhijin
     **/
    public function index(){
        $cashFlow = D("CashFlow");
        $notice = D("Notice");
        $userObj = D("User");
        $userMsg = $userObj->getUserMassage($_SESSION['uid']);
        $allIncome = $userMsg['income'];
        $todayFlow = $cashFlow->getTodayFlow($_SESSION['uid']);
        
        $flows = $cashFlow->getFlowByRec($_SESSION['uid']);
        $count = 0;
        $counted = 0;
        foreach ($flows as $flow){
            $count += 1;
            if ($flow['status'] != 1){
                $counted += 1;
            }
        }
        $this->assign("count",$count);
        $this->assign("counted",$counted);
        
        $notices = $notice->getNoticeByRec($_SESSION['uid']);
        $noticeCount = 0;
        $noticeCounted = 0;
        foreach ($notices as $notice){
            $noticeCount += 1;
            if ($notice['status'] != 1){
                $noticeCounted += 1;
            }
        }
        $this->assign("noticeCount",$noticeCount);
        $this->assign("noticeCounted",$noticeCounted);
        
        $today_income = 0;
        $today_pay = 0;
        foreach ($todayFlow as $item) {
            if (in_array($item['type'],array(3,5,7,8,9))){
                $today_income += $item['money'];
            }else{
                $today_pay += $item['money'];
            }
        }
        $this->assign("income",$today_income);
        $this->assign("allIncome",$allIncome);
        $this->assign("pay",$today_pay);
        $this->display();
    }

    public function moneyNotice()
    {
        $flowObj = D("CashFlow");
        $flows = $flowObj->getFlowByRec($_SESSION['uid']);
        $flowObj->changeStatus();
        $this->assign("flow",$flows);
        $this->display();
    }
    public function agentNotice()
    {
        $notice = D("Notice");
        $notices = $notice->getNotice($_SESSION['uid']);
        $notice->changeStatus();
        $this->assign("notice",$notices);
        $this->display();
    }

    /*转账记录*/
    public function accountRecoder(){
        $db_transfer = D("Transfer");
        $db_user = D('user');
        $uid = $_SESSION['uid'];
        $transfer = $db_transfer -> getTsfer($uid);
        foreach($transfer as $k => $v){
            if($uid == $transfer[$k]['pay_id']){
                $where['id'] = $transfer[$k]['rec_id'];
                $transfer[$k]['title'] = '转账成功';
            }elseif($uid == $transfer[$k]['rec_id']){
                $where['id'] = $transfer[$k]['pay_id'];
                $transfer[$k]['title'] = '收到转账';
            }else{
                $transfer[$k]['title'] = '转账失败';
            }
            $user_res = $db_user -> where($where) ->find();
            $transfer[$k]['username'] = $user_res['user_name'];
        }
        $this -> assign('transfer',$transfer);
        $this->display();
    }

}