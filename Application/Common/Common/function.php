 <?php
/**
 * 分页代码封装
 * @param $m 模型，引用传递
 * @param $where 查询条件
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
/**
 * 推荐奖励
 * @param $referee_mobile
 * @param $user_rank
 * @return array
 */
function shareBonus($referee_mobile, $user_rank)
{
    /*查询推荐情况，获取奖励金额*/
     if (!empty($referee_mobile)) {
         $bonus = array();
         $user = D("daili_user");
         $rank = D("daili_rank");
         $referee = $user->where("tel=" . $referee_mobile)->find();//推荐人信息
         $parameterR = $rank->where("id=" . $referee['rank_id'])->find();//推荐人参数
         $parameterU = $rank->where("id=" . $user_rank)->find();//注册人参数
         $bonus['need_money'] = $parameterU['need_money'];
         /*注册金额写入系统收入表*/
         M("daili_income_log")->add(array(
             "money"=>$bonus['need_money'],
             "user_id"=>$referee['id'],
             "create_time"=>time(),
             "type"=>1
         ));
         /*所属公司负责人获得的奖励*/
         $companys = D("daili_company");
         if (!empty($referee['referee_id'])){
             $company = $companys->where("chairman=" . $referee['referee_id'])->find();
             if (!empty($company)){
                 if ($company['status']==1){
                     //记录公司业绩
                     $companys->where("chairman=" . $referee['referee_id'])->setField("achievement",$bonus['need_money']);
                     //发放公司负责人奖金
                     $user->where("id=" . $referee['referee_id'])->setField("money",($bonus['need_money'] * $parameterR['share_yj'] / 100));
                     M("daili_capital_flow")->add(array(
                         "pay_id"=>1,
                         "rec_id"=> $referee['referee_id'],
                         "type"=>5,
                         "money"=>$bonus['need_money'] * $parameterR['share_yj'] / 100,
                         "intro"=>"分公司业绩奖励。",
                         "create_time"=>time(),
                         "pay_time"=>time()
                     ));
                 }
             }
         }

         if ($referee['system'] = 2) {//业务员分享奖励
             $bonus['first'] = $parameterU['need_money'] * ($parameterR['reward_ratio'] / 100);
         } else {//代理分享奖励
             if ($referee['achievement'] > $parameterR['limit']) {//达到标准
                 $bonus['second'] = $parameterU['need_money'] * ($parameterR['reward_ratio2'] / 100) * $parameterR['ratio'];
             } else {//未达标准
                 if ($referee['achievement'] + $parameterU['need_money'] < $parameterR['limit']) {//加上本次分享注册还未达到标准
                     $bonus['first'] = $parameterU['need_money'] * ($parameterR['reward_ratio'] / 100);
                 } else {//因本次注册达到标准
                     $bouns['third'] = $parameterR['need_money'] * $parameterR['ratio'];
                     $bonus['second'] = ($referee['achievement'] + $parameterU['need_money'] - $parameterR['limit']) * ($parameterR['reward_ratio2'] / 100) * $parameterR['ratio'];
                     $bonus['first'] = ($parameterR['limit'] - $referee['achievement']) * ($parameterR['reward_ratio'] / 100);
                 }
             }
         }
     } else {
        $bonus = array();//奖励金额
    }

    /*奖励分发以及写入流水*/
    $userObj = M("daili_user");
    $sql["achievement"] = D("daili_user")->where("tel=".$referee_mobile)->setInc("achievement",$bonus['need_money']);//记录业绩
    $user = $userObj->where("tel=" . $referee_mobile)->find();
    $uid = $user['id'];
    $rank_id = $user['rank_id'];
    $sql = array();
    //推荐奖励第一种情况
    if (!empty($bonus['first'])) {
        $sql1 = D("daili_user")->where("tel=" . $referee_mobile)->setInc("money", $bonus['first']);//分享代理奖励
        $sql["income"] = D("daili_user")->where("tel=".$referee_mobile)->setInc("income",$bonus['first']);//记录奖金
        if ($sql1) {//记录资金流水
            $dataFlow['rec_id'] = $uid;
            $dataFlow['pay_id'] = 1;
            $dataFlow['money'] = -$bonus['first'];
            $dataFlow['type'] = 5;
            $dataFlow['intro'] = "分享合作伙伴收入。";
            $dataFlow['create_time'] = time();
            $dataFlow['pay_time'] = time();
            $sql["flow"] = D("daili_capital_flow")->add($dataFlow);//写入流水
         }
     }
    /*推荐奖励的第二种情况（分期付款）*/
    if (!empty($bonus['second'])) {//超出标准部分奖励（分期）
         $dataBonus = null;
         $dataBonus['user_id'] = $uid;
         $dataBonus['create_time'] = time();
         $dataBonus['type'] = 1;
         $dataBonus['money'] = $bonus['second'];
         $dataBonus['balance'] = $bonus['second'];
         if ($rank_id == 1) {
             $dataBonus['end_time'] = strtotime("+6 month");
             $dataBonus['pay_times'] = ceil(($dataBonus['end_time'] - $dataBonus['create_time']) / 86400);
         } elseif ($rank_id == 2) {
             $dataBonus['end_time'] = strtotime("+9 month");
             $dataBonus['pay_times'] = ceil(($dataBonus['end_time'] - $dataBonus['create_time']) / 86400);
         } elseif ($rank_id == 3) {
             $dataBonus['end_time'] = strtotime("+12 month");
             $dataBonus['pay_times'] = ceil(($dataBonus['end_time'] - $dataBonus['create_time']) / 86400);
         }
         $sql["upBonus"] = D("daili_divide_bonus")->add($dataBonus);
     }
     if (!empty($bonus['third'])) {//达到标准的额外奖励（分期）
         $dataBonus = null;
         $dataBonus['user_id'] = $uid;
         $dataBonus['create_time'] = time();
         $dataBonus['type'] = 1;
         if ($rank_id == 1) {
             $dataBonus['money'] = $bonus['third'];
             $dataBonus['balance'] = $bonus['third'];
             $dataBonus['end_time'] = strtotime("+6 month");
             $dataBonus['pay_times'] = ceil(($dataBonus['end_time'] - $dataBonus['create_time']) / 86400);
         } elseif ($rank_id == 2) {
             $dataBonus['money'] = $bonus['third'] * 0.8;
             $dataBonus['balance'] = $bonus['third'] * 0.8;
             $dataBonus['end_time'] = strtotime("+9 month");
             $dataBonus['pay_times'] = ceil(($dataBonus['end_time'] - $dataBonus['create_time']) / 86400);
         } elseif ($rank_id == 3) {
             $dataBonus['money'] = $bonus['third'] * 0.6;
             $dataBonus['balance'] = $bonus['third'] * 0.6;
             $dataBonus['end_time'] = strtotime("+12 month");
             $dataBonus['pay_times'] = ceil(($dataBonus['end_time'] - $dataBonus['create_time']) / 86400);
         } else {
             $dataBonus = null;
         }
         $sql["lowBonus"] = D("daili_divide_bonus")->add($dataBonus);
     }
     return $sql;//flow为写入流水的状态；upBonus为写入超出标准奖励状态；lowBonus为写入未超出标准奖励状态;achievement为写入业绩状态。
 }

function getpage(&$m,$where,$pagesize=10){
    $m1=clone $m;//浅复制一个模型
    $count = $m->where($where)->count();//连惯操作后会对join等操作进行重置
    $m=$m1;//为保持在为定的连惯操作，浅复制一个模型
    $p=new Think\Page($count,$pagesize);
    $p->lastSuffix=false;
    $p->setConfig('header','<span class="rows" style="float: right">共<b>%TOTAL_ROW%</b>条记录&nbsp;&nbsp;&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</span>');
    $p->setConfig('prev','上一页');
    $p->setConfig('next','下一页');
    $p->setConfig('last','末页');
    $p->setConfig('first','首页');
    $p->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

    $p->parameter=I('get.');

    $m->limit($p->firstRow,$p->listRows);

    return $p;
}
function GetProvinceName($province_id){
    $provinces=D('bao_province_city')->where(array('province_id'=>$province_id))->find();
    return $provinces['province'];
}
function GetCityName($city_id){
    $citys=D("bao_city")->where(array('city_id'=>$city_id))->find();
    return $citys['name'];
}
function GetAreaName($area_id){
    $areas=D("bao_area")->where(array('area_id'=>$area_id))->find();
    return $areas['area_name'];
}


//根据公司id查找所有下级公司
function GetLowCompany($company_id,$where){
    $company=D('daili_company')->where(array('company_id'=>$company_id))->find();
    if ($company['leavel']==1 && $company_id!=1){
        $where['province_id']=$company['province_id'];
    }elseif ($company['leavel']==2 || $company['leavel']==3 && $company_id!=1){
        $where['city_id']=$company['city_id'];
    }elseif ($company['leavel']==4 || $company['leavel']==5 && $company_id!=1){
        
        $where['area_id']=$company['area_id'];
    }
    $where['company_id']=array('NEQ',$company_id);
    $lowcompany=D("daili_company")->where($where)->order('company_id desc')->select();
    foreach ($lowcompany as $k=>$item) {
        $lowcompany[$k]['province_name']=GetProvinceName($item['province_id']);
        $lowcompany[$k]['city_name']=GetCityName($item['city_id']);
        $lowcompany[$k]['area_name']=GetAreaName($item['area_id']);
    }
    return $lowcompany;
}

/**邮件发送函数**/
function sendMail($to, $title, $content) {
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
    $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to,"尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return($mail->Send());
}

/**随机生成验证码数字函数****/
function randCode($length){
    return substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $length);
}
/**验证身份证号码**/
function isCreditNo($vStr){
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return 0;
    if (!in_array(substr($vStr, 0, 2), $vCity)) return 0;
    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);
    if ($vLength == 18) {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return 0;
    if ($vLength == 18) {
        $vSum = 0;
        for ($i = 17 ; $i >= 0 ; $i--) {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
        if($vSum % 11 != 1) return 0;
    }
    return 1;
}

 function getShareNum($uid,$rank_id){
     $users=D("daili_user");
     $user=$users->where(array("referee_id"=>$uid,"rank_id"=>$rank_id))->select();
     return count($user);
 }
 /**银行卡号验证**/
 function luhm($s) {
     $n = 0;
     for ($i = strlen($s); $i >= 1; $i--) {
         $index=$i-1;
         //偶数位
         if ($i % 2==0) {
             $n += $s{$index};
         } else {//奇数位
             $t = $s{$index} * 2;
             if ($t > 9) {
                 $t = (int)($t/10)+ $t%10;
             }
             $n += $t;
         }
     }
     return ($n % 10) == 0;
 }
/**获取客户ip**/
function getIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}