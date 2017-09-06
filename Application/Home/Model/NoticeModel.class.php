<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10 0010
 * Time: 10:24
 */
namespace Home\Model;
use Think\Model;

class NoticeModel extends Model{
    protected $pk = "id";
    protected $tableName = "daili_notice";

    /**
     * 获取多条信息
     * @param $condition
     * @return bool|mixed
     */
    public function getMessages($condition){
        $result = $this->where($condition)->select();
        if($result){
            return $result;
        }else{
            return false;
        }
    }


    /**
     * @param $rec_id
     * @param $content
     * @param $title
     * @return bool
     */
    public function addNotice($rec_id, $content, $title)
    {
        $data['content'] = $content;
        $data['title'] = $title;
        $data['create_time'] = time();
        $data['type'] = 2;
        $data['rec_id'] = $rec_id;
        $add = $this->add($data);
        if ($add){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $uid
     * @return bool|mixed
     */
    public function getNotice($uid)
    {
        $noticeObj = $this->where("rec_id =" . $uid)->select();
        if (empty($noticeObj)){
            return false;
        }else{
            return $noticeObj;
        }
    }

    /**
     * @param $rec_id
     * @return mixed|null
     */
    public function getNoticeByRec($rec_id)
    {
        if (!empty($rec_id)){
            $flowObj = $this->where("rec_id =" . $rec_id)->select();
        }else{
            $flowObj = null;
        }
        return $flowObj;
    }

    /**
     * @return bool
     */
    public function changeStatus()
    {
        $where['status'] = array('neq',1);
        $status = $this->where($where)->setField("status",1);
        if ($status){
            return true;
        }else{
            return false;
        }
    }
}