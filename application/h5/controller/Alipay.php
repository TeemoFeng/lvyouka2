<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/19 0019
 * Time: 17:33
 */
namespace app\h5\controller;
use app\h5\model\CardOrder;
use think\Config;
use think\Controller;
header("Content-type:text/html;charset=utf-8");
class Alipay extends Controller
{
  
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

    public function alipay_old($data = null, $status = null)
    {
        $config = Config::get('alipay');
        vendor('alipaywx.wappay.service.AlipayTradeService');
        vendor('alipaywx.wappay.buildermodel.AlipayTradeWapPayContentBuilder');

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

    //支付宝回调
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

            $order = CardOrder::where(['order_number' => $out_trade_no])->find()->toArray();
            $card = new Vipcard();

            if($_POST['trade_status'] == 'TRADE_FINISHED') {
              	if($order['state'] == 1){
                	$card->cardBuyReturn($order['id'], $trade_no);
                }
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $card->cardBuyReturn($order['id'], $trade_no);
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

    //同步回调
    public function return_url()
    {
        return $this->fetch();
    }


}