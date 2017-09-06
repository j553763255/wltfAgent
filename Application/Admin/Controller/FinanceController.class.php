<?php
/**
 * 财务管理
 * Created by PhpStorm.
 * User: zhouhua
 * Date: 2017/2/25
 * Time: 10:47
 */
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\CommondController;
class FinanceController extends CommondController{

    /**所有拨付记录**/
    public function payAll(){
        $userObj = D("User");
        $users = $userObj->select();
        $flowObj = D("CashFlow");
        $maps['type'] = array("in",array(1,2,4,10));
        $flowsIn = $flowObj->where($maps)->select();//收入
        $income = 0;
        $todayIncome = 0;
        foreach ($flowsIn as $k=>$val) {
            if($val['type'] == 1 || $val['type'] == 2){
                $income += $val['fee'];
                if ($val['create_time'] > strtotime(date('Y-m-d'))) {
                    $todayIncome += $val['fee'];
                }
            }else{
                $income +=$val['money'];
                if ($val['create_time'] > strtotime(date('Y-m-d'))) {
                    $todayIncome += $val['money'];
                }
            }
        }
        $map['type'] = array("in",array(3,5,7,8,9));
        $flowsCount = $flowObj->where($map)->select();//拨出
        $total = 0;
        $today = 0;
        foreach ($flowsCount as $k=>$flow) {
            $total += $flow['money'];
            if ($flow['create_time'] > strtotime(date('Y-m-d'))){
                $today += $flow['money'];
            }
        }
        $p = getpage($flowObj,$map,20);
        $flows = $flowObj->where($map)->select();//拨出
        foreach ($flows as $k=>$flow) {
            foreach ($users as $user) {
                if ($flow['rec_id'] == $user['id']){
                    $flows[$k]['user_name'] = $user['real_name'];
                }
            }
            if ($flow['type'] == 1){$flows[$k]['type'] = "转账";}
            if ($flow['type'] == 2){$flows[$k]['type'] = "提现";}
            if ($flow['type'] == 3){$flows[$k]['type'] = "项目分红";}
            if ($flow['type'] == 4){$flows[$k]['type'] = "加盟缴费";}
            if ($flow['type'] == 5){$flows[$k]['type'] = "任务奖励";}
            if ($flow['type'] == 6){$flows[$k]['type'] = "手动操作";}
            if ($flow['type'] == 7){$flows[$k]['type'] = "服务费";}
            if ($flow['type'] == 8){$flows[$k]['type'] = "直推奖励";}
            if ($flow['type'] == 9){$flows[$k]['type'] = "分销奖励";}
        }
        $this->assign("list",$flows);
        $this->page = $p->show();
        $this->assign('total',$total);//总拨付金额
        $this->assign('today',$today);//今日拨付金额
        $this->assign('income',$income);//总收入金额
        $this->assign('todayIncome',$todayIncome);//今日收入金额
        $this->assign('ratio',floor($total / $income * 10000) / 100);//总拨比
        $this->assign('todayRatio',floor( $today / $todayIncome* 10000) / 100);//今日拨比
        $this->display();
    }

    /**今日拨付记录**/
    public function currentPay(){
        $userObj = D("User");
        $users = $userObj->select();
        $flowObj = D("CashFlow");
        $p = getpage($flowObj,$map = array('type'=>array('in','1,2,4')),20);
        $flows = $flowObj->getFlowByType(array(1,2,4));
        $income = 0;
        $todayIncome = 0;
        foreach ($flows as $k=>$flow) {
            $income += $flow['money'];
            if ($flow['create_time'] > strtotime(date('Y-m-d'))) {
                $todayIncome += $flow['money'];
            }
            foreach ($users as $user) {
                if ($flow['rec_id'] == $user['id']){
                    $flows[$k]['user_name'] = $user['real_name'];
                }
            }
            if ($flow['type'] == 1){$flows[$k]['type'] = "转账";}
            if ($flow['type'] == 2){$flows[$k]['type'] = "提现";}
            if ($flow['type'] == 3){$flows[$k]['type'] = "项目分红";}
            if ($flow['type'] == 4){$flows[$k]['type'] = "加盟缴费";}
            if ($flow['type'] == 5){$flows[$k]['type'] = "任务奖励";}
            if ($flow['type'] == 6){$flows[$k]['type'] = "手动操作";}
            if ($flow['type'] == 7){$flows[$k]['type'] = "服务费";}
            if ($flow['type'] == 8){$flows[$k]['type'] = "直推奖励";}
        }
        $this->assign("list",$flows);
        $this->page = $p->show();
        $this->assign('income',-$income);
        $this->assign('todayIncome',-$todayIncome);
        $this->display();
    }

    /**加盟费统计**/
    public function income(){
        $user = D('User');
        $p = getpage($user,$map = array(),20);
        $list = $user->revenue();
        $total = 0;
        if(!empty($list)){
            foreach($list as $val){
                $money[] = $val['money'];
            }
            $total = array_sum($money);
        }
        $this->assign('list',$list);
        $this->assign('total',$total);
        $this->page = $p->show();
        $this->display();
    }

    /**提现详情**/
    public function cashLog(){
        $cash = D('Cash');
        $p = getpage($cash,array(),20);
        $cashDetail = $cash->getInfo();
        //获取总金额
        $total = 0;
        if(!empty($cashDetail)){
            foreach($cashDetail as $value){
                $money[] = $value['money'];
            }
            $total = array_sum($money);
            $count = count($money);
        }
        $this->assign('list',$cashDetail);
        $this->assign('count',$count);
        $this->assign('total',$total);
        $this->page = $p->show();
        $this->display();
    }

    /**今日提现详情**/
    public function currentCash(){
        $cash = D('Cash');
        $start_time = strtotime(date('Y-m-d'));
        $end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
        $map['create_time'] = array(array('egt',$start_time),array('lt',$end_time));
        $p = getpage($cash,$map,20);
        $cashDetail = $cash->getMessages($map);
        //获取总金额
        $total = 0;
        $count = 0;
        if(!empty($cashDetail)){
            foreach($cashDetail as $value){
                $money[] = $value['money'];
            }
            $total = array_sum($money);
            $count = count($money);
        }
        $this->assign('list',$cashDetail);
        $this->assign('count',$count);
        $this->assign('total',$total);
        $this->page = $p->show();
        $this->display();
    }

    /**提现管理**/
    public function cashSet(){
        $cash = D('Cash');
        if(IS_POST){
            if($_POST['operate'] == 'set'){//设置
                if($_POST['type'] == 1){//时间段设置
                    $start_time = strtotime($_POST['start_time']);
                    $end_time = strtotime($_POST['end_time']);
                    if($_POST['status'] == 1) {
                        if (empty($_POST['start_time'])) {
                            $this->error('开始时间不能为空', U('admin/finance/cashSet'));
                        } elseif (!empty($end_time) && ($end_time < $start_time)) {
                            $this->error('结束时间不能小于开始时间', U('admin/finance/cashSet'));
                        } else {
                            $start_time = strtotime($_POST['start_time']);
                            if (empty($_POST['end_time'])) {
                                $end_time = 0;
                            } else {
                                $end_time = strtotime($_POST['end_time']);
                            }
                            //将数据存进表中
                            $update_data1 = array(
                                'value' => $start_time,
                                'status' => $_POST['status']
                            );
                            $update_data2 = array(
                                'value' => $end_time,
                                'status' => $_POST['status']
                            );
                            $update_result1 = D('Set')->updateMessage(array('name' => '提现开始时间'), $update_data1);
                            $update_result2 = D('Set')->updateMessage(array('name' => '提现结束时间'), $update_data2);
                            if($update_result1 && $update_result2){
                            $this->success('设置成功',U('admin/finance/cashSet'));
                            }
                        }
                    }else{
                        //将数据存进表中
                        $update_result1 = D('Set')->updateMessage(array('name' => '提现开始时间'),array('status'=>2));
                        $update_result2 = D('Set')->updateMessage(array('name' => '提现结束时间'),array('status'=>2));
                        if($update_result1 && $update_result2){
                            $this->success('设置成功',U('admin/finance/cashSet'));
                        }
                    }
                }
                if($_POST['type'] == 2){//固定时间设置
                    if($_POST['status'] == 1){
                        $update_message['value'] = $_POST['week'];
                        $update_message['status'] = $_POST['status'];
                        $update_result = D('Set')->updateMessage(array('name'=>'固定提现时间'),$update_message);
                        if($update_result){
                            $this->success('设置成功',U('admin/finance/cashSet'));
                        }
                    }
                }
            }
            if($_POST['operate'] == 'pay'){//打款
                $condition['id'] = $_POST['id'];
                if($_POST['statue'] == 1){//通过
                    $result_update = $cash->updateMessage($condition,array('status'=>2));
                    if($result_update){
                        $data['code'] = 1;
                        $this->ajaxReturn($data,'JSON');
                    }
                }
                if($_POST['statue'] == 2){//拒绝
                    $result_update = $cash->updateMessage($condition,array('status'=>3,'intro'=>$_POST['remarks']));
                    if($result_update){
                        $data['code'] = 1;
                        $this->ajaxReturn($data,'JSON');
                    }
                }
            }
        }

        $map['status'] = 1;
        $p = getpage($cash,$map,10);
        $cashDetail = $cash->getMessages($map);
        //获取总金额
        $total = 0;
        if(!empty($cashDetail)){
            foreach($cashDetail as $value){
                $money[] = $value['money'];
            }
            $total = array_sum($money);
            $count = count($money);
        }
        $status = D('set')->getStatus();
        $this->assign('status',$status);
        $this->assign('list',$cashDetail);
        $this->assign('count',$count);
        $this->assign('total',$total);
        $this->page = $p->show();
        $this->display();
    }
}