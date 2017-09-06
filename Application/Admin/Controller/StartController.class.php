<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\CommondController;

class StartController extends CommondController
{

    public function dailihome()
    {
        session_start();
        $admin = D("daili_admin")->where('admin_id=' . session('admin_id'))->find();
        $role_maps = D('daili_role_maps')->where("role_id=" . $admin['role_id'])->select();
        $menu_id = array();
        foreach ($role_maps as $map) {
            $menu_id[] = $map['menu_id'];
        }
        $where['menu_id'] = array('in', $menu_id);
        $menu = D("daili_admin_menu")->where(array('is_show' => 1))->where($where)->select();
        $this->assign('menuList', $menu);//$menu分配menuList
        $this->display();
    }

    public function dailiHomeAjax()
    {
        $url = str_replace("/index.php/admin/", "", $_POST['url']);
        $menus = D("daili_admin_menu")->select();
        $bread = array();
        $fmenu_uid = null;
        $fmenu_uid2 = null;
        if ($url != "javascript:;") {
            foreach ($menus as $menu) {
                if ($menu['menu_url'] == $url) {
                    $bread[3]["menu_name"] = $menu['menu_name'];
                    $bread[3]["menu_url"] = $menu['menu_url'];
                    $bread[3]["class"] = $menu['class'];
                    $fmenu_uid = $menu['fmenu_id'];
                }
            }
            foreach ($menus as $menu) {
                if ($menu['menu_id'] == $fmenu_uid) {
                    $bread[2]["menu_name"] = $menu['menu_name'];
                    $bread[2]["menu_url"] = $menu['menu_url'];
                    $bread[2]["class"] = $menu['class'];
                    $fmenu_uid2 = $menu['fmenu_id'];
                }
            }
            foreach ($menus as $menu) {
                if ($menu['menu_id'] == $fmenu_uid2) {
                    $bread[1]["menu_name"] = $menu['menu_name'];
                    $bread[1]["menu_url"] = $menu['menu_url'];
                    $bread[1]["class"] = $menu['class'];
                }
            }
        }
        $this->ajaxReturn($bread, 'json');
    }

    public function addrole()
    {
        $role_id = $_GET['role_id'];
        if (IS_POST) {
            $role_map = D('daili_role_maps');
            $menu_id = $_POST['menu_id'];
            $role_ids = $_POST['role_id'];
            foreach ($menu_id as $item) {
                $data['role_id'] = $role_ids;
                $data['menu_id'] = $item;
                $role_map->add($data);
            }
            $this->dailiSuccess('修改成功！', "/index.php/admin/start/addagent");
        }
        $role = D("daili_role")->where('role_id=' . $role_id)->find();
        $menus = D("daili_admin_menu")->order('fmenu_id asc')->select();
        $this->assign('role', $role);
        $this->assign('menus', $menus);
        $this->display();
    }

    public function member()
    {
        $daili = D('daili_user')->select();
        $this->assign('daili', $daili);
        $this->display();
    }

    /**
     *
     */
    public function homepage()
    {
        $admin_id = session('admin_id');
        $users = D("daili_user");
        $admin = D("daili_admin")->where(array('admin_id' => $admin_id))->find();
        $role = D("daili_role")->where("role_id=" . $admin['role_id'])->find();

        $admin['role'] = $role['role_name'];
        $htmlData = array(
            "allMoney" => 0,
            "pay_money" => 0,
            "not_pay" => 0,
            "cash_money" => 0,
            "today_pay" => 0,
            "allUser" => 0,
            "monthUser" => 0,
            "waitUser" => 0,
            "failUser" => 0,
            "dividend" => 0
        );
        //---------代理统计---------//
        $user = $users->select();
        foreach ($user as $item) {
            $htmlData['dividend'] += $item['dividend'];
            if ($item['status'] == 2) {
                $htmlData['allUser'] += 1;//所有有效代理
                if ($item['create_time'] > strtotime(date("Y-m-01"))) {
                    $htmlData['monthUser'] += 1;//本月新增代理
                }
            } elseif ($item['status'] == 1) {
                $htmlData['waitUser'] += 1;//未审核代理
            } elseif ($item['status'] == 3) {
                $htmlData['failUser'] += 1;//未通过代理
            }
        }
        //----------奖金统计----------//
        $flowObj = D("CashFlow");
        $flows = $flowObj->select();
        foreach ($flows as $flow) {
            if (in_array($flow['type'],array(1,2,4))){
                if ($flow['type'] == 1 || $flow['type'] == 2){
                    $htmlData['allMoney'] -= $flow['fee'];
                }elseif($flow['type'] == 4){
                    $htmlData['allMoney'] -=$flow['money'];
                }
            }
            if (in_array($flow['type'],array(3,5,7,8))){
                $htmlData['pay_money'] += $flow['money'];
                if ($flow['create_time'] >  strtotime(date('Y-m-d'))){
                    $htmlData['today_pay'] += $flow['money'];
                }
            }
        }

//        $user = D('User');
//        $list = $user->revenue();
//        if(!empty($list)){
//            foreach($list as $val){
//                $money[] = $val['money'];
//            }
//            $htmlData['allMoney'] = array_sum($money);
//        }
//        $capital_flows = M("daili_capital_flow");
//        $divide_bonuses = M("daili_divide_bonus");
//        $cash_logs = M("daili_cash_log");
//        $capital_flow = $capital_flows->select();
//        $divide_bonus = $divide_bonuses->select();
//        $cash_log = $cash_logs->where("status=2")->select();
//        foreach ($capital_flow as $item) {
//            if ($item['rec_id'] == 1){
//                $htmlData['allMoney'] += $item['money'];//系统总收入
//            }
//            if ($item['pay_id'] == 1){
//                $htmlData['pay_money'] += $item['money'];//立即拨出金额
//            }
//        }
//        foreach ($divide_bonus as $item) {
//            $htmlData['pay_money'] += $item['money'] - $item['balance'];//加上分期部分已拨付金额
//            $htmlData['not_pay'] += $item['balance'];//未拨付分期金额
//            if ($item['pay_times']>0){
//                $htmlData['today_pay'] += $item['balance'] / $item['pay_times'];//今日拨付金额
//            }
//        }
//        foreach ($cash_log as $item) {
//            $htmlData['cash_money'] += $item['money'];//提现金额
//        }
        //----------排行版----------//
        $rankingMaps1['rank_id'] = array("lt", 4);
        $rankingMaps2['rank_id'] = array("gt", 3);
        $dailiRanking = $users->where($rankingMaps1)->order("income desc")->limit(0, 100)->select();
        $yewuRanking = $users->where($rankingMaps2)->order("income desc")->limit(0, 100)->select();
        $this->assign("dailiRanking", $dailiRanking);
        $this->assign("yewuRanking", $yewuRanking);

        $this->assign("htmlData", $htmlData);
        $this->assign('admin', $admin);
        $this->display();
    }

    public function loginout()
    {
        $dladmin = D('daili_admin');
        $dladmin->where(array('admin' => $_SESSION['adminid']))->save('lasttime', time());
        session_start();
        session('adminid', null);
        session('loginkey', null);
//        $this->redirect('退出登陆成功',U('admin/login/index'));
        $this->redirect('admin/login/index');
    }
}
    