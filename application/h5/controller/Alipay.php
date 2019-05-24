<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/19 0019
 * Time: 17:33
 */
namespace app\h5\controller;
use app\h5\model\CardList;
use app\h5\model\CardOrder;
use app\h5\model\Order;
use think\Config;
use think\Controller;
use think\Db;
use app\h5\model\LevelCondition;
use app\h5\model\Member;
use app\h5\model\MemberCard;
use app\h5\model\MemberInfo;
use app\h5\model\MemberLevel;
use think\Exception;
use app\h5\model\Journey;
use think\Request;
header("Content-type:text/html;charset=utf-8");
class Alipay extends Controller
{
    public function __construct(Request $request = null){
        parent::__construct($request);
        vendor('alipaywx.wappay.service.AlipayTradeService');
        vendor('alipaywx.wappay.buildermodel.AlipayTradeWapPayContentBuilder');
    }
    //支付宝购买旅游卡支付
    public function alipay($data = null){
        $config = Config::get('alipay');
        $aliPay = new AlipayService($config['app_id'],$config['return_url'],$config['notify_url'],$config['merchant_private_key']);
        $payAmount = floatval($data['price']);
        $outTradeNo = $data['order_number'];
        $orderName = '购买旅游卡';
        $payConfigs = $aliPay->doPay($payAmount,$outTradeNo,$orderName,$config['return_url'],$config['notify_url']);
        $queryStr = http_build_query($payConfigs);
        $queryStr2 = 'https://openapi.alipay.com/gateway.do?'.$queryStr;
        $this->assign('result', $queryStr2);
        $this->assign('order',$data);
        $this->assign('total_amount',$payAmount);

    }

    //支付宝预约出行支付
    public function alipayTrip($data = null){
        $config = Config::get('alipay');
        $aliPay = new AlipayService($config['app_id'],$config['return_trip'],$config['notify_trip'],$config['merchant_private_key']);
        $payAmount = floatval($data['cash']);
        $outTradeNo = $data['order_number'];
        $orderName = '预约押金';
        $payConfigs = $aliPay->doPay($payAmount,$outTradeNo,$orderName,$config['return_trip'],$config['notify_trip']);
        $queryStr = http_build_query($payConfigs);
        $queryStr2 = 'https://openapi.alipay.com/gateway.do?'.$queryStr;
        return $queryStr2;
//        $this->assign('result', $queryStr2);
//        $this->assign('order',$data);
//        $this->assign('total_amount',$payAmount);

    }


    public function alipay_old($data = null, $status = null)
    {
        $config = Config::get('alipay');
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $data['order_number'];
        //订单名称，必填
        $subject = '购买旅游卡';
        //付款金额，必填
        $total_amount = $data['price'];
        //商品描述，可空
        $body = '购买旅游卡';
        //超时时间
        $timeout_express = "1m";
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);
        $payResponse = new \AlipayTradeService($config);
        $result = $payResponse->wapPay($payRequestBuilder, $config['return_url'], $config['notify_url']);
        $this->assign('result',$result);
        $this->assign('order',$data);
        $this->assign('total_amount',$total_amount);
    }

    public function pay($goto = null){
        file_put_contents ( dirname ( __FILE__ ).DIRECTORY_SEPARATOR."./../../log.txt", date ( "Y-m-d H:i:s" ) . "  " . $goto . "\r\n", FILE_APPEND );
        $this->assign('goto', $goto);
        return $this->fetch();
    }

    //支付宝购卡支付回调
    public function notify_url()
    {
        $config = Config::get('alipay');
        $arr = $_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];
            //记录交易详情
            $order_info = json_encode($arr);

            $order = CardOrder::where(['order_number' => $out_trade_no])->find()->toArray();
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                if($order['state'] == 1){
                    $this->cardBuyReturn($order['id'], $trade_no, $order_info);
                }
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $this->cardBuyReturn($order['id'], $trade_no, $order_info);
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

            }

            echo "success";		//请不要修改或删除

        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
    }

    //支付宝订单同步回调
    public function return_url()
    {
        return $this->fetch('success');
    }

    //支付宝预约回调
    public function notify_trip()
    {
        $config = Config::get('alipay');
        $arr = $_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        if($result) {//验证成功
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //记录交易详情
            $order_info = json_encode($arr);

            $order = Order::where(['order_number' => $out_trade_no])->find()->toArray();
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                if($order['state'] == 1){
                    $this->journeyOrderCallBack($order['id'], $trade_no, $order_info);
                }
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $this->journeyOrderCallBack($order['id'], $trade_no, $order_info);
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

            }

            echo "success";		//请不要修改或删除

        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
    }

    //支付宝预约同步回调
    public function return_trip()
    {
        return $this->fetch('success');
    }



    //买卡成功后回调
    public function cardBuyReturn($id, $trade_no, $order_info){
        Db::startTrans ();
        try{
            #获取订单信息判断
            $order = CardOrder::getInfo([
                'id'=>$id,
                'state'=>1
            ],'card_id,buy_num,uid,id,price',true);
            if (!$order){
                \exception('订单不存在或已处理');
            }
            $cardInfo = \app\h5\model\VipCard::getCard ($order->card_id,'*');
            if (!$cardInfo){
                \exception ('卡不存在');
            }
            #获取卡号密码
            $cardList = CardList::getCards ($order->buy_num,$order->card_id);
            #赠送用户卡
            MemberCard::setMemberCard($order->buy_num,$order->uid,$order->card_id,$cardList);
            #修改订单支付状态
            $order::infoEdit($order,['state','payment_time','trade_no', 'order_info'],[
                'state' => 2,
                'payment_time' => time(),
                'trade_no' => $trade_no,
                'order_info' => $order_info,
            ]);

            #发送奖励 升级
            //购买人信息
            $memberInfo = Member::getInfo ([
                'id'=>$order->uid
            ],'parent_idstr,id');
            $idArr = explode (',',substr ($memberInfo->parent_idstr,2,-1));
            $idArr = array_reverse ($idArr);
            $buyMemberLevel = MemberLevel::getInfo ([
                'card_id'=>$order->card_id,
                'uid'=>$order->uid,
            ],'id,state');
            $levelConditions = LevelCondition::where([
                'status'=>1,
                'card_id'=>$order->card_id
            ])->column ('zt_num_condition,team_num_condition,bonus_type,bonus_proportion,level_title','level_number');
            if ($buyMemberLevel && $buyMemberLevel->state == 0){
                $buyMemberLevel::infoEdit ($buyMemberLevel,[
                    'state'
                ],[
                    'state'=>1
                ]);
                MemberLevel::memberUpgrade ($idArr,$order->card_id,$levelConditions);
            }elseif (!$buyMemberLevel){
                MemberLevel::infoAdd ([
                    'uid'=>$order->uid,
                    'card_id'=>$order->card_id,
                    'state'=>1,
                ]);
                MemberLevel::memberUpgrade ($idArr,$order->card_id,$levelConditions);
            }
            if ($cardInfo->open == 1){
                MemberLevel::yjAward($idArr,$order->card_id,$order->price, $levelConditions);
            }
            Db::commit ();
        }catch (Exception $e){
            $info =  $e->getMessage ();
            Db::rollback ();
            //失败存入订单异常表
            $exce = [
                'order_id'  => $id,
                'info'      => $info,
                'status'    => 0,
                'create_time'   => time()
            ];
            Db::name('order_exception')->insert($exce);
        }

    }

    //预约押金支付成功后回调接口
    public function journeyOrderCallBack($id, $trade_no, $order_info){
        Db::startTrans ();
        try{
            #获取订单信息
            $order = Order::getInfo ([
                'id'=>$id,
                'state'=>1,
                'status'=>1
            ],'*',true);
            if (!$order){
                \exception ('订单不存在');
            }
            $jouInfo = Journey::getInfo ([
                'id'=>['eq',$order->j_id],
                'status'=>1
            ]);
            if (!$jouInfo){
                \exception ('行程不存在');
            }
            #获取卡种类信息
            $cardInfo = \app\h5\model\VipCard::getCard ($order->card_id,'*');
            switch ($cardInfo->card_type){
                case 1:
                    #按日期结束的卡
                    #真实花费卡次数为1
                    $state = 3;
                    $sj_card_num = MemberCard::UsecardType1 ($order->uid,$order->card_id);
                    break;
                case 2:
                    #按使用次数
                    $state = 2;
                    $sj_card_num = MemberCard::UsecardType2 ($order->uid,$order->card_id,$order->xz_card_num);
                    break;
                default:
                    \exception ('卡片类型不存在');
                    break;
            }
            #修改订单信息
            $kfNum = 0;
            if ($cardInfo->card_type == 2){
                $count  = Order::where('card_id','eq',$cardInfo->card_id)
                    ->where('state','>',1)
                    ->where('status','eq',1)
                    ->count('id');
                $kfNum = $count%4+1;
            }
            $order::infoEdit ($order,'state,sj_card_num,fukuan_time,kf_num,trade_no,order_info',[
                'state'=>$state,
                'sj_card_num'=>$sj_card_num,
                'fukuan_time'=>time (),
                'kf_num' =>$kfNum,
                'trade_no' => $trade_no,
                'order_info' => $order_info,
            ]);
            $state1 = 1;
            if ($jouInfo->this_people_count + $order->people_count >= $jouInfo->max_people_count){
                $state1 = 0;
            }
            $jouInfo::infoEdit ($jouInfo,'state,this_people_count',[
                'this_people_count'=>$jouInfo->this_people_count + $order->people_count,
                'state'=>$state1
            ]);
            Db::commit ();
        }catch (Exception $e){
            $info =  $e->getMessage ();
            Db::rollback ();
            //失败存入订单异常表
            $exce = [
                'trip_id'   => $id,
                'info'      => $info,
                'status'    => 0,
                'create_time'   => time()
            ];
            Db::name('trip_exception')->insert($exce);
        }

    }


}