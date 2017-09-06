<?php
/**
 * 首页（我的）控制器
 * Created by PhpStorm.
 * User: ZHOU
 * Date: 2017/3/1
 * Time: 13:33
 */
namespace Home\Controller;
use Think\Controller;
use Home\Controller\CommonController;

session_start();

class IndexController extends CommonController{

    /**我的**/
    public function index(){
        //个人信息
        $user = D('user');
        $user_id = $_SESSION['uid'];
        $map['id'] = $user_id;
        $user_message = $user->getMessage($map);
        //当日的业绩
//        $today_achievement = $user->dayAchievement($user_id);
        $number = $user->getReferNum($user_id);
        $ranking = $user->rankingList($user_id);
        $user->getAllNextIds($user_id);
        $ids = $user->lowerIds;
        $user_message['all_achievement'] = $user->allAchievement($ids);
//        $user_message['today_achievement'] = $today_achievement;
        $user_message['number'] = $number;
        $user_message['ranking'] = $ranking;
        //特殊业绩
        $userObj = D("User");
        $userObj->getNextService($_SESSION['uid']);
        $nextServices = $userObj->nextService;
        $allAchievement = 0;
        foreach ($nextServices as $nextService) {
            $allAchievement += $nextService['achievement'];
        }
        $this->assign("allAchievement",$allAchievement);
        $this->assign('rank_id',$user_message['rank_id']);
        $this->assign('user_message',$user_message);
        $this->display();
    }
    
    /**个人信息详情**/
    public function myInfo(){
        $user = D('user');
        $user_id = $_SESSION['uid'];
        $map['id'] = $user_id;
        $user_message = $user->getMessage($map);
//        $exchange = D('exchange')->getMessage(array('id'=>$user_message['service_number']));
//        $user_message['exchange_name'] = $exchange['user_name'];
        $user_message['card_id'] = substr($user_message['card_id'],0,2) . '*********' . substr($user_message['card_id'],-4);
        $service = $user->getMessage(array('id' => $user_message['service_number']));
        $address = D('Address');
        $service_name = $address->getProvinceName($service['province_id']) . "办事处";
        $user_message['service'] = $service_name;
        $this->assign('user_message',$user_message);
        $this->display();

    }

    /**级别**/
    public function rank(){
        $user_message = D('user')->getUserMassage($_SESSION['uid']);
        $rank = D('rank');
        $rank_message = $rank->getAllRank();
        $class_a = D('user')->getRankNum(1);
        $class_b = D('user')->getRankNum(2);
        $class_c = D('user')->getRankNum(3);
        foreach($rank_message as $key=>$val){
            if($val['rank'] == $user_message['rank_id']){
                $my_rank['rank_name'] = $val['rank_name'];
                $my_rank['cost'] = $val['need_money'];
            }
            if($val['id'] == 1){
                $rank_message[$key]['surplus'] = $val['limit_people'] - $class_a;
            }
            if($val['id'] == 2){
                $rank_message[$key]['surplus'] = $val['limit_people'] - $class_b;
            }
            if($val['id'] == 3){
                $rank_message[$key]['surplus'] = $val['limit_people'] - $class_c;
            }
        }
        $my_rank['user_id'] = $_SESSION['uid'];
        $my_rank['id'] = $user_message['rank_id'];
        $this->assign('my_rank',$my_rank);
        $this->assign('rank_message',$rank_message);
        $this->display();
    }

    /**升级数据交互**/
    public function rankAjax(){
        //获取账户注册币余额
        $user = D('user');
        $user_message = $user->getMessage(array('id'=>$_POST['user_id']));
        //获取升级需要补交金额
        $rank = D('rank');
        $rank_cost = $rank->getMessage(array('rank'=>$_POST['rank_id']));
        $pay_money = $rank_cost['need_money'] - $user_message['join_fee'];
        $money = $user_message['register_coin'] - $pay_money;
        if($money >= 0){
            $update_message = upgrade_payment($_POST['user_id'],$user_message['referee_id'],$user_message['rank_id'],$_POST['rank_id'],$pay_money);
            if($update_message){
                $data['code'] = 1;
            }else{
                $data['code'] = 2;
            }
        }else{
            $data['code'] = 3;
        }
        $this->ajaxReturn($data,'JSON');
    }
    /**排行榜**/
    public function rankingList()
    {
        $user = D("User");
        $maps['id'] = array("gt",4);
        $achievementRanking = $user->where($maps)->limit(100)->ranking("achievement");
        $pointsRanking = $user->where($maps)->limit(100)->ranking("points");
        $incomeRanking = $user->where($maps)->limit(100)->ranking("income");
        $this->assign("achievementRanking",$achievementRanking);
        $this->assign("pointsRanking",$pointsRanking);
        $this->assign("incomeRanking",$incomeRanking);
        $this->display();
    }

    public function myTeam()
    {
        $userObj = D("User");
        $userObj->getNextService($_SESSION['uid']);
        $nextServices = $userObj->nextService;
        $this->assign("nextService",$nextServices);
        $this->display();
    }

    public function changeFace()
    {
        $this->display();
    }
}