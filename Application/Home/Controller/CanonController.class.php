<?php
namespace Home\Controller;
use Think\Controller;
use Home\Controller\CommondController;
class CanonController extends CommonController {

    public function dailidownload()
    { 
        $userid = $_SESSION['admin_id'];
        $files = M("daili_file");
        $file = $files->select();
        $words = null;
        foreach ($file as $item) {
            if ($item['type'] == 1){
                $words[]=$item;
            }
        }
        $this->assign("words",$words);
        $ct = M('daili_active_application')->where(array('active_user_id' => $userid))->select();//返回二维数组,find返回一维数组，如果是find前台的volist取值乱码
        /**********************申请活动************************/
        if (isset($_POST['addc'])) {
            if ($_POST['activetitle'] && $_POST['activetext']) {
                $activeapplication = M('daili_active_application');
                $activeid = $activeapplication->max('active_id');
                $data['active_id'] = $activeid + 1;
                $data['active_user_id'] = $_SESSION['admin_id'];
                $data['active_title'] = $_POST['activetitle'];
                $data['active_content'] = $_POST['activetext'];
                $data['active_time'] = date('Y-m-d H:i:s', time());//获取当前时间
                $data['active_ip'] = $_SERVER['REMOTE_ADDR'];//当前IP
                $data['active_feedback'] = '未审核';
                $activeapplication->add($data);
                $this->dailiSuccess('添加成功！', "/admin/dailidownload");
            } else {
                $this->dailiError('添加失败！', 'javascript:history.go(-1)');
            }
        }
        $this->assign('activeapplication', $ct);
        $this->display();
    }

    public function delactive()
    {

        $activeid = $_GET['active_id'];
        if (D('daili_active_application')->where(array('active_id' => $activeid))->delete()) {
            $this->dailiSuccess('删除成功', '/admin/dailidownload');
        }

    }//删除活动申请

    public function editactive()
    {

        $data['active_title	'] = $_POST['editactivetitle'];
        $data['active_content'] = $_POST['editactivetext'];
        $data['active_id'] = $_POST['hideneditactiveid'];

        if (D('daili_active_application')->where(array('active_id' => $data['active_id']))->select()) {
            D('daili_active_application')->where(array('active_id' => $data['active_id']))->save($data);
            $this->dailiSuccess('添加成功！', "/admin/dailidownload");
        } else {
            $this->dailiError('编辑失败！', 'javascript:history.go(-1)');
        }


    }//编辑活动申请

}