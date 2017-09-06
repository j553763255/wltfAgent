<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 19:52
 */
namespace Home\Controller;
use Think\Controller;

session_start();
class SetController extends CommonController{

    /**设置列表**/
    public function setList(){
        $user = D('user');
        $user_message = $user->getMessage(array('id'=>$_SESSION['uid']));
        if($user_message['pay_pwd'] == ""){
            $is_set = 1;
        }else{
            $is_set = 2;
        }
        $this->assign('is_set',$is_set);
        $this->display();
    }

    /**修改密码**/
    public function changePwd(){
        $user = D('user');
        if(IS_POST){
            if($_POST['type'] == 'check'){
                if(judgeReg($_POST['old_pwd'])){
                    $check_pwd = md5($_POST['old_pwd']);
                    $pwd = $user->getMessage(array('id' => $_SESSION['uid'], 'pwd' => $check_pwd));
                    if (!empty($pwd)) {
                        $data['code'] = 2;
                    } else {
                        $data['code'] = 1;
                    }
                }else {
                    $data['code'] = 3;
                }
                $this->ajaxReturn($data,"JSON");
            }
            if($_POST['type'] == 'submit'){
                if(judgeReg($_POST['old_pwd'])) {
                    $new_pwd = md5($_POST['new_pwd']);
                    $update_result = $user->updateMessage(array('id' => $_SESSION['uid']), array('pwd' => $new_pwd));
                    if ($update_result) {
                        $data['code'] = 1;
                    } else {
                        $data['code'] = 2;
                    }
                }else{
                    $data['code'] =3;
                }
                $this->ajaxReturn($data,"JSON");
            }
        }
        $this->display();
    }

    /**设置支付密码**/
    public function setPayPwd(){
        $user = D('user');
        if(IS_POST) {
            if ($_POST['type'] == 'next_step') {
                $user_message = $user->getMessage(array('id' => $_SESSION['uid'], 'real_name' => $_POST['real_name']));
                if (empty($user_message)) {
                    $data['code'] = 1;
                } else {
                    if ($_POST['card_id'] == $user_message['card_id']) {
                        $data['code'] = 3;
                        $data['user_name'] = $user_message['user_name'];;
                    } else {
                        $data['code'] = 2;
                    }
                }
            }
            if ($_POST['type'] == 'submit') {
                if (!is_numeric($_POST['pay_pwd']) || strlen($_POST['pay_pwd']) != 6) {
                    $data['code'] = 1;
                }else{
                    $update_message = $user->updateMessage(array('id'=>$_SESSION['uid']),array('pay_pwd'=>md5($_POST['pay_pwd'])));
                    if($update_message){
                        $data['code'] = 2;
                    }else{
                        $data['code'] = 3;
                    }
                }
            }
            $this->ajaxReturn($data, "JSON");
        }
        $this->display();
    }

    /**重值支付密码**/
    public function resetPayPwd(){
        $user = D('user');
        if(IS_POST) {
            if ($_POST['type'] == "forget") {
                $check_card = $user->getMessage(array('id'=>$_SESSION['uid'],'card_id'=>$_POST['card_id']));
                if(!empty($check_card)){
                    $data['success'] = 1;
                    $data['user_name'] = $check_card['user_name'];
                }else{
                    $data['code'] = 2;
                }
                $this->ajaxReturn($data,"JSON");
            }else {
                $pay_pwd = md5($_POST['old_pwd']);
                $check_result = $user->is_set_check(array('id' => $_SESSION['uid'], 'pay_pwd' => $pay_pwd));
                if ($check_result) {
                    $new_pay = $_POST['new_pay'];
                    $update_pay = $user->updateMessage(array('id' => $_SESSION['uid']), array('pay_pwd' => $new_pay));
                    if ($update_pay) {
                        $data['code'] = 1;
                    } else {
                        $data['code'] = 3;
                    }
                } else {
                    $data['code'] = 2;
                }
                $this->ajaxReturn($data, "JSON");
            }
        }
        $this->display();
    }
}