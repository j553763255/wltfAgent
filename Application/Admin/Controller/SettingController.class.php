<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\CommondController;
use Think\Model;
use Think\Upload;

class SettingController extends CommondController
{
    public function auditsett()
    {
        $changeid = $_POST['changeid'];
        $ct = D('daili_agentexamine')->where(array('agentexamine_id' => $changeid))->select();
        $ct['agentexamine_task'] = $_POST['changetext'];
        if ($ct['agentexamine_task'] != '') {
            $time['agentexamine_time'] = date('Y-m-d H:i:s', time());//获取当前时间
            $ip['agentexamine_ip'] = $_SERVER['REMOTE_ADDR'];//当前IP
            D('daili_agentexamine')->where(array('agentexamine_id' => $changeid))->save($time);
            D('daili_agentexamine')->where(array('agentexamine_id' => $changeid))->save($ct);
            D('daili_agentexamine')->where(array('agentexamine_id' => $changeid))->save($ip);
        }
        $this->ajaxReturn();//必须要返回
    }

    public function auditsettings()
    {

        $id = $_POST['agentexamine_id'];
        // $name = $_POST['agentexamine_name'];
        if (isset($_POST['submit'])) {
            $changetext = D('daili_agentexamine')->where(array('agentexamine_id' => $id))->find();

        }
        $this->assign('changetext', $changetext);
        $this->display();
    }

    public function fxset()
    {
        $rank = M('daili_rank');
        $users = M("daili_user");
        $ratios = M("daili_ratio");
        $rankobj = $rank->select();
        if (IS_POST) {
            if ($_POST['submit']=="dailiRatio"){
                $id = $_POST['id'];
                $data['need_money'] = $_POST['need_money'];
                $data['transfer_fee'] = $_POST['transfer_fee'];
                $data['referee_bouns'] = $_POST['referee_bouns'];
                $data['bouns_dot'] = $_POST['bouns_dot'];
                $data['service_ratio'] = $_POST['service_ratio'];
                $data['limit_people'] = $_POST['limit_people'];
                $save_rank = D('daili_rank')->where('id=' . $id)->save($data);
                if ($save_rank) {
                    $this->success("修改成功！", U("admin/setting/fxset"));
                } else {
                    $this->error("修改失败！", U("admin/setting/fxset"));
                }
            }
            if ($_POST['submit']=="allRatio"){
                $sql = null;
                foreach ($_POST['value'] as $k=>$item) {
                     $sql[$k] = M("daili_ratio")->where("id=".$k)->setField("value",$item);
                }
                if (in_array("1",$sql)){
                    $this->success("修改成功！");
                }else{
                    $this->error("修改失败！");
                }
            }
        }
        $ratio = $ratios->select();
        foreach ($ratio as $k=>$item) {
            if ($item['id'] == 1){
                $allDot = $item['value'];
            }
            if ($item['id'] == 2){
                $allMoney = $item['value'];
            }
        }
        $payRed = array(
            1=>array("rank_name"=>"城市代理","num"=>0,"money"=>0,"redDot"=>0),
            2=>array("rank_name"=>"区域代理","num"=>0,"money"=>0,"redDot"=>0),
            3=>array("rank_name"=>"地区代理","num"=>0,"money"=>0,"redDot"=>0)
        );
        $user = $users->select();
        foreach ($user as $item) {
            if ($item['rank_id'] == 1){
                $payRed[1]['num'] += 1;
            }elseif($item['rank_id'] == 2){
                $payRed[2]['num'] += 1;
            }elseif ($item['rank_id'] == 3){
                $payRed[3]['num'] += 1;
            }
        }
        foreach ($rankobj as $item) {
            if ($item['id'] == 1){
                $payRed[1]['redDot'] = $item['bouns_dot'];
                $payRed[1]['money'] = floor($allMoney / $allDot * $item['bouns_dot'] * 100) / 100;
            }elseif($item['id'] == 2){
                $payRed[2]['redDot'] = $item['bouns_dot'];
                $payRed[2]['money'] = floor($allMoney / $allDot * $item['bouns_dot'] * 100) / 100;
            }elseif($item['id'] == 3){
                $payRed[3]['redDot'] = $item['bouns_dot'];
                $payRed[3]['money'] = floor($allMoney / $allDot * $item['bouns_dot'] * 100) / 100;
            }
        }
        $this->assign("payRed",$payRed);
        $this->assign("ratio",$ratio);
        $this->assign('rank', $rankobj);
        $this->display();
    }//奖金设置

    /**分红发放**/
    public function payDividend()
    {
        if (empty($_POST['money'])){
            $gameMoney = 1000000;
        }else{
            $gameMoney = $_POST['money'];
        }                                        // 总用户充值金额
        $users = D("User");
        $Dividends = $users->getAllDividend();      //总分红点
        if(empty($_POST['dividendRatio'])){
            $dividendRatio = 0.5;
        }else{
            $dividendRatio = $_POST['dividendRatio'] / 100; //加盟商分红比例
        }
        if (empty($_POST['payRatio'])){
            $payRatio = 0.5;
        }else{
            $payRatio = $_POST['payRatio'] / 100;      //拨付比例
        }
        $dividendValue  = $gameMoney *  $dividendRatio * $payRatio / $Dividends;  //分红点价值
        
        $data = $users->payDividend($dividendValue);
        
        $this->ajaxReturn($data, "json");
    }

    /**代理设置**/
    public function integralset()
    {
        if (IS_POST) {
            $id = $_POST['id'];
            $data['rank_name'] = $_POST['rank_name'];
            $data['need_money'] = $_POST['need_money'];
            $data['cash_fee'] = $_POST['cash_fee'];
            $data['transfer_fee'] = $_POST['transfer_fee'];
            $data['ratio'] = $_POST['ratio'];
            $data['limit_people'] = $_POST['limit_people'];
            $data['service_percent'] = $_POST['service_percent'];
            $data['is_subsidy'] = $_POST['is_subsidy'];
            $data['subsidy_value'] = $_POST['subsidy_value'];
            $data['referee_bouns'] = $_POST['referee_bouns'];

            $sql = D("daili_rank")->where("id=" . $id)->save($data);
//            $data2['shop_type'] = $_POST['shop_type'];
//            $data2['shop_size'] = $_POST['shop_size'];
//            $data2['fx_piece_agent'] = $_POST['fx_piece_agent'];
//            $data2['fx_area_agent'] = $_POST['fx_area_agent'];
//            $data2['fx_city_agent'] = $_POST['fx_city_agent'];
//            $sql2 = D("daili_company_tiaojian")->where("id=" . $id)->save($data2);
//            if ($sql || $sql2){
            if ($sql){
                $this->success("修改成功！",U("admin/setting/integralset"));
            }else{
                $this->error("修改失败！");
            }
        }
        $ranks = D("daili_rank")->select();
        $business = array();
        $agent = array();
        foreach ($ranks as $k => $rank) {
            if ($rank['system'] == 1) {
                $business[$k]["id"] = $rank['id'];
                $business[$k]["rank_name"] = $rank['rank_name'];
                $business[$k]["need_money"] = $rank['need_money'];
                $business[$k]["cash_fee"] = $rank['cash_fee'];
                $business[$k]['transfer_fee'] = $rank['transfer_fee'];
                $business[$k]['referee_bouns'] = $rank['referee_bouns'];
            }
            if ($rank['system'] == 2) {
                $agent[$k]["id"] = $rank['id'];
                $agent[$k]["rank_name"] = $rank['rank_name'];
                $agent[$k]["need_money"] = $rank['need_money'];
                $agent[$k]['service_ratio'] = $rank['service_ratio'];
                $agent[$k]["cash_fee"] = $rank['cash_fee'];
                $agent[$k]['transfer_fee'] = $rank['transfer_fee'];
                $agent[$k]['ratio'] = $rank['ratio'];
                $agent[$k]['limit_people'] = $rank['limit_people'];
                $agent[$k]['is_subsidy'] = $rank['is_subsidy'];
                $agent[$k]['subsidy_value'] = $rank['subsidy_value'];
                $agent[$k]['referee_bouns'] = $rank['referee_bouns'];
            }
        }
        $this->assign("business", $business);
        $this->assign("agent", $agent);
        $this->display();
    }//代理设置

    /**公告管理**/
    public function notice()
    {
        if (IS_POST){
            if ($_POST['id']!=null){
                $id=$_POST['id'];
                $data['title']=$_POST['title'];
                $data['content']=$_POST['content'];
                $data['modify_time']=time();
                $data['modify_admin_id']=session('admin_id');
                $sql=D("daili_notice")->where("id=".$id)->save($data);
                if ($sql){
                    D("daili_user")->setField('alert_num',0);
                    $this->success("公告修改成功！",U("admin/setting/notice"));
                }else{
                    $this->error("发布修改失败！");
                }
            }else{
                $data['title']=$_POST['title'];
                $data['content']=$_POST['content'];
                $data['create_time']=time();
                $data['admin_id']=session('admin_id');
                $data['type'] = 1;
                $sql=D("daili_notice")->add($data);
                if ($sql){
                    $where["alert_num"]=array("neq",0);
                    if (D("daili_user")->where($where)->setField("alert_num",0)){
                        $this->success("公告发布成功！",U("admin/setting/notice"));
                    }
                }else{
                    $this->error("发布公告失败！");
                }
            }
        }
        $notices=D("daili_notice")->order("id desc")->select();
        $admins=D("daili_admin")->select();
        foreach ($notices as $k=>$notice) {
            foreach ($admins as $admin) {
                if ($notice['admin_id']==$admin['admin_id']){
                    $notices[$k]["admin_mobile"]=$admin['mobile'];
                }
                if ($notice['modify_admin_id']==$admin['admin_id']){
                    $notices[$k]["modify_admin_mobile"]=$admin['mobile'];
                }
            }
        }
        $this->assign("notice",$notices);
        $this->display();
    }//公告管理

    public function delNotice()
    {
        $notice_id=$_POST['notice_id'];
        $sql=D("daili_notice")->where("id=".$notice_id)->delete();
        if ($sql){
            $sql = true;
        }else{
            $sql = false;
        }
        $this->ajaxReturn($sql);
    }

    public function tasksetting()
    {
        $maps=null;
        if ($_GET['type']=="delRankTask"){
            $taskArr = explode(",",$_GET['default_task']);
            foreach ($taskArr as $k=>$item) {
                if ($item==$_GET['task_id']){
                    unset($taskArr[$k]);
                }
            }
            $default_task =  implode(",", $taskArr);
            $delRankTaskSql=M("daili_rank")->where("id=".$_GET['rank_id'])->setField("default_task",$default_task);
            if ($delRankTaskSql){
                $this->success("删除代理默认任务成功！");
            }else{
                $this->error("删除代理任务失败！");
            }
        }
        if (IS_POST){
            if ($_POST['submit']=="taskTerm"){//查询
                $maps['task_trem']=$_POST['taskTerm'];
            }
            if ($_POST['submit']=="modifyTask"){//修改
                $modifyData['num']=$_POST['typeNum'];
                $modifyData['price']=$_POST['price'];
                $modifyData['ratio']=$_POST['ratio'];
                $modifySql=D("daili_task")->where("id=".$_POST['task_id'])->save($modifyData);
                if ($modifySql){
                    $this->success("修改成功！");
                }else{
                    $this->error("修改失败！");
                }
            }
            if ($_POST['submit']=="addTask"){//添加
                if ($_POST['num']==null){
                    $this->error("参数错误！");
                }else{
                    $termArr=explode(',',$_POST['taskTerm']);
                    $addData['task_term']=$termArr[0];
                    $addData['task_name']=$termArr[1];
                    if ($termArr[0]==4){
                        $addData['num']=$_POST['people'].",".$_POST['num'];
                    }
                    $addData['num']=$_POST['num'];
                    $addData['ratio']=$_POST['ratio'];
                    $addData['price']=$_POST['price'];
                    $addSql=D("daili_task")->add($addData);
                    if ($addSql){
                        $this->success("任务添加成功！");
                    }else{
                        $this->error("任务添加失败！");
                    }
                }
            }
            if ($_POST['submit']=="addRankTask"){
                $rank=D("daili_rank");
                $old_task=$_POST['oldRank'];
                $rank_id=$_POST['rankId'];
                $task_id=$_POST['taskId'];
                $data['default_task']=$old_task.",".$task_id;
                $SQL=$rank->where("id=".$rank_id)->save($data);
                if ($SQL){
                    $this->success("添加任务成功！");
                }else{
                    $this->error("添加任务失败！");
                }
            }
            if ($_POST['submit']=="addUserTask"){
                $user_task=D("daili_user_task");
                $oldTask=$user_task->where("user_id=".$_POST['userId'])->getField('task_id',true);
                if (in_array($_POST['taskId'],$oldTask)){
                    $this->error("用户已拥有该任务");
                }else{
                    $user_task_data['user_id']=$_POST['userId'];
                    $user_task_data['task_id']=$_POST['taskId'];
                    $addUserTaskSql=$user_task->add($user_task_data);
                    if ($addUserTaskSql){
                        $this->success("任务添加成功！");
                    }else{
                        $this->error("任务添加失败！");
                    }
                }
            }
        }
        $tasks=D("daili_task");
        $taskTerm=D("daili_task_term")->select();
        $p = getpage($tasks, $maps, 10);
        $task = $tasks->field(true)->where($maps)->order("id desc")->select();
        foreach ($task as $key=>$item) {
            foreach ($taskTerm as $value) {
                if ($item['task']==$value['id']){
                    $task[$key]['task_name']=$value['task_name'];
                }
            }
        }
        $this->list = $task;
        $this->page = $p->show();
        $this->assign("taskTerm",$taskTerm);
        $rank=D("daili_rank");
        $allTask=$tasks->select();
        $ranks=$rank->select();
        foreach ($ranks as $k=>$val) {
            $taskArr=explode(",",$val['default_task']);
            foreach ($allTask as $key=>$item) {
                if (in_array($item['id'],$taskArr)){
                    $ranks[$k]['task'][$key]=$item;
                }
            }
        }
        $this->assign("ranks",$ranks);
        /**用户任务分配**/
        $users=D("daili_user");
        $user_tasks=D("daili_user_task");
        $user=$users->select();
        $user_task=$user_tasks->select();
        foreach ($user as $key=>$value) {
            foreach ($user_task as $val){
                if ($value['id']==$val['user_id']){
                    foreach ($allTask as $k=>$v) {
                        if ($val['task_id']==$v['id']){
                            $user[$key]['task'][$k]=$v;
                        }
                    }
                }
            }
        }
        $this->assign("user",$user);
        $this->display();
    }//任务设置

    public function delTaskAjax()
    {
        if ($_POST['type']=="all"){
            $delSql=D("daili_task")->where("id=".$_POST['task_id'])->delete();
            $this->ajaxReturn($delSql);
        }elseif($_POST['type']=="rank"){
            $tasks=M("daili_task");
            $task_id=explode(",",$_POST['old_task']);
            $maps['id']=array("in",$task_id);
            $task=$tasks->where($maps)->select();
            $this->ajaxReturn($task);
        }
    }
    
    public function depositset()
    {
        if (isset($_POST['submit_city1'])) {
            //  print_r($_POST);
            //Array ( [city1_chairman] => 1 [city1_specialize] => 2 [city1_executive] => 2 [city1_ministerial] => 3 [city1_submit] => )
            if ($_POST['deposit_city1']) {

                $data['deposit_city'] = $_POST['deposit_city1'];

                // D('daili_agentexamine')->where (array('agentexamine_id' => $changeid))->save($ip);
                D('daili_agentexamine')->where(array())->save($data);//加个判断在一级市范围内
                echo "<script>  alert(\"设置成功！\"); </script>";
            } else   echo "<script>  alert(\"设置失败！\"); </script>";
        }
        $this->display();
    }//一级代理申请押金设置

    public function hierarchicalauthority()
    {

        if (isset($_POST['submit'])) {

            $data['role_id'] = $_POST['position'];//确定是什么职位

            $data['authority1'] = $_POST['authority1'];
            $data['authority2'] = $_POST['authority2'];
            $data['authority3'] = $_POST['authority3'];
            $data['authority4'] = $_POST['authority4'];
            $data['authority5'] = $_POST['authority5'];
            $data['authority6'] = $_POST['authority6'];
            $data['authority7'] = $_POST['authority7'];
            $data['authority8'] = $_POST['authority8'];//确定职位的应该有的权限

            if (!$data['authority1']) {
                $data['authority1'] = 0;
            }
            if (!$data['authority2']) {
                $data['authority2'] = 0;
            }
            if (!$data['authority3']) {
                $data['authority3'] = 0;
            }
            if (!$data['authority4']) {
                $data['authority4'] = 0;
            }
            if (!$data['authority5']) {
                $data['authority5'] = 0;
            }
            if (!$data['authority6']) {
                $data['authority6'] = 0;
            }
            if (!$data['authority7']) {
                $data['authority7'] = 0;
            }
            if (!$data['authority8']) {
                $data['authority8'] = 0;
            }//对于没有的权限标0
            if ($data['role_id']) {

                D('daili_agentexamine')->where(array('role_id' => $data['role_id']))->save($data);//保存权限
                echo "<script>  alert(\"设置成功！\"); </script>";
            } else
                echo "<script>  alert(\"设置失败！\"); </script>";


        }
        $this->display();
    }//老的权限设置

    public function upload()
    {
        if (isset($_POST['submit'])) {
            import('Think.Verify.Upload');
            $picture = new Upload(); // 实例化上传类
            $picture->maxSize = 3145728;// 设置附件上传大小
            $picture->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $picture->rootPath = './Uploads/';// 设置附件上传根目录

            $picture->savepath = ''; // 设置附件上传（子）目录
            $info = $picture->upload();// 上传文件
            if (!($info['file1'] && $info['file2'])) {
                echo "<script>alert('请上传文件')</script>";
            }
            $fr = '/Uploads/';//正确 http://localhost/Uploads/2016-11-14/582963ff2640f.jpg
            $user = M('daili_admin');// 实例化User对象
            $upload_role_id_address = array('certificate_address' => $fr . $info['file2']['savepath'] . $info['file2']['savename'], 'contract_address' => $fr . $info['file1']['savepath'] . $info['file1']['savename']);
            $name = $_SESSION['adminid'];//获取SESSION的账户名 $_SESSION['adminid'],$_SESSION['pwd'],$_SESSION['loginkey'],
            $where['admin'] = $name;//判断条件实例化
            $user->where($where)->setField($upload_role_id_address);
            $image1 = new \Think\Image();
            $image2 = new \Think\Image();
            $image1->open('./Uploads/' . $info['file1']['savepath'] . $info['file1']['savename']);
            $image2->open('./Uploads/' . $info['file2']['savepath'] . $info['file2']['savename']);

            $image1->thumb(800, 600, \Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/contract.jpg');//固定缩略图
            $image2->thumb(800, 600, \Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/certificate.jpg');//固定缩略图
            echo "<script>alert('信息上传成功')</script>";
            $addr1 = $fr . 'contract.jpg';
            $addr2 = $fr . 'certificate.jpg';
            $this->assign('addr1', $addr1);
            $this->assign('addr2', $addr2);
        }

        $this->display();
    }

    /**积分兑换设置**/
    public function exchange(){
        if(!empty($_GET['id'])){
            $point = M('daili_point_exchange')->where(array('id'=>$_GET['id']))->find();
            $this->ajaxReturn($point,'JSON');
        }
        if(IS_POST){
            if($_POST['operate'] == 'edit'){//修改设置
                if(empty($_POST['need_points']) || empty($_POST['award'])){
                    $this->error('修改失败,请将数据填写完整!', U('admin/setting/exchange'));
                }else{
                    $update_data = array(
                        'daili_class' => $_POST['daili_class'],
                        'exchange_point' => $_POST['need_points'],
                        'award' => $_POST['award'],
                        'type' => $_POST['type']
                    );
                    $result = M('daili_point_exchange')->where(array('id'=>$_POST['set_id']))->save($update_data);
                    if($result){
                        $this->success('修改成功！',U('admin/setting/exchange'));
                    }
                }
            }
            if($_POST['operate'] == 'del'){//删除
                $result = M('daili_point_exchange')->where(array('id'=>$_POST['id']))->delete();
                if($result){
                    $data = array('code'=>1);
                }else{
                    $data = array('code'=>2);
                }
                $this->ajaxReturn($data,'JSON');
            }
            if($_POST['operate'] == 'add'){//新增
                if(empty($_POST['need_points']) || empty($_POST['award'])){
                    $this->error('新增失败,请将数据填写完整!', U('admin/setting/exchange'));
                }else{
                    $add_data = array(
                        'daili_class' => $_POST['daili_class'],
                        'exchange_point' => $_POST['need_points'],
                        'award' => $_POST['award'],
                        'type' => $_POST['type']
                    );
                    $result = M('daili_point_exchange')->add($add_data);
                    if($result){
                        $this->success('新增成功！',U('admin/setting/exchange'));
                    }else{
                        $this->error('新增失败！',U('admin/setting/exchange'));
                    }
                }
            }
        }
        $result = M('daili_point_exchange')->select();
        $this->assign('list',$result);
        $this->display();
    }
/**
 * 兑换中心设置
*/
    public function MoneyChangerSetting()
    {
        $exchange = D("company");
        if (IS_POST){
            $result = $exchange->modifyRatio($_POST);
            if($result){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
        }
        $exchangeRatio = $exchange->getRatio();
        $this->assign("ratio",$exchangeRatio);
        $this->display();
    }

    public function CompanyMallSetting()
    {
        $goodsObj = D("CompanyGoods");
        $goods = $goodsObj->getMall();
        $this->assign("goods",$goods);
        $this->display();
    }

    public function DividendSetting()
    {
//        $gameMoney = 1000000; // 总用户充值金额
//        $users = D("User");
//        $Dividends      = $users->getAllDividend(); //总分红点
//        $userNum1       = $users->getUserNum(1);    //一级加盟商人数
//        $userNum2       = $users->getUserNum(2);    //二级加盟商人数
//        $userNum3       = $users->getUserNum(3);    //三级加盟商人数
//        $dividendRatio  = 0.5; //加盟商分红比例
//        $payRatio       = 0.5; //拨付比例
//        $dividendValue  = floor($gameMoney *  $dividendRatio * $payRatio / $Dividends * 100) / 100;  //分红点价值
//        $average1       = floor($users->getAllDividend(1) * $dividendValue / $userNum1 * 100) / 100; //一级加盟商平均分红
//        $average2       = floor($users->getAllDividend(2) * $dividendValue / $userNum2 * 100) / 100; //二级加盟商平均分红
//        $average3       = floor($users->getAllDividend(3) * $dividendValue / $userNum3 * 100) / 100; //三级加盟商平均分红
//        $data = array(
//            "gameMoney"      =>  $gameMoney,
//            "Dividends"      =>  $Dividends,
//            "userNum1"       =>  $userNum1,
//            "userNum2"       =>  $userNum2,
//            "userNum3"       =>  $userNum3,
//            "dividendRatio"  =>  $dividendRatio * 100,
//            "payRatio"       =>  $payRatio * 100,
//            "average1"       =>  $average1,
//            "average2"       =>  $average2,
//            "average3"       =>  $average3,
//            "dividendValue"  =>  $dividendValue,
//        );
//        $this->assign('data',$data);
        if(!empty($_POST['bonus'])){
            $bonus = $_POST['bonus'];
        }else{
            $bonus = 0;
        }
        $user = D('user');
        $result = $user->getList($bonus);
        $this->assign('lists',$result['pay_reward']);
        $this->assign('total_money',$result['total_money']);
        $this->assign('bonus',$result['bonus']);
        $this->display();
    }

    public function DividendMeasure()
    {
        $gameMoney = $_POST['money']; // 总用户充值金额
        $users = D("User");
        $Dividends      = $users->getAllDividend();      //总分红点
        $userNum1       = $users->getUserNum(1);         //一级加盟商人数
        $userNum2       = $users->getUserNum(2);         //二级加盟商人数
        $userNum3       = $users->getUserNum(3);         //三级加盟商人数
        $dividendRatio  = $_POST['dividendRatio'] / 100; //加盟商分红比例
        $payRatio       = $_POST['payRatio'] / 100;      //拨付比例
        $dividendValue  = floor($gameMoney *  $dividendRatio * $payRatio / $Dividends * 100) / 100;  //分红点价值
        $average1       = floor($users->getAllDividend(1) * $dividendValue / $userNum1 * 100) / 100; //一级加盟商平均分红
        $average2       = floor($users->getAllDividend(2) * $dividendValue / $userNum2 * 100) / 100; //二级加盟商平均分红
        $average3       = floor($users->getAllDividend(3) * $dividendValue / $userNum3 * 100) / 100; //三级加盟商平均分红
        $data = array(
            "average1"       =>  $average1,
            "average2"       =>  $average2,
            "average3"       =>  $average3,
            "dividendValue"  =>  $dividendValue,
        );
        $this->ajaxReturn($data);
    }

    public function addMall()
    {
        $ExchangeGoods = D("ExchangeGoods");
        if(IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功
                foreach($info as $file){
                    $data['goods_pic'] = "/Uploads/" . $file['savepath'] . $file['savename'];
                }
            }
            $data['name']           = $_POST['goodsName'];
            $data['need_pionts']    = $_POST['need_pionts'];
            $data['create_time']    = time();
            $data['modify_ip']      = $_SERVER["REMOTE_ADDR"]; ;
            $data['modify_id']      = session('admin_id');
            $data['type']           = $_POST['type'];
            $data['qty']            = $_POST['qty'];
            $data['introduction']   = $_POST['introduction'];
            if ($ExchangeGoods->addMall($data)){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
        }
        $this->display();
    }

    public function modifyGoods()
    {
        $goodsObj = D("ExchangeGoods");
        if (IS_AJAX){
            $id = $_POST['id'];
            $goods = $goodsObj->getMall($id);
            $this->ajaxReturn($goods);
        }
        if (IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            foreach($info as $file){
                $data['goods_pic'] = "/Uploads/" . $file['savepath'] . $file['savename'];
            }
            $data['id'] = $_POST['goodsId'];
            $data['name']           = $_POST['goodsName'];
            $data['need_pionts']    = $_POST['need_pionts'];
            $data['create_time']    = time();
            $data['modify_ip']      = $_SERVER["REMOTE_ADDR"]; ;
            $data['modify_id']      = session('admin_id');
            $data['type']           = $_POST['type'];
            $data['qty']            = $_POST['qty'];
            $data['introduction']   = $_POST['introduction'];
            if ($goodsObj->modifyGoods($data)){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
        }
    }

    /**手动分红查询**/
    public function handOperation(){
        if(IS_POST){
            if(!empty($_POST['start_time']) && !empty($_POST['end_time'])){
                $map['create_time'] = array(array('gt',strtotime($_POST['start_time'])),array('lt',strtotime($_POST['end_time'])));
            }elseif(!empty($_POST['start_time']) && empty($_POST['end_time'])){
                $map['create_time'] = array('gt',strtotime($_POST['start_time']));
            }elseif(empty($_POST['start_time']) && !empty($_POST['end_time'])){
                $map['create_time'] = array('lt',strtotime($_POST['end_time']));
            }
            //获取当前所要的详细信息
            $user = D('user');
            $data = $user->getPayMessage($map);
            if($data) {
                $data['code'] = 1;
                if ($data['dividend'] == 0) {
                    $data['dividend_value'] = 0;
                } else {
                    $data['dividend_value'] = floor($_POST['pay_money'] / $data['dividend']);
                }
            }else{
                $data['code'] = 2;
            }
            $data['pay_money'] = $_POST['pay_money'];
        }
        $this->ajaxReturn($data,'JSON');
    }

    /**手动分红拨付**/
    public function dividendPay(){
        if(IS_POST){
            $pay_result = D('user')->updatePay(array('id'=>array('in',$_POST['result'])),$_POST['dividend_value']);
            if($pay_result == "success"){
                $data['code'] = 1;
            }else{
                $data['code'] = 2;
                $data['error'] = $pay_result;
            }
        }
        $this->ajaxReturn($data,'JSON');
    }

    /**新版分红发放**/
    public function payBonus(){
        if(IS_POST){
            if($_POST['bonus'] != 0){
                $pay_result = D('user')->payIds($_POST['bonus']);
                if($pay_result == "success"){
                    $data['code'] = 1;
                }else{
                    $data['code'] = 2;
                    $data['error'] = $pay_result;
                }
            }else{
                $data['code'] = 3;
            }
            $this->ajaxReturn($data,'JSON');
        }
    }

}