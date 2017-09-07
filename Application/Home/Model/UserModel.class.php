<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 13:46
 */
namespace Home\Model;
use Think\Model;

class UserModel extends Model{
    protected $pk = 'id';
    protected $tableName = 'daili_user';
    public $nextService;
    public $lowerIds;
    /**
    *个人信息
     */
    public function getUserMassage($uid)
    {
        $userObj = $this->where(array(array('id'=>$uid)))->find();
        return $userObj;
    }
    /**通过手机查找用户**/
    public function getUserByMobile($mobile)
    {
        $userObj = $this->where("tel = " . $mobile)->find();
        return $userObj;
    }
    /**
     * 查找下级用户
     * @param $uid
     * @return mixed
     */
    public function getReferMsg($uid)
    {
        $userObj = $this->where("referee_id = " . $uid)->select();
        return $userObj;
    }
    /**
     * 转账
     * @param $rec_mobile
     * @param $pay_user
     * @param $need_pay
     * @return int|string
     */
    public function transfer($rec_mobile,$pay_user,$need_pay)
    {
        $user_money = $this->where(array('id'=>$pay_user))->getField("register_coin");
        if ($user_money >= $need_pay){
            $pay = $this->where(array('id'=>$pay_user))->setDec("register_coin",$need_pay);   //从转出账户扣款
            if ($pay){
                $get = $this->where(array('tel'=>$rec_mobile))->setInc("register_coin",$need_pay); //给转入账户增款
                if ($get){
                    $msg = 2;  //收账账户增款成功
                }else{
                    $msg = 3; //转账账户扣款成功，但是收账账户未到账
                }
            }else{
                $msg = 1;  //转账人未扣款
            }
        }else{
            $msg = 4; //余额不足
        }
        return $msg;
    }

    /**
     * 用户提现
     * @param $uid
     * @param $cashMoney
     * @return int|string
     */
    public function userCash($uid,$cashMoney)
    {
        $userMoney = $this->where("id = " . $uid)->getField("money");
        if ($userMoney < $cashMoney){
            return "账户余额不足！";
        }else{
            $cashSql = $this->where("id = " . $uid)->setDec("money",$cashMoney);
            if ($cashSql){
                return 1;
            }else{
                return "提现失败！";
            }
        }
    }
    /**
     * @param $userName
     * @return mixed
     */
    public function loginMessage($userName)
    {
        $userObj = $this->where(array("user_name" => $userName))->find();
        return $userObj;
    }
    /**
     * 用户排行
     */
    public function ranking($norm)
    {
        $userObj = $this->order($norm." "."desc")->select();
        foreach ($userObj as $key=>$value) {
            if ($value['rank_id'] == 1){
                $userObj[$key]["rank_name"] = "一级";
            }elseif ($value['rank_id'] == 2){
                $userObj[$key]['rank_name'] = "二级";
            }elseif ($value['rank_id'] == 3){
                $userObj[$key]['rank_name'] = "三级";
            }else{
                $userObj[$key]['rank_name'] = "四级";
            }
        }
        return $userObj;
    }
    /**
     * 个人信息
     * @param $condition
     * @return bool|mixed
     */

    public function getMessage($condition){
        $result = $this->where($condition)->find();
        if($result){
            $rank = D('Rank')->getMessage(array('rank'=>$result['rank_id']));
            $result['rank_name'] = $rank['rank_name'];
            $result['service_ratio'] = $rank['service_ratio'];
            if ($result['rank_id'] == 1){
                $result['rank'] = "省级";
            }elseif ($result['rank_id'] ==2){
                $result['rank'] = "市级";
            }else{
                $result['rank'] = "贸易商";
            }
            //获取省市区
            $addr['code'] = array('in',array($result['province_id'],$result['city_id'],$result['area_id']));
            $addr_res = M('daili_address')->field('name')->where($addr)->select();
            $result['addr'] = $addr_res[0]['name'].".".$addr_res[1]['name'].".".$addr_res[2]['name'];
            return $result;
        }else{
            return false;
        }
    }
    /**
     * 获取当日的业绩
     * @param $id
     * @return int|number
     */
    public function dayAchievement($id){
        $start_time = strtotime(date('Y-m-d'));
        $end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
        $map['create_time'] = array(array('egt',$start_time),array('lt',$end_time));
        $map['referee_id'] = $id;
        $result = $this->field('join_fee')->where($map)->select();
        $achievement = 0;
        if(!empty($result)){
            foreach($result as $val){
                $join_fee[] = $val['join_fee'];
            }
            $achievement = array_sum($join_fee);
        }
        return $achievement;
    }
    /**
     * 获取总的业绩
     * @param $ids
     * @return int|number
     */
    public function allAchievement($ids){
        $achievement = 0;
        if(!empty($ids)){
            $map['referee_id'] = array('in',$ids);
            $result = $this->field('join_fee')->where($map)->select();
            if(!empty($result)){
                foreach($result as $val){
                    $join_fee[] = $val['join_fee'];
                }
                $achievement = array_sum($join_fee);
            }
        }
        return $achievement;
    }

    /**
     * 获取所有下级人员的ids
     * @param $id
     */
    public function getAllNextIds($id){
        $this->lowerIds[] = $id;
        $result_ids = $this->field('id')->where(array('referee_id'=>$id))->select();
        if(!empty($result_ids)){
            foreach($result_ids as $value){
                $this->getAllNextIds($value['id']);
            }
        }
    }

    /**
     * 我的伙伴数
     * @param $referee_id
     * @return int
     */
    public function getReferNum($referee_id){
        $result = $this->where(array('referee_id'=>$referee_id))->select();
        $number = count($result,0);
        return $number;
    }

    /**
     * 获取个人排行榜排名
     * @param $id
     * @return int|string
     */
    public function rankingList($id){
        $result = $this->field('id,income')->select();
        if(!empty($result)){
            foreach($result as $key=>$val){
                $achievement[$key] = $val['income'];
            }
            //根据值的大小进行排序
            array_multisort($achievement,SORT_DESC,$result);
            foreach($result as $key=>$value){
                if($value['id'] == $id){
                    $ranking = $key + 1;
                }
            }
            return $ranking;
        }
    }
    /**
     * 获取该等级已注册人数
     * @param $rank_id
     * @return bool
     */
    public function getRankNum($rank_id){
        $result = $this->where(array('rank_id'=>$rank_id))->count();
        if($result){
            return $result;
        }else{
            return false;
        }
    }
    /**
     * 根据条件判断是否存在
     * @param $condition
     * @return bool
     */
    public function is_set_check($condition){
        $result = $this->where($condition)->find();
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }
    
//    function sort_array($array, $key_id, $order='asc', $type='number') {
//        if(is_array($array)) {
//            foreach($array as $val) {
//                $order_arr[] = $val[$key_id];
//            }
//
//            $order = ($order == 'asc') ? SORT_ASC: SORT_DESC;
//            $type  = ($type == 'number') ? SORT_NUMERIC: SORT_STRING;
//
//            array_multisort($order_arr, $order, $type, $array);
//        }
//    }

    /**
     * 添加用户
     * @param $datas
     * @return mixed
     */
    public function addUser($datas)
    {
        $data['user_name'] = $datas['mobile'];
        $data['pwd'] = md5("WL-china");
        $data['card_id'] = $datas['card_id'];
        $data['tel'] = $datas['mobile'];
        $data['real_name'] = $datas['real_name'];
        $data['rel'] = $datas['mobile'];
        $data['province_id'] = $datas['province'];
        $data['city_id'] = $datas['city'];
        $data['area_id'] = $datas['area'];
        $data['referee_id'] = $datas['referee_id'];
        $data['address'] = $datas['address'];
        $data['rank_id'] = $datas['rank'];
        $data['create_time'] = time();
        $data['service_number'] = $datas['service_number'];
        $data['dividend'] = $datas['dividend'];
        $data['join_fee'] = $datas['join_fee'];
        if ($datas['rank'] == 1 || $datas['rank'] == 2){
            $data['is_service'] = 1;
        }
        $bool = $this->add($data);
        if ($bool){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 更新信息
     * @param $condition
     * @param $data
     * @return bool
     */
    public function updateMessage($condition,$data){
        $result = $this->where($condition)->save($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证是否已存在
     * @param $value
     * @param $type
     * @return bool
     */
    public function isExist($value,$type)
    {
        $sql = $this->where(array($type=>$value))->find();
        if (empty($sql)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 更新用户信息（增加）
     * @param $uid
     * @param $name
     * @param $value
     * @return bool
     */
    public function updateUserMsg($uid,$name, $value)
    {
        $update = $this->where("id = " . $uid)->setInc($name,$value);
        if ($update){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 更新用户信息（减少）
     * @param $uid
     * @param $name
     * @param $value
     * @return bool
     */
    public function updateUserMsgReduce($uid,$name, $value)
    {
        $update = $this->where("id = " . $uid)->setDec($name,$value);
        if ($update){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $referee_id
     * @return bool
     */
    public function getNextService($referee_id)
    {
        if (!empty($referee_id)){
            $maps['is_service'] = 1;
            $maps['referee_id'] = $referee_id;
            $user = $this->where($maps)->select();
            if (!empty($user)){
                foreach ($user as $item) {
                    $this->nextService[] = $item;
                    $this->getNextService($item['id']);
                }
            }
        }else{
            return false;
        }
    }

    /**
     * 获取总业绩
     * @param $condition
     * @return bool|int
     */
    public function getAchievement($condition){
        $result = $this->where($condition)->select();
        if($result){
            $achievement = 0;
            if(!empty($result)){
                foreach($result as $value){
                    $achievement += $value['join_fee'];
                }
            }
            return $achievement;
        }else{
            return false;
        }
    }

    /**
     * 余额充值注册币
     * @param $id
     * @param $money
     * @return bool
     */
    public function turnCoin($id, $money)
    {
        $users = M();
        $sql_del = "UPDATE daili_user SET `money` = `money` - $money  WHERE `id` = $id ";
        $sql_inc = "UPDATE daili_user SET `register_coin` = `register_coin`+ $money WHERE `id` = $id ";
        $user_del = $users->execute($sql_del);
        $user_inc = $users->execute($sql_inc);
        if ($user_del){
            if ($user_inc){
                $msg = 1;    //充值成功：钱扣掉了，且注册币增加了
            }else{
                $msg = 2;  //钱扣掉了，但是注册币没有增加
            }
        }else{
            $msg = 3;  //充值失败 ：钱没有被扣，注册币也没有增加
        }
        return $msg;
    }

    /**
     * 根据ID获取用户信息，ID是数组，用于循环
     * @param $id
     * @return bool
     */
    public function getUesrMsg($id){
        $where_id['id'] = array('in',$id);
        $userRec = $this-> where($where_id) ->find();
        if($userRec){
            return $userRec;
        }else{
            return false;
        }
    }

    /**
     * 检查省级是否重复注册
     * @param $id
     * @return bool
     */
    public function inspectProvince($id)
    {
        $map['province_id'] = $id;
        $sql = $this -> where($map) -> find();
        if ($sql){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 检查市级是否重复注册
     * @param $id
     * @return bool
     */
    public function inspectCity($id)
    {
        $map['city_id'] = $id;
        $sql = $this -> where($map) -> find();
        if ($sql){
            return true;
        }else{
            return false;
        }
    }

//    public function monthlyMoney($id)
//    {
//        $userMsg = $this->where("id = " . $id)->find();
//        $ratio = D("ratio");
//        $term = $ratio->getTerm();
//        $time = date("d ", $userMsg['create_time']);
//
//    }
}