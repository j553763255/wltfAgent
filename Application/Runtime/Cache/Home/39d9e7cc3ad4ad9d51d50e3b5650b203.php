<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-cn">
<head>
    <META name="Pragma" CONTENT="no-cache">
    <META name="Cache-Control" CONTENT="no-cache">
    <META name="Expires" CONTENT="0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>卧龙腾飞加盟商平台</title>
    <link rel="stylesheet" href="/wltfagent/Public/home/css/common.css">
    <link rel="stylesheet" href="/wltfagent/Public/home/css/home.css">
    <link rel="stylesheet" href="/wltfagent/Public/home/css/style.css">
    <script type="application/javascript" src="/wltfagent/Public/home/js/jquery.js"></script>
    <script type="application/javascript" src="/wltfagent/Public/home/layer/layer.js"></script>
    <!--<script type="application/javascript" src="/wltfagent/Public/home/layer_mobile/layer.js"></script>-->
    <script type="application/javascript" src="/wltfagent/Public/home/js/common.js"></script>
    <script type="application/javascript" src="/wltfagent/Public/home/js/style.js"></script>
</head>
<iframe name="niu-frame" style="display: none"></iframe>
<link rel="stylesheet" href="/wltfagent/Public/home/css/RabbitBao.css">
<link rel="stylesheet" href="/wltfagent/Public/home/css/task.css">
<script src="/wltfagent/Public/home/js/RabbitBao.js"></script>
<script src="/wltfagent/Public/admin/js/echarts.js"></script>
<script src="/wltfagent/Public/admin/js/china.js"></script>
</head>
<body>
    <div class="header"><strong>数据中心</strong></div>
    <div class="statistics">
        <div class="statistics-left">
            <div class="left-top">
                <div class="image">
                    <img src="/wltfagent/Public/home/images/rabbitBao_01.png">
                </div>
                <div class="value">
                    <p class="title">当日收入</p>
                    <p class="money">$<?php echo ($income); ?></p>
                </div>
            </div>
            <div class="left-button">
                <div class="image">
                    <img src="/wltfagent/Public/home/images/rabbitBao_02.png">
                </div>
                <div class="value">
                    <p class="title">当日支出</p>
                    <p class="money">$<?php echo ($pay); ?></p>
                </div>
            </div>
        </div>
        <div class="statistics-right">
            <div class="image">
                <img src="/wltfagent/Public/home/images/rabbitBao_03.png">
            </div>
            <div class="value">
                <p class="title">总收入</p>
                <p class="money">$<?php echo ($allIncome); ?></p>
            </div>
        </div>
    </div>
    <div class="content">
        <!--<div class="nav-head">
            <ul>
                &lt;!&ndash;<li class="tab-head active"><a href="#tab-news">消息</a></li>&ndash;&gt;
                &lt;!&ndash;<li class="tab-head"><a href="#tab-task">任务大厅</a></li>&ndash;&gt;
                <li class="tab-head"><a href="#tab-map">中心分布</a></li>
            </ul>
        </div>-->
        <div class="nav-body">
            <!--<div class="tab-body active" id="tab-news">
                <ul>
                    <a href="<?php echo U('home/RabbitBao/moneyNotice');?>">
                        <li class="money_change">
                                <img src="/wltfagent/Public/home/images/solid-left_13.png">
                                <p class="title">金额变动消息</p>
                                <p class="value"><?php echo ($counted); ?>/<?php echo ($count); ?></p>
                        </li>
                    </a>
                    <li class="game_user">
                        <img src="/wltfagent/Public/home/images/solid-left_13.png">
                        <p class="title">玩家增加消息</p>
                        <p class="value">0/0</p>
                    </li>
                    <a href="<?php echo U('home/RabbitBao/agentNotice');?>">
                        <li class="agent_user">
                            <img src="/wltfagent/Public/home/images/solid-left_13.png">
                            <p class="title">加盟商增加消息</p>
                            <p class="value"><?php echo ($noticeCounted); ?>/<?php echo ($noticeCount); ?></p>
                        </li>
                    </a>
                    <a href="<?php echo U('home/RabbitBao/accountRecoder');?>">
                        <li class="money_change">
                            <img src="/wltfagent/Public/home/images/solid-left_13.png">
                            <p class="title">转账记录</p>
                        </li>
                    </a>
                </ul>
            </div>-->
            <!--<div class="tab-body" id="tab-task">
                <div class="task-list">
                    <div class="task-left">
                        <span class="task-type">系统任务</span>
                        <div class="task-complete"><span class="complete-right">100%</span></div>
                    </div>
                    <div class="task-centre">
                        <div class="task-title">[分享玩家]</div>
                        <div class="task-time">2017-02-21</div>
                        <button class="button">领取</button>
                    </div>
                    <div class="task-right">
                        <div class="task-operate">暂无任务</div>
                    </div>
                </div>
                <div class="task-list">
                    <div class="task-left">
                        <span class="task-type">系统任务</span>
                        <div class="task-complete"><span class="complete-right">100%</span></div>
                    </div>
                    <div class="task-centre">
                        <div class="task-title">[玩家首充]</div>
                        <div class="task-time">2017-02-21</div>
                        <button class="button">领取</button>
                    </div>
                    <div class="task-right">
                        <div class="task-operate">暂无任务</div>
                    </div>
                </div><div class="task-list">
                <div class="task-left">
                    <span class="task-type">系统任务</span>
                    <div class="task-complete"><span class="complete-right">100%</span></div>
                </div>
                <div class="task-centre">
                    <div class="task-title">[自我充值]</div>
                    <div class="task-time">2017-02-21</div>
                    <button class="button">领取</button>
                </div>
                <div class="task-right">
                    <div class="task-operate">暂无任务</div>
                </div>
            </div><div class="task-list">
                <div class="task-left">
                    <span class="task-type">系统任务</span>
                    <div class="task-complete"><span class="complete-right">100%</span></div>
                </div>
                <div class="task-centre">
                    <div class="task-title">[玩家充值]</div>
                    <div class="task-time">2017-02-21</div>
                    <button class="button">领取</button>
                </div>
                <div class="task-right">
                    <div class="task-operate">暂无任务</div>
                </div>
            </div>
            </div>-->
            <div class="tab-body" id="tab-map" style="width: 320px;height: 300px;margin: 0 auto;"></div>
            <script>
                var myChart = echarts.init(document.getElementById('tab-map'));
                function randomData() {
                    return Math.round(Math.random()*1000);
                }

                option = {
                    title: {
                        text: '服务机构分布热力图',
//                        subtext: '纯属虚构',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item'
                    },
//                    legend: {
//                        orient: 'vertical',
//                        left: 'left',
//                        data:['iphone3']
//                    },
                    visualMap: {
                        min: 0,
                        max: 2500,
                        left: 'left',
//                        top: 'bottom',
                        text: ['高','低'],           // 文本，默认为数值文本
                        calculable: true
                    },
                    toolbox: {
                        show: true,
                        orient: 'vertical',
                        left: 'right',
                        top: 'center',
                        feature: {
                            dataView: {readOnly: false},
                            restore: {},
                            saveAsImage: {}
                        }
                    },
                    series: [
                        {
                            name: '加盟商',
                            type: 'map',
                            mapType: 'china',
                            roam: false,
                            label: {
                                normal: {
                                    show: false
                                },
                                emphasis: {
                                    show: false
                                }
                            },
                            data:[
                                {name: '北京',value: randomData() },
                                {name: '天津',value: randomData() },
                                {name: '上海',value: randomData() },
                                {name: '重庆',value: randomData() },
                                {name: '河北',value: randomData() },
                                {name: '河南',value: randomData() },
                                {name: '云南',value: randomData() },
                                {name: '辽宁',value: randomData() },
                                {name: '黑龙江',value: randomData() },
                                {name: '湖南',value: randomData() },
                                {name: '安徽',value: randomData() },
                                {name: '山东',value: randomData() },
                                {name: '新疆',value: randomData() },
                                {name: '江苏',value: randomData() },
                                {name: '浙江',value: randomData() },
                                {name: '江西',value: randomData() },
                                {name: '湖北',value: randomData() },
                                {name: '广西',value: randomData() },
                                {name: '甘肃',value: randomData() },
                                {name: '山西',value: randomData() },
                                {name: '内蒙古',value: randomData() },
                                {name: '陕西',value: randomData() },
                                {name: '吉林',value: randomData() },
                                {name: '福建',value: randomData() },
                                {name: '贵州',value: randomData() },
                                {name: '广东',value: randomData() },
                                {name: '青海',value: randomData() },
                                {name: '西藏',value: randomData() },
                                {name: '四川',value: randomData() },
                                {name: '宁夏',value: randomData() },
                                {name: '海南',value: randomData() },
                                {name: '台湾',value: randomData() },
                                {name: '香港',value: randomData() },
                                {name: '澳门',value: randomData() }
                            ]
                        }
                    ]
                };
                myChart.setOption(option);

            </script>
        </div>
    </div>
    <div class="foot-size"></div>
<!--<link rel="stylesheet" href="/wltfagent/Public/home/css/home.css">-->
<div class="foot">
    <a href="<?php echo U('home/index/index');?>">
        <div class="foot-left">
            <p><img src="/wltfagent/Public/home/images/1_31.png"></p>
            <p>我的</p>
        </div>
    </a>
    <a href="<?php echo U('home/RabbitBao/index');?>">
        <div class="foot-center">
            <!--<span class="span_top"></span>-->
            <span class="span_bottom"></span>
        </div>
    </a>
    <a href="<?php echo U('home/support/index');?>">
        <div class="foot-right">
            <p><img src="/wltfagent/Public/home/images/1_34.png"></p>
            <p>支持</p>
        </div>
    </a>
</div>
</body>
</html>
<script>
    $(".game_user").click(function () {
        layer.open({
            content:"暂无消息",
            skin:"msg",
            time:2
        })
    });
    $(".agent_user").click(function () {
        layer.open({
            content:"暂无消息",
            skin:"msg",
            time:2
        })
    });
</script>