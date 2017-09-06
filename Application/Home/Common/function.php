<?php
/**
 * 获取最新的任务完成信息
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/4
 * Time: 16:19
 */

/**
 * 密码正则判断
 * @param $str
 * @return bool
 */

function judgeReg($str){
    if(preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $str)){
        return true;
    }else{
        return false;
    }
}
/*上传文件的命名方式*/
function get_uuid(){
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
    return strtolower($uuid);
}
/**
 * 加盟商升级
 * @param $id
 * @param $referee_id
 * @param $old_rank
 * @param $new_rank
 * @param $money
 * @return bool
 */
function upgrade_payment($id,$referee_id,$old_rank,$new_rank,$money){
    $users = M();
    //更新用户表信息
    if($new_rank == 1){
        $dividend = 10;
    }elseif($new_rank == 2){
        $dividend = 5;
    }elseif($new_rank == 3){
        $dividend = 1;
    }
    $sql = "UPDATE daili_user SET `register_coin` = `register_coin` - ".$money.",`join_fee` = `join_fee`+".$money.",`rank_id` =".$new_rank.",`dividend` = ".$dividend." WHERE `id` = ".$id;
    $update_user = $users->execute($sql);
    if($update_user){
        /*分发相应的福利*/
        D('CashFlow')->addFlow($id,1,10,$money,'升级等级',0);
        //直推人奖励拨付
        $referee_rank = D('user')->where(array('id'=>$referee_id))->getField('rank_id');
        $service_number = D('user')->where(array('id'=>$id))->getField('service_number');
        $referee_bonus = D('rank')->where(array('rank'=>$referee_rank))->getField('referee_bouns');
        $referee_reward = $money * $referee_bonus/100;
        $update_message = release_update($referee_id,$referee_reward);
        if($update_message){
            D('CashFlow')->addFlow(1,$referee_id,8,$referee_reward,'被推荐人提升等级奖励',0);
            //服务奖励
            service_recharge($service_number,$money);//兑换中心收取的服务奖励
            //分销奖励
            distribution($referee_id,$referee_reward);
            //将业绩补给兑换中心
            $service_id = D('user')->where(array('is_service'=>1,'service_number'=>$service_number))->getField('id');
            D('user')->where(array('id'=>$service_id))->setInc('achievement',$money);
            $point = $money * 0.15;
            D('user')->where(array('id'=>$service_id))->setInc('points',$point);
            /*补发分红点功能暂未确定是否使用，请勿删除*/
            //补发升级后个人所得分红点
            //$old_bonus_point = D('rank')->field('bouns_dot)->where(array('rank'=>$old_rank))->find();
            //$new_bonus_point = D('rank')->field('bouns_dot)->where(array('rank'=>$new_rank))->find();
            //$add_bonus_point['bouns_dot'] = $new_bonus_point['bouns_dot'] - $old_bonus_point['bouns_dot'];
            //$add_bonus_point['reward_bonus_point'] = $new_bonus_point['reward_bonus_point'] - $old_bonus_point['reward_bonus_point'];
            //$pay_bonus_point = pay_bonus_point($id,$add_bonus_point);
            //补发直推人额外补助分红点数
            $refereeMsg = D('user')->getMessage(array('id'=>$referee_id));//推荐人信息
            $dot = 0;
            if($refereeMsg['rank_id'] == 1){
                if($old_rank == 2){
                    $dot = 1.5;
                }elseif($old_rank == 3){
                    $dot = 0.3;
                }
            }elseif($refereeMsg['rank_id'] == 2){
                if($old_rank == 2){
                    $dot = 1.25;
                }elseif($old_rank == 3){
                    $dot = 0.25;
                }
            }elseif($refereeMsg['rank_id'] == 3){
                if($old_rank == 2){
                    $dot = 1;
                }elseif($old_rank == 3){
                    $dot = 0.2;
                }
            }
            //新等级的额外奖励的分红点
            $new_dot = 0;
            if ($refereeMsg['rank_id'] == 1){
                if ($new_rank == 1){
                    $new_dot = 3;
                }elseif($new_rank == 2){
                    $new_dot = 1.5;
                }elseif($new_rank == 3){
                    $new_dot = 0.3;
                }
            }elseif($refereeMsg['rank_id'] == 2){
                if ($new_rank == 1){
                    $new_dot = 2.5;
                }elseif($new_rank == 2){
                    $new_dot = 1.25;
                }elseif($new_rank == 3){
                    $new_dot = 0.25;
                }
            }elseif ($refereeMsg['rank_id'] == 3){
                if ($new_rank == 1){
                    $new_dot = 2;
                }elseif($new_rank == 2){
                    $new_dot = 1;
                }elseif($new_rank == 3){
                    $new_dot = 0.2;
                }
            }
            $add_dot = $new_dot - $dot;
            $update_bonus = D('user')->where(array('id'=>$referee_id))->setInc('reward_bonus',$add_dot);
            if($update_bonus){
                return true;
            }
        }else{
            return false;
        }
    }
}

/**
 * 资金拨付并更新数据
 * @param $id 收款人ID
 * @param $value 收款金额
 * @return bool
 */
function release_update($id,$value){
    $user = M();
    //扣除固定游戏充值的金额
    $game_value = $value * 5/100;
    $val = $value - $game_value;
    $sql = " UPDATE daili_user SET `money` = `money` + ".$val.",`income` = `income`+".$val.",`fixed_recharge` = `fixed_recharge`+".$game_value." WHERE `id` = ".$id;
    $update_user = $user->execute($sql);
    if($update_user){
        return true;
    }else{
        return false;
    }
}

/*添加业绩*/
function addAchievement($ids,$value){
    $user = M('daili_user');
    $map['id'] = array('in',$ids);
    $result = $user->where($map)->setInc('achievement',$value);
    if($result){
        return true;
    }else{
        return false;
    }
}

/**
 * 根据手机号码查询会员id
 * @param $mobile
 * @return bool|mixed
 */
function getIdByMobile($mobile){
    //获取用户会员ID
    $user_id = M('bao_users')->where(array('mobile'=>$mobile))->getField('user_id');
    if(!empty($user_id)){
        return $user_id;
    }else{
        return false;
    }
}


/**
 * 查询出正在进行中的完成指标和奖励
 * @param $uid
 * @param $id 任务日志id
 * @param $bonus
 * @param $start_time
 * @param $end_time
 * @return mixed
 */
//function getInfoById($uid,$id,$bonus,$start_time,$end_time,$task_norm){//1.推荐人 2.推荐店 3.自我消费...
//    //根据任务id查询出任务信息
//    $task_Logs = D('TaskLog')->getLogById($id);
//    $finish_num = 0;
//    $reward = 0;
//    if($task_Logs['type'] == 1){
//        $finish_num = shareVipNum($uid,$start_time,$end_time);
//    }
//
//    if($task_Logs['type'] == 2){
//        $finish_num = shareShopNum($uid,$start_time,$end_time);
//    }
//
//    if($task_Logs['type'] == 3){
//        $finish_num = spendSelfByDay($uid,$start_time,$end_time);
//    }
//    if($task_Logs['type'] == 4){
//        $norm = explode(',',$task_norm);
//        $finish_num = spendingByOther($uid,$norm,$start_time,$end_time);
//    }
//    if(!empty($task_Logs)) {
//        if ($finish_num >= $task_Logs['task_num']) {
//            $reward = $bonus * $task_Logs['ratio'] / 100;
//        } else {
//            $reward = 0;
//        }
//    }
//    $result['finish_num'] = $finish_num;
//    $result['reward'] = $reward;
//    return $result;
//}

/**
 * 获取手续费比例
 * @param $id 设置rank表的id
 * @return bool|mixed
 */
function getFee($id){
    $map['id'] = $id;
    $fee = D('Rank')->field('cash_fee,transfer_fee')->where($map)->find();
    if(!empty($fee)){
        return $fee;
    }else{
        return false;
    }
}

/**
 * 扣款
 * @param $condition
 * @param $data
 * @return bool
 */
function decCash($condition,$data){
    $result = D('User')->where($condition)->setDec('money',$data);
    if($result){
        return true;
    }else{
        return false;
    }
}

/**
 * 收款
 * @param $condition
 * @param $data
 * @return bool
 */
function incCash($condition,$data){
    $result = D('User')->where($condition)->setInc('money',$data);
    if($result){
        return true;
    }else{
        return false;
    }
}

/**
 * 数据操作结果前台展示
 * @param $message
 * @param string $jumpUrl
 * @param int $time
 */
function agentMsg($message, $jumpUrl = '', $time = 3000){
    $str = '<script>';
    $str .= 'parent.boxMsg("' . $message . '","' . $jumpUrl . '","' . $time . '");';
    $str .= '</script>';
    die($str);
}

/**
 * 直推奖励
 * @param $referee "推荐人手机号码"
 * @param $money "注册金额"
 * @param $rank "注册等级"
 * @return bool
 */
function shareAwards($referee,$money,$rank){
    $userObj = D("User");
    $rankObj = D("Rank");
    $refereeMsg = $userObj->getUserByMobile($referee);
    $refereeRank = $rankObj->getRankMassage($refereeMsg['rank_id']);
    //直推奖励
    $bonus = $money * $refereeRank["referee_bouns"] / 100;
//    $bonus = $money * $refereeRank["referee_bouns"] / 100 * 0.95;
    $saveBonus = $userObj->updateUserMsg($refereeMsg['id'],"money",$bonus);
    $userObj->updateUserMsg($refereeMsg['id'],"income",$bonus);
//    $save_distribution = distribution($refereeMsg['id'],$bonus / 0.95);
    //分享奖励分红点
    $dot = 0;
    if ($refereeMsg['rank_id'] == 1){
        if ($rank == 1){
            $dot = 3;
        }elseif($rank == 2){
            $dot = 1.5;
        }elseif($rank == 3){
            $dot = 0.3;
        }
    }elseif($refereeMsg['rank_id'] == 2){
        if ($rank == 1){
            $dot = 2.5;
        }elseif($rank == 2){
            $dot = 1.25;
        }elseif($rank == 3){
            $dot = 0.25;
        }
    }elseif ($refereeMsg['rank_id'] == 3){
        if ($rank == 1){
            $dot = 2;
        }elseif($rank == 2){
            $dot = 1;
        }elseif($rank == 3){
            $dot = 0.2;
        }
    }
    $userObj->updateUserMsg($refereeMsg['id'],"reward_bonus",$dot);
    //强制游戏充值
//    $game = $money * $refereeRank['referee_bouns'] /100 * 0.05;
//    $saveGame = $userObj->updateUserMsg($refereeMsg['id'],"fixed_recharge",$game);
    $data = array(
        "pay_id" => 1,
        "rec_id" => $_POST['referee_id'],
        "type" => 8,
//        "money" => $bonus / 0.95,
        "money" => $bonus,
        "remarks" => "分享奖励",
        "fee" => 0,
    );
    $addFlow = addFlow($data);//流水
    $return = array("money"=>$bonus,"dot"=>$dot);
//    if ($saveBonus && $saveGame && $addFlow && $saveBonus){
    if ($saveBonus && $addFlow && $saveBonus){
        return $return;
    }else{
        return false;
    }
}
/**
 * 流水写入
 * @param $data "$data['pay_id'],$data['rec_id'],$data['type'],$data['money'],$data['remarks'],$data['fee']"
 * @return bool
 */
function addFlow($data){
    $cashFlow = D("CashFlow");
    $addFlow = $cashFlow->addFlow($data['pay_id'],$data['rec_id'],$data['type'],$data['money'],$data['remarks'],$data['fee']);
    if ($addFlow){
        return true;
    }else{
        return false;
    }
}

/**
 * 服务费奖励拨付
 * @param $service_number
 * @param $money
 */
function service_recharge($service_number,$money){
    //获取所有的相应兑换中心的服务费比例
    $exchange = D('Exchange');
    $exchange->serviceCharge($service_number);
    $result_message = $exchange->allMessages;
    $a = 0;
    foreach($result_message as $value){
        $b = $value['service_ratio'];
        $c = $b - $a;
        if($c > 0){
            $service_fee = $money * $c/100;
            $re = release_update($value['apply_id'],$service_fee);//服务费拨付
            //分销奖励
            //distribution($value['apply_id'],$service_fee);
            if($re){
                //写流水
                D('CashFlow')->addFlow(1,$value['apply_id'],7,$service_fee,'服务奖励',0);
            }
            $a = $b;
        }
    }
}

/**
 * 拨付分销奖励（不包含服务费）
 * @param $id
 * @param $money
 * @return bool
 */
function distribution($id,$money){
    if($id != 1) {
    $user = D('user');
    $first_distribution = $money * 5/100;
    $last_distribution = $money * 10/100;
    //一级分销
    $first_referee_id = $user->where(array('id'=>$id))->getField('referee_id');
    if(in_array($first_referee_id,array(1,2,3,4))){
        return true;
    }else{
        $update_user1 = release_update($first_referee_id,$first_distribution);
        if($update_user1){
            D('CashFlow')->addFlow(1,$first_referee_id,9,$first_distribution,'分销奖励',0);
        }
        //二级分销
        $last_referee_id = $user->where(array('id'=>$first_referee_id))->getField('referee_id');
	if(in_array($last_referee_id,array(1,2,3,4))){
            return true;
        }else{
            $update_user2 = release_update($last_referee_id,$last_distribution);
            if($update_user2){
                D('CashFlow')->addFlow(1,$last_referee_id,9,$last_distribution,'分销奖励',0);
                }
            }
        }
    }
}

/**
 * 注册信息填写验证
 * @param $data
 * @return string
 */
function testUserMsg($data){
    $rank = D("Rank");
    $user = D("User");
    $rankMsg = $rank->getRankMassage($data['rank']);
    $exchangeMsg = $user->getUserMassage($_SESSION['uid']);
    if(empty($data['referee'])){
        $data['referee'] = 1;
        $data['referee_id'] = 1;
        if ($data['rank'] == 3){
            $msgs = "推荐人信息有误，请重新填写！";
        }
    }else if (in_array("", $data)) {
        $msgs = "请完善个人信息！";
//    } else if ($exchangeMsg['register_coin'] < $rankMsg['need_money']) {
//        $msgs = "注册币不足，请联系客服充值！";
    } else if ($user->isExist($data['mobile'], "tel")) {
        $msgs = "该手机号码已经注册，请勿重复注册！";
    } else if ($data['mobile'] == $data['referee']) {
        $msgs = "推荐人不可为注册人本身！";
    } else if ($user->isExist($data['card_id'], "card_id")) {
        $msgs = "该身份证号码已经注册，请勿重复注册！";
    } else if (!preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $data['real_name'])) {
        $msgs = "请输入正确的姓名！";
    } else if (!isCreditNo($data['card_id'])) {
        $msgs = "请输入正确的身份证号码！";
    } else if (strlen($data['mobile']) != 11 || substr($data['mobile'], 0, 1) != 1) {
        $msgs = "请输入正确的手机号码！";
    } elseif ($_POST['rank'] == 0){
        $msgs = "请完善个人信息！";
    }elseif ($_POST['rank'] == 1 && $user->inspectProvince($_POST['province'])){//检测省是否已注册
        $msgs = "对不起，该省办事处已被抢注！";
    }elseif($_POST['rank'] == 2 && $user->inspectCity($_POST['city'])){//检测市是否已注册
        $msgs = "对不起，该市办事处已被抢注！";
    }else {
        $msgs = 1;
    }
    return $msgs;
}
/**
 * 上传企业信息填写验证
 * @param $data
 * @return string
 */
function testSuppliersMsg($data){
    if (in_array("", $data)) {
        $msgs = "请完善企业信息！";
    }else {
        $msgs = 1;
    }
    return $msgs;
}

/**
 * 获取星期几信息
 * @param $time
 * @param int $i
 * @return string
  */
function getTimeWeek($time, $i = 0) {
    $week_array = array("日","一", "二", "三", "四", "五", "六");
    $oneDay = 24 * 60 * 60;
    return $week = "周" . $week_array[date("w", $time + $oneDay * $i)];
}

/**
 * 短信接口调用方法
 * 使用：
 * echo curlOpen('http://www.baidu.com');
 *
 * POST数据
 * $post = array('aa'=>'ddd','ee'=>'d')
 * 或
 * $post = 'aa=ddd&ee=d';
 * echo curlOpen('http://www.baidu.com',array('post'=>$post));
 * @param string $url
 * @param array $config
 */
function curlOpen($url, $config = array())
{
    $arr = array('post' => false,'referer' => $url,'cookie' => '', 'useragent' => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; customie8)', 'timeout' => 20, 'return' => true, 'proxy' => '', 'userpwd' => '', 'nobody' => false,'header'=>array(),'gzip'=>true,'ssl'=>false,'isupfile'=>false);
    $arr = array_merge($arr, $config);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $arr['return']);
    curl_setopt($ch, CURLOPT_NOBODY, $arr['nobody']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $arr['useragent']);
    curl_setopt($ch, CURLOPT_REFERER, $arr['referer']);
    curl_setopt($ch, CURLOPT_TIMEOUT, $arr['timeout']);
//curl_setopt($ch, CURLOPT_HEADER, true);//获取header
    if($arr['gzip']) curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    if($arr['ssl'])
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    if(!empty($arr['cookie']))
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $arr['cookie']);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $arr['cookie']);
    }

    if(!empty($arr['proxy']))
    {
//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); 
        curl_setopt ($ch, CURLOPT_PROXY, $arr['proxy']);
        if(!empty($arr['userpwd']))
        {
            curl_setopt($ch,CURLOPT_PROXYUSERPWD,$arr['userpwd']);
        }
    }

//ip比较特殊，用键值表示
    if(!empty($arr['header']['ip']))
    {
        array_push($arr['header'],'X-FORWARDED-FOR:'.$arr['header']['ip'],'CLIENT-IP:'.$arr['header']['ip']);
        unset($arr['header']['ip']);
    }
    $arr['header'] = array_filter($arr['header']);

    if(!empty($arr['header']))
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arr['header']);
    }
    if ($arr['post'] != false)
    {
        curl_setopt($ch, CURLOPT_POST, true);
        if(is_array($arr['post']) && $arr['isupfile'] === false)
        {
            $post = http_build_query($arr['post']);
        }
        else
        {
            $post = $arr['post'];
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $result = curl_exec($ch);
//var_dump(curl_getinfo($ch));
    curl_close($ch);
    return $result;
}

function sms($mobile){//手机号 超过1024请用POST
    $appkey = 'ef9291ffcee010c7';//你的appkey
    $content = '恭喜您成功注册江西兔尔宝加盟商系统，请与官方微信公众号登陆系统，账号为手机号码，初始密码为teb123456，请及时修改密码。【兔尔宝】';//utf8
    $url = "http://api.jisuapi.com/sms/send?appkey=$appkey&mobile=$mobile&content=$content";
    $result = curlOpen($url);
    $jsonarr = json_decode($result, true);
    if($jsonarr['status'] != 0)
    {
        echo $jsonarr['msg'];
        exit();
    }
    $result = $jsonarr['result'];
    return $result['count'];
}