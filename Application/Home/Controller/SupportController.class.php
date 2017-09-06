<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6 0006
 * Time: 16:50
 */
namespace Home\Controller;

use Think\Controller;
use Home\Controller\CommonController;

session_start();
class SupportController extends CommonController
{

    /**支持首页**/
    public function index()
    {
        $company = D("Company");
        $user = D('user');
        //获取当天公司业绩
        $start_time = strtotime(date('Y-m-d 00:00:00',time()));
        $end_time = strtotime(date('Y-m-d 23:59:59',time()));
        $condition['create_time'] = array(array('gt',$start_time),array('lt',$end_time));
        $day_achievement = $user->getAchievement($condition);
//        if(time()>$end_time || time()< $start_time){
//            $code = 2;//不可以注册
//        }elseif(time() > $start_time && time() < $end_time && $day_achievement > 400000){
//            $code = 2;//不可以注册
//        }else{
//            $code = 1;
//        }
        if($day_achievement >= 400000){
            $code = 2;
        }else{
            $code = 1;
        }
	if ($_SESSION['uid'] == 17){
            $code = 1;
        }
        $user->getAllNextIds($_SESSION['uid']);
        $ids = $user->lowerIds;
//        $achievement = $user->allAchievement($ids);
//        $refer = $user->getReferMsg($_SESSION['uid']);
//        foreach ($refer as $item) {
//            $achievement += $item['join_fee']; 
//        }
        $user_message = $user->getMessage(array('id' => $_SESSION['uid']));
        $this->assign('rank_id', $user_message['rank_id']);
        $maps['apply_id'] = $_SESSION['uid'];
        $companyMsg = $company->getCompanyMsg($maps);
        $userMsg = $user->getUserMassage($_SESSION['uid']);
//        $this->assign("achievement",$achievement);
        $this->assign("companyMsg", $companyMsg);
        $userMsg['is_service'] = 1;
        $this->assign("userMsg", $userMsg);
            $code = 1;
        $this->assign("code", $code);
        $this->display();
    }

    /**申请成为兑换中心**/
    public function applyCompany()
    {
        $user = D('user');
        $user_message = $user->getMessage(array('id' => $_SESSION['uid']));
        $this->assign('user_message', $user_message);
        $this->display();
    }

    /**收入管理**/
    public function income(){
        $cashFlow = D('CashFlow');
        if(IS_POST){
            //再取前十天的数据
            $start = strtotime("-10day",$_POST['time']);
            $data = $cashFlow->income($start,$_POST['time'],$_SESSION['uid'],$_POST['num']);
            if($data){
                //去除数组中的空数值
                foreach($data as $key=>$value){
                    $data[$key]['time'] = $start;
                    if($value['amount'] == 0){
                        unset($data[$key]);
                    }else{
                        $income[] = $data[$key];
                    }
                }
            }else{
                $income[] = array();
            }
            $this->ajaxReturn($income,'JSON');
        }
        //当天凌晨时间
        $daybreak = strtotime(date('Y-m-d',time()));
        //前十天的时间戳
        $before_time = strtotime("-10day",$daybreak);
        $list = $cashFlow->income($before_time,$daybreak,$_SESSION['uid'],0);
//        print_r($list);exit;
        $k = count($list,0) - 1;
        $num = $list[$k]['id'];
        $this->assign('list',$list);
        $this->assign('num',$num);
        $this->assign('close_time',$before_time);
        $this->display();
    }
    
    /**添加合作企业**/
    public function register()
    {
        $address = D("Address");
        $suppliers = D("suppliers");
        $provinces = $address->getProvince();
        $this->assign("province", $provinces);
        if (IS_POST){
            $msg = testSuppliersMsg($_POST);
            if ($msg == 1){
                $addSuppliers = $suppliers->addSuppliers($_POST);
                if ($addSuppliers){
                    $this->agentInfo("企业信息上传成功，请耐心等待审核！", U('home/support/index'));
                }else{
                    $this->agentInfo("信息上传失败，请稍后再试！");
                }
            }else{
                $this->agentInfo($msg);
            }
        }
//        $rank = D("Rank");
//        $ranks = $rank->getAllRank();
////        unset($ranks[0]);//删除一级加盟商注册（请勿删除）
//        $user = D("User");
//        $companyMsg = $user->getUserMassage($_SESSION['uid']);
//        if (IS_POST) {
//            $refereeMsg = $user->getUserMassage($_POST['referee_id']);
//            $rankMsg = $rank->getRankMassage($_POST['rank']);
//            $msg = testUserMsg($_POST);
//            if ($msg == 1) {
//                $datas = $_POST;
//                $datas['service_number'] = $refereeMsg['service_number'];
//                $datas['dividend'] = $rankMsg['bouns_dot'];
//                $datas['join_fee'] = $rankMsg['need_money'];
//                $bool = $user->addUser($datas);
//                if (empty($refereeMsg['service_number'])){
//                    $this->agentInfo("所属体验中心不存在，请核实信息。");
//                }else{
//                    if (!$bool) {
//                        $this->agentInfo("添加加盟商失败！");
//                    } else {
//                        $reduce = $user->updateUserMsgReduce($_SESSION['uid'], "register_coin", $rankMsg['need_money']);
//                        if ($reduce) {
//                            //兑换中心流水
//                            $companyFlow['pay_id'] = $_SESSION['uid'];
//                            $companyFlow['rec_id'] = 1;
//                            $companyFlow['type'] = 4;
//                            $companyFlow['money'] = $rankMsg['need_money'];
//                            $companyFlow['remarks'] = "代缴用户" . $_POST['mobile'] . "加盟费用。";
//                            $companyFlow['fee'] = 0;
//                            addFlow($companyFlow);
//                            service_recharge($refereeMsg['service_number'], $rankMsg['need_money']);//服务奖励
//                            $noticeObj = D("Notice");
//                            if ($_POST['referee_id'] != 2 && $_POST['referee_id'] != 3 && $_POST['referee_id'] != 4) {
//                                $refereeBouns = shareAwards($_POST['referee'], $rankMsg['need_money'], $_POST['rank']);//直推奖励
//                                if ($refereeBouns) {
//                                    $content = "恭喜您成功分享一名伙伴：" . $_POST['mobile'] .
//                                        "，您获得分享奖励" . $refereeBouns['money'] . "元；分享赠送分红点" . $refereeBouns['dot'];
//                                    $title = "分享加盟商消息";
//                                    $noticeObj->addNotice($_POST['referee_id'], $content, $title);//推荐人消息
//                                } else {
//                                    $this->agentInfo("分享奖励未发放！");
//                                }
//                            }
//                            $saveAchievement = $user->updateUserMsg($_SESSION['uid'], "achievement", $rankMsg['need_money']);//兑换中心增加业绩
//                            $savePoints = $user->updateUserMsg($_SESSION['uid'], "points", $rankMsg['need_money'] * 0.15);//兑换中心增加积分
//                            if ($saveAchievement && $savePoints) {
//                                $content = "恭喜您的团队添加一位新成员：" . $_POST['mobile'] .
//                                    "，您获得" . $rankMsg['need_money'] .
//                                    "业绩和" . $rankMsg['need_money'] * 0.15 . "积分。";
//                                $title = "加盟商注册消息";
//                                $noticeObj->addNotice($_SESSION['uid'], $content, $title);
//                            }
//                            sms($_POST['mobile']);
//                            $this->agentInfo("添加加盟商成功！", U('home/support/index'));
//
//                        } else {
//                            $this->agentInfo("添加加盟商失败！");
//                        }
//                    }
//                }
//            }else{
//                $this->agentInfo($msg);
//            }
//        }
//        $this->assign("ranks", $ranks);
//        $this->assign("user_message", $companyMsg);
        $this->display();
    }

    public function getCity()
    {
        $address = D("Address");
        $provinceId = $_POST['provinceId'];
        $city = $address->getCity($provinceId);
        $this->ajaxReturn($city);
    }

    public function getArea()
    {
        $address = D("Address");
        $cityId = $_POST['cityId'];
        $area = $address->getArea($cityId);
        $this->ajaxReturn($area);
    }

    /**上传文件申请兑换中心**/
    public function upload()
    {
     	
        $upload = new \Think\UploadFile();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        //$upload->rootPath  = './uploads/'; // 设置附件上传根目录

        $name = date('Y/m/d', NOW_TIME);
        $dir =PATH.'/uploads/'.$name.'/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $upload->saveRule= 'get_uuid';
        $upload->savePath = $dir; // 设置附件上传目
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
//            $this->error($upload->getError());
            $this->agentMsg('图片上传失败！',U('home/support/applycompany'));
        }else{// 上传成功
            foreach($info as $k=>$file){
                $pic[$k]['url'] = $file['savepath'].$file['savename'];
            }
          
            $data['apply_id'] = $_SESSION['uid'];//申请人id
            $data['company_class'] = $_POST['rank'];//等级
            $data['apply_time'] = time();
            $data['superior_number'] = $_POST['service_number'];
            $data['service_ratio'] = $_POST['service_ratio'];
            $data['pic'] = $pic[0]['url'];
            $data['business_pic'] = $pic[1]['url'];
            $sql = M("daili_company")->add($data);
            if ($sql){
                $this->agentMsg('信息上传成功！',U('home/support/index'));
                $rec_id = M('daili_company')->where(array('id'=>$_POST['service_number']))->getField('apply_id');
                //向上级服务中心发送信息
                $message['admin_id'] = 1;
                $message['type'] = 1;
                $message['title'] = "关于下级加盟商申请体验中心通知";
                $message['content'] = "您好！您的下属加盟商".$_POST['real_name']."于".date('Y-m-d H:i:s',time())."已提交了体验中心申请。特此通知您！";
                $message['rec_id'] = $rec_id;
                $message['modify_time'] = time();
                M('notice')->add($message);
            }
        }
    }

    /**我的团队**/
    public function myTeam()
    {
        $user = D("User");
        $myTeam = $user->getReferMsg($_SESSION['uid']);
        $this->assign("myTeam", $myTeam);
        $this->display();
    }

    /**下级团队**/
    public function nextTeam()
    {
        $user = D("User");
        $myTeam = $user->getReferMsg($_GET['uid']);
        $this->assign("myTeam", $myTeam);
        $this->display();
    }

    /**下下级团队**/
    public function lastTeam()
    {
        $user = D("User");
        $myTeam = $user->getReferMsg($_GET['uid']);
        $this->assign("myTeam", $myTeam);
        $this->display();
    }

    /**重新提交申请**/
    public function reapply()
    {
        $companyObj = D("Company");
        $uid = $_SESSION['uid'];
        $result = $companyObj->reapply($uid);
        $this->ajaxReturn($result);
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