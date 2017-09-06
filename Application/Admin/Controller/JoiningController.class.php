<?php
/**
 * 加盟商
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2017/2/24
 * Time: 19:11
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
use Admin\Controller\CommondController;

class JoiningController extends CommondController{

    /**加盟商管理**/
    public function details(){
        $user = D('User');
	/*$map['service_number'] = array('in',array(5,16,17,18,126,149,19,20,124,122));
	$map['create_time'] = array('lt',1497801600);
	$res = $user->field('id')->where(array($map))->select();
	foreach($res as $val){
		$upd = D('user')->where(array('id'=>$val['id']))->setDec('money',200);
		if($upd){
			 D('CashFlow')->addFlow($val['id'],1,6,200,'南阳会议扣款',0);
		}
	}


	die();*/
	/*$user->getAllNextIds(66);
        $ids = $user->lowerIds;
	  // print_r($ids);
          //$achievement = $user->allAchievement($ids);
	    //print_r($achievement);exit;
	  $c['id'] = array('in',$ids);
	 $c['create_time'] = array('gt','1494926476');
       // $c['create_time'] = array(array('gt','1494926476'),array('lt','1495036799'));
	 $user_m = D('user')->where($c)->field('id,real_name,join_fee,rank_id')->select();
	  foreach($user_m as $key=>$val){
	  	if($val['join_fee'] == 0){
			unset($user_m[$key]);
		}else{
			$ac += $val['join_fee'];
		}
	}
	   print_r($ac);exit;*/
        if(IS_POST){
            /*余额充值*/
            if($_POST['operate'] == 'recharge') {
                $user_message = $user->getMessage(array('id' => $_POST['id']));
                if ($user_message) {
                    $user_money = $user_message['money'] + $_POST['money'];
                    $update_money = $user->updateMessage(array('id' => $_POST['id']), array('money' => $user_money));
                    if ($update_money) {
                        D('CashFlow')->addFlow(1,$_POST['id'],6,$_POST['money'],$_POST["remarks"].'余额充值',0);
                        $data['code'] = 1;
                        $this->ajaxReturn($data, 'JSON');
                    }
                }
            }
            /*注册币充值*/
            if($_POST['operate'] == 'coin-recharge'){
                $user_message = $user->getMessage(array('id' => $_POST['id']));
                if ($user_message) {
                    $user_coin = $user_message['register_coin'] + $_POST['money'];
                    $update_money = $user->updateMessage(array('id' => $_POST['id']), array('register_coin' => $user_coin));
                    if ($update_money) {
                        D('CashFlow')->addFlow($_POST['id'],1,6,$_POST['money'],$_POST["remarks"].'注册币充值',0);
                        $data['code'] = 1;
                        $this->ajaxReturn($data, 'JSON');
                    }
                }
            }
            /*编辑原有信息查询*/
            if($_POST['type'] == 'msg'){
                $user_info = $user->getMessage(array('id' => $_POST['id']));
                $this->ajaxReturn($user_info,'JSON');
            }
            /*编辑*/
            if($_POST['operate'] == 'edit'){
                $update_map['user_name'] = $_POST['user_name'];
                if(!empty($_POST['pwd'])){
                    $update_map['pwd'] = md5($_POST['pwd']);
                }
                $update_map['real_name'] = $_POST['real_name'];
                $update_map['card_id'] = $_POST['card_id'];
                $update_map['rank_id'] = $_POST['rank'];
                $update_map['tel'] = $_POST['tel'];
                $update_map['status'] = $_POST['status'];
                $update_map['dividend'] = $_POST['dividend'];
                $update_map['province_id'] = $_POST['province'];
                $update_map['city_id'] = $_POST['city'];
                $update_map['area_id'] = $_POST['area'];
                $update_map['address'] = $_POST['address'];
                $update_map['achievement'] = $_POST['achievement'];
                $update_map['points'] = $_POST['points'];
                $update_message = $user->updateMessage(array('id'=>$_POST['id']),$update_map);
                if($update_message){
                    echo "<script>alert('数据更新成功');</script>";
                }else{
                    echo "<script>alert('数据更新失败')</script>";
                }
            }
            /*删除*/
            if($_POST['operate'] == 'del'){
                $result = $user->del(array('id'=>$_POST['id']));
                if($result){
                    echo "<script>alert('数据删除成功'})</script>";
                }
            }
            //添加
            if($_POST['operate'] == 'add'){
                $add_data['user_name'] = $_POST['user_name'];
                $add_data['pwd'] = MD5('teb123456');
                $add_data['real_name'] = $_POST['real_name'];
                $add_data['card_id'] = $_POST['card_id'];
                $add_data['rank_id'] = $_POST['rank'];
                $add_data['tel'] = $_POST['tel'];
                $add_data['status'] = $_POST['status'];
                $add_data['referee_id'] = $_POST['referee_id'];
                $add_data['service_number'] = $_POST['service_number'];
                $add_data['create_time'] = time();
                $add_data['join_fee'] = $_POST['join_fee'];
                $add_data['address'] = $_POST['address'];
                /*分红点，暂时修改请勿删除！*/
//                if($_POST['rank'] == 1){
//                    $add_data['dividend'] = 10;
//                }elseif($_POST['rank'] == 2){
//                    $add_data['dividend'] = 5;
//                }elseif($_POST['rank'] == 3){
//                    $add_data['dividend'] = 1;
//                }
                $add_data['dividend'] = $_POST['dividend'];
                $add_data['province_id'] = $_POST['province'];
                $add_data['city_id'] = $_POST['city'];
                $add_data['area_id'] = $_POST['area'];
                $add_message = $user->add($add_data);
                if($add_message){
                    echo "<script>alert('数据添加成功')</script>";
                }
            }
            if($_POST['operate'] == "test"){
                $result_rand = NoRand(0,30000,20);
                foreach($result_rand as $key=>$value){
                    $id = $key + 17;  
                    if($id <= 36){
                        $result = D('user')->where(array('id'=>$id))->setInc('income',$value);
			            if($result){
				            $data_mes[] = $id;
			            }
                    }
                }
		        if(count($data_mes) == 20){
			        $data['code'] = 1;
		        }else{
			        $data['code'] = 2;
		        }
                $this->ajaxReturn($data,"JSON");
            }
        }
        $map['id'] = array("not between","17,36");
        $p = getpage($user,$map,20);
        //首先查询出所有的加盟商
        $agent_message = $user->getInfo($map);
        //获取推荐人数
        foreach($agent_message as $value){
            $user_ids[] = $value['id'];
        }
        $num = $user->getNum($user_ids);
        if($num){
            foreach($agent_message as $key=>$val){
                $agent_message[$key]['referee_num'] = 0;
                foreach($num as $item){
                    if($item['id'] == $val['id']){
                        $agent_message[$key]['referee_num'] += 1;
                    }
                }
            }
        }else{
            foreach($agent_message as $key=>$val) {
                $agent_message[$key]['referee_num'] = 0;
            }
        }
        $address = D("Address");
        $provinces = $address->getProvince();
        $this->assign('list',$agent_message);
        $this->assign('province',$provinces);
        $this->page = $p->show();
        $this->display();
    }

    /**获取城市**/
    public function getCity()
    {
        $address = D("Address");
        $provinceId = $_POST['provinceId'];
        $city = $address->getCity($provinceId);
        $this->ajaxReturn($city);
    }

    /**获取区县**/
    public function getArea()
    {
        $address = D("Address");
        $cityId = $_POST['cityId'];
        $area = $address->getArea($cityId);
        $this->ajaxReturn($area);
    }

    /**纠纷加盟商列表**/
    public function botherList(){
        $user = D('User');
        if(IS_POST){
            //恢复状态
            $update_status = $user->updateMessage(array('id'=>$_POST['id']),array('status'=>$_POST['status']));
            if($update_status){
                $data['code'] = 1;
                $this->ajaxReturn($data,'JSON');
            }
        }
        $map['status'] = 2;
        $p = getpage($user,$map,20);
        $list = $user->getMessages($map);
        //获取推荐人数
        foreach($list as $value){
            $user_ids[] = $value['id'];
        }
        if(empty($user_ids)){
            $num = false;
        }else{
            $num = $user->getNum($user_ids);
        }
        if($num){
            foreach($list as $key=>$val){
                $list[$key]['referee_num'] = 0;
                foreach($num as $item){
                    if($item['id'] == $val['id']){
                        $list[$key]['referee_num'] += 1;
                    }
                }
            }
        }else{
            foreach($list as $key=>$val) {
                $list[$key]['referee_num'] = 0;
            }
        }
        $this->assign('list',$list);
        $this->page = $p->show();
        $this->display();
    }

    /**加盟商升级**/
//    public function upGrade(){
//        $user = D('User');
//        if(IS_POST){
//            //首先查询提交的信息是否正确
//            $refer['user_name'] = $_POST['user_name'];
//            $refer['old_class'] = $_POST['old_class'];
//            $refer['new_class'] = $_POST['new_class'];
//            $refer['money'] = $_POST['money'];
//            $check = $user->checkMessage(array('user_name'=>$_POST['user_name']));
//            if($check){
//                if($check['rank_id'] != $_POST['old_class']){
//                    echo "<script>alert('请填写正确的加盟商等级!');</script>";
//                    $this->assign('refer',$refer);
//                }else{
//                    //更新该加盟商信息
//                    $data['rank_id'] = $_POST['new_class'];
//                    $update_message = $user->updateMessage(array('user_name'=>$_POST['user_name']),$data);
//                    $user->where(array('user_name'=>$_POST['user_name']))->setInc('join_fee',$_POST['money']);
//                }
//            }else{
//                echo "<script>alert('请填写正确的加盟商信息!');</script>";
//                $this->assign('refer',$refer);
//
//            }
//        }
//        $this->display();
//    }

    /**兑换中心详情**/
    public function companyDetails(){
        $company = D('ApplyCompany');
        $userObj = D("User");
        $users = $userObj->select();
        $map['status'] = 2;
        $p = getpage($company,$map,20);
        $list = $company->getMessages($map);
        foreach ($list as $k=>$item) {
            $list[$k]['share_num'] = 0;
            foreach ($users as $user) {
                if ($item['apply_id'] == $user['id']){
                    $list[$k]['real_name'] = $user['real_name'];
                }
                if ($user['service_number'] == $item['id']){
                    $list[$k]['share_num'] += 1;
                }
            }
        }
        $this->assign('list',$list);
        $this->page = $p->show();
        $this->display();
    }

    /**兑换中心审批**/
    public function companyCheck(){
        $company = D('ApplyCompany');
        if(IS_POST){
            if($_POST['type'] == 1){
                $users = D('User');
                //首先获取所有的下级被推荐人的id(除去已申请服务中心)
                $refer[] = $_POST['user_id'];
                $users->getRecommendId($refer,array());
                $refereed_ids = $users->allRecommendId;
                //更新其他人数据，添加所属服务中心标志
                if(!empty($refereed_ids)) {
                    $condition['id'] = array('in', $refereed_ids);
                    $data = array('service_number' => $_POST['id']);
                    $update_others = $users->updateMessage($condition, $data);
                    if(!$update_others){
                        $data['code'] =3;//下级人员所属的兑换中心标识更新失败
                    }
                }
                //更新个人的信息(包括用户信息及服务中心信息)
                $update_service_message = $company->updateMessage(array('id'=>$_POST['id']),array('status'=>2,'auditor'=>$_SESSION['admin_id'],'handle_time'=>time()));
                $update_info = array(
                    'is_service' => 1,
                    'service_number' => $_POST['id'],
                );
                $update_user_message = $users->updateMessage($_POST['user_id'], $update_info);
                if($update_user_message && $update_service_message){
                    /*存在上下级关系的情况*/
                    //更新服务中心之间的的直属关系，首先查询出直属下级
                    $users->getUnderService($refer, array());
                    $data["user"] = $users;
                    $service_ids = $users->lowerService;
                    if(!empty($service_ids)) {
                        //更新所属上级服务中心字段值
                        $conditions['id'] = array('in', $service_ids);
                        $update_data = array('superior_number' => $_POST['id']);
                        $update_other_service = $company->updateMessage($conditions, $update_data);
                        if ($update_other_service) {
                            $data['code'] = 1;
                        }else{
                            $data['code'] = 2;//下级兑换中心直属中心更新失败
                        }
                    }else{
                        $data['code'] = 1;
                    }
                }else{
                    $data['code'] = 4;
                }
                $this->ajaxReturn($data,'JSON');
            }
            if($_POST['submit'] == 'submit'){
                //更新状态
                $update_status = $company->updateMessage(array('id'=>$_POST['id']),array('status'=>3));
                if($update_status){
                    //将数据存进数据
                    $data['rec_id'] = $_POST['user_id'];
                    $data['title'] = $_POST['title'];
                    $data['content'] = $_POST['content'];
                    $data['type'] = 1;
                    $data['admin'] = $_SESSION['admin_id'];
                    $notice = M('daili_notice')->add($data);
                    if($notice){
                        $this->success("驳回邮件已发送！",U('admin/joining/companyCheck'));
                    }else{
                        $this->error('邮件发送失败！',U('admin/joining/companyCheck'));
                    }
                }else{
                    $this->display();
                    $this->error('审核失败！',U('admin/joining/companyCheck'));
                }
            }
        }
        $map['status'] = 1;
        $p = getpage($company,$map,20);
        $list = $company->getMessages($map);
        $this->assign('list',$list);
        $this->page = $p->show();
        $this->display();
    }

    /**查询推荐人信息**/
    public function getReferee(){
        if(IS_POST){
            $user = D('user');
            $user_message = $user->getMessage(array('tel'=>$_POST['mobile']));
            if(!empty($user_message)){
                $data['real_name'] = $user_message['real_name'];
                $data['referee_id'] = $user_message['id'];
                $data['service_number'] = $user_message['service_number'];
                $data['code'] = 1;
            }else{
                $data['code'] = 2;
            }
            $this->ajaxReturn($data,'JSON');
        }
    }
}