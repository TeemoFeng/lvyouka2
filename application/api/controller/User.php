<?php

/**
 * 个人中心 - 控制器
 */
namespace app\api\controller;


use app\h5\model\CodePath;
use app\h5\model\Finance;
use app\h5\model\Member;
use app\h5\model\MemberCard;
use app\h5\model\MemberFeedback;
use app\h5\model\MemberInfo;
use app\h5\model\Order;
use app\h5\model\Scenic;
use think\Db;
use think\Exception;
use think\Image;

class User extends Base
{

    //微信登录
    public function wxlogin(){
        $get = input('get.');
        $param['appid'] = '';    //小程序id
        $param['secret'] = '';    //小程序密钥
        $param['js_code'] = define_str_replace($get['code']);
        $param['grant_type'] = 'authorization_code';
        $http_key = httpCurl('https://api.weixin.qq.com/sns/jscode2session', $param, 'GET');
        $session_key = json_decode($http_key,true);
        //print_r(http_build_query($param));
        if (!empty($session_key['session_key'])) {
            $appid = $param['appid'];
            $encrypteData = urldecode($get['encrypteData']);
            $iv = define_str_replace($get['iv']);
            $errCode = decryptData($appid, $session_key['session_key'], $encrypteData, $iv);
            //把appid写入到数据库中
            $data['appid'] = $errCode['openId'];
            $data['nicheng'] = $errCode['nickName'];
            $data['publishtime'] = time();
            $data['sex'] = $errCode['gender'];
            if (false == $this->user->where(['appid' => $data['appid']])->find()) {
                $this->user->insert($data);
            }else{
                $value = $this->user->where(['appid' => $data['appid']])->field('name,tel,birthday,industry,address')->select();
            }
            $array = array_merge_recursive($errCode, $value);
            return json($array);
        }else{
            echo '获取session_key失败！';
        }
    }




    /**
     * 首页
     * @auther enfu
     * @date 2019/5/13 14:57
     */
    public function index()
    {
        try{
            $userInfo = Member::getInfo ([
                'id'=>$this->userId,
                'status'=>1
            ],'username,mobile,integral,valid,zt_count,all_integral,wx_header_image');
            if (!$userInfo){
                 \exception ('账号不存在或已被封禁');
            }
        }catch (Exception $e){
            return json(['code' => 0, 'msg' => $e->getMessage (), 'url' => 'index/index']);
        }

        $dataR = array();
        $dataR['userInfo'] = $userInfo;

        return json($dataR);
    }

    /**
     * 用户卡片
     * @return mixed
     * @auther enfu
     * @date 2019/5/13 17:00
     */
    public function userCard($cardId = 0)
    {
        if(isset($_GET['cardId']))$cardId = $_GET['cardId'];
        if(isset($_GET['type'])){
            if($_GET['type'] == 2){
                try{
                    $list = MemberCard::getInfoPage ([
                        'uid'=>$this->userId,
                        'card_id'=>$cardId,
                        'state'=>1
                    ],'card_number,card_id,password,activate','id ASC',100);
                    foreach ($list as $key=>$value){
                        $imagePath = \app\h5\model\VipCard::getValue ([
                            'card_id'=>$value->card_id,
                            'status'=>1
                        ],'image_path');
                        $value->image_path = request ()->domain ().$imagePath;
                    }
                }catch (Exception $e){
                    return json(['code' => 0, 'msg' => '获取失败']);
                }

                $dataR = array();
                $dataR['list'] = $list;

                return json($dataR);

            }elseif ($_GET['type'] == 1) {
                $herder = \app\h5\model\VipCard::getAll ('title,card_id',[
                'status'=>1
                ],'price asc');
                if (isset($herder[0]) && $cardId == 0){
                    $cardId = $herder[0]->card_id;
                }

                $dataR = array();
                $dataR['cardId'] = $cardId;
                $dataR['header'] = $herder;

                return json($dataR);
            }
        }
        
    }

    /**
     * 用户订单
     * @return mixed
     * @auther enfu
     * @date 2019/5/13 17:00
     */
    public function userOrder($state = 0)
    {
        if(isset($_GET['state']))$state = $_GET['state'];
        $header = [
            '0'=>'全部',
            '2'=>'审核中',
            '3'=>'已预约',
            '4'=>'已出行'
        ];


        $dataR = array();
        $dataR['headerList'] = $header;
        $dataR['state'] = $state;

        return json($dataR);
    }
  
  
    public function pageUserOrder($state){
        if(isset($_GET['state']))$state = $_GET['state'];
        try{
            $where['state'] = ['in',[2,3,4]];
            $where['uid'] = $this->userId;
            switch ($state){
                case 2:
                    $where['state'] = 2;
                    break;
                case 3:
                    $where['state'] = 3;
                    break;
                case 4:
                    $where['state'] = 4;
                    break;
                default:
                    $state = 0;
                    break;
            }
            $Order = Order::getInfoPage ($where,'j_id,order_number,people_count,cash,fukuan_time,state','id desc',4);
            foreach ($Order as $value){
                $journeyInfo = $value->journeyInfo('sc_id,s_time,e_time')->where('status','neq',-1)->find();
                $value->s_time = '未知';
                $value->e_time = '未知';
                $value->title = '未知';
                $arr = [
                    '1'=>'待付款',
                    '2'=>'待审核',
                    '3'=>'已预约',
                    '4'=>'已出行',
                ];
                $value->state = $arr[$value->state];
                $value->fukuan_time = date('Y-m-d H:i:s',$value->fukuan_time);
                if ($journeyInfo){
                    $value->s_time = $journeyInfo->s_time;
                    $value->e_time =  $journeyInfo->e_time;
                    $title = Scenic::getValue ([
                        'id'=>$journeyInfo->sc_id
                    ],'title');
                    if (!empty($title)){
                        $value->title = $title;
                    }
                }
            }

            $dataR = array();
            $dataR['code'] = 1;
            $dataR['Order'] = $Order;

            return json($dataR);
        }catch (Exception $e){
            return json(['code' => 0, 'msg' => $e->getMessage ()]);
        }
        
    }

    /**
     * 用户积分记录
     * @return mixed
     * @auther enfu
     * @date 2019/5/13 17:00
     */
    public function userIntegral(){
         try{
            $userInfo = Member::getInfo ([
                'id'=>$this->userId,
                'status'=>1
            ],'username,mobile,integral,valid,zt_count,all_integral,wx_header_image');
            if (!$userInfo){
                \exception ('账号不存在或已被封禁');
            }
        }catch (Exception $e){
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }


        $dataR = array();
        $dataR['code'] = 1;
        $dataR['userInfo'] = $userInfo;
        return json($dataR);
    }

    public function ajaxUserIntegral(){

        $list = Finance::getInfoPage ([
            'uid'=>$this->userId,
        ],'content,create_time,price,balance,plusormin','id DESC','15');
        if(empty($list)){
            $list = [];
        }

        $dataR = array();
        $dataR['code'] = 1;
        $dataR['list'] = $list;
        return json($dataR);

    }


    /**
     * 我的团队
     * @return mixed
     * @auther enfu
     * @date 2019/5/13 17:00
     */
    public function userTeam()
    {

        try{
            $list = Member::getInfoPage ([
                'parent_idstr'=>['like','%'.$this->userId.'%'],
                'status'=>1
            ],'create_time,username,mobile','id DESC','15');
        }catch (Exception $e){

        }

        $dataR = array();
        $dataR['code'] = 1;
        $dataR['list'] = $list;
        $dataR['userInfo'] = $this->userInfo;

        return json($dataR);
    }

    /**
     * 积分兑换
     * @return mixed
     * @auther enfu
     * @date 2019/5/13 17:00
     */
    public function userExchange()
    {
      try{
            $userInfo = Member::getInfo ([
                'id'=>$this->userId,
                'status'=>1
            ],'username,mobile,integral,valid,zt_count,all_integral,wx_header_image');
            if (!$userInfo){
                \exception ('账号不存在或已被封禁');
            }
        }catch (Exception $e){
            return json(['code' => 0, 'msg' => $e->getMessage(), 'url' => 'index/index' ]);
        }

        $dataR = array();
        $dataR['code'] = 1;
        $dataR['userInfo'] = $userInfo;

        return json($dataR);
    }

    /**
     * 兑换操作方法
     * @auther enfu
     * @date 2019/5/14 9:18
     */
    public function exchange()
    {

    }


    public function userInfo()
    {
        if (request ()->isGet ()) {
            $from = input('param.from');
            $infobind = MemberInfo::getInfo([
                'uid' => $this->userId,
            ]);
            $bind = 0;
            if (!empty($infobind)) {
                $bind = 1;
            }
            $res = [
                'info' => $infobind,
                'bind' => $bind,
                'from' => $from,
            ];
            return json($res);
        }

    }

    public function infoBind(){
        if (request ()->isPost ()){
            $infobind = MemberInfo::getInfo ([
                'uid'=>$this->userId,
            ]);
            $bind = 0;
            if(!empty($infobind)){
                $bind = 1;
            }
            $data = request ()->post ();
            Db::startTrans ();
            if ($bind == 1){
                echo json_encode(array("msg"=>"不能重复绑定信息"));die;
            }
            try{
                $this->validateCheck ('CardActive0',$data);
                $userInfo = Member::getInfo ([
                    'id'=>$this->userId
                ],'*',true);
                $infobind = new MemberInfo();
                $infobind->insert([
                    'addr'=>$data['addr'],
                    'xiangxi' => $data['xiangxi'],
                    'mobile' => $data['mobile'],
                    'name' => $data['name'],
                  	'create_time' => time(),
                    'uid'   => $this->userId,
                    'name_id'=> $data['name_id'],
                    'bind' => 1
                ]);
              
             
                $userInfo::infoEdit ($userInfo,['mobile'],[
                    'mobile'=>$data['mobile']
                ]);
                Db::commit ();
            }catch (Exception $e){
                Db::rollback ();
                return json(['code' => 0, 'msg' => $e->getMessage()]);
            }
            if($data['from'] == 1){
                return json(['code' => 1, 'msg' => '信息绑定成功', 'from' => 1, 'url' => 'vipcard/cardActive']);
            }
            return json(['code' => 1, 'msg' => '信息绑定成功', 'from' => 0]);

        }
    }

    /**
     * 兑换记录
     * @auther enfu
     * @date 2019/5/14 9:18
     */
    public function userExchangeRecord()
    {
        return $this->fetch();
    }

    /**
     * 关于我们
     * @return mixed
     * @auther enfu
     * @date 2019/5/14 9:24
     */
    public function userAbout()
    {
        return $this->fetch();
    }

    /**
     * 意见反馈
     * @return mixed
     * @auther enfu
     * @date 2019/5/14 9:24
     */
    public function userFeedback()
    {
        if (request ()->isGet ()){
            // return $this->fetch();
        }elseif (request ()->isPost ()){
            try{
                $data = request ()->post();
                $this->validateCheck ('UserFeedback',$data);
                MemberFeedback::infoAdd ([
                    'uid'=>$this->userId,
                    'content'=>$data['content']
                ],['create_time','content','uid']);
            }catch (Exception $e){
                return json(['code' => 0, 'msg' => $e->getMessage ()]);
            }
            return json(['code' => 1, 'msg' => '意见反馈成功']);
        }
    }

   /**
     * 意见反馈提交操作
     * @return mixed
     * @auther enfu
     * @date 2019/5/14 9:24
     */
    public function feedback()
    {
        return $this->fetch();
    }


    /**
     * 邀请分享
     * @return mixed
     * @auther enfu
     * @date 2019/5/14 9:24
     */
    public function userInvite($backgroundPicture = 'yaoqing')
    {
        $check = CodePath::getInfo ([
            'id'=>$this->userId
        ],'*');
        $user_info = Member::getInfo ([
            'id'=>$this->userId
        ]);
        $qrData = request ()->domain ().'/h5/user/register?usercode='.$user_info->code;
        if (!$check || empty($check->code_path)){
            $savePath = ROOT_PATH . 'public/qrcode/';
            $webPath = '/qrcode/';
            $qrLevel = 'H';
            $qrSize = '5';
            $savePrefix = 'NickBai';
            $pic = '';
            if($filename = createQRcode($savePath, $qrData, $qrLevel, $qrSize, $savePrefix)){
                $pic = $webPath . $filename;
            }
            $path = $this->image($pic,$backgroundPicture);
            model ('CodePath')
                ->allowField (true)
                ->isUpdate ($check)
                ->save (
                    [
                        'id'=>$this->userId,
                        'code_path'=>$path
                    ]
                );
        }else{
            $path = $check->code_path;
        }
   
        $dataR = array();
        $dataR['code'] = 1;
        $dataR['code_path'] = request ()->domain ().$path;

        return json($dataR);
    }

    //邀请用户注册
    public function register()
    {
        //获取用户携带来的邀请码
        $code = request()->param('usercode',"");
        if(empty($code)){
            return;
        }else{
            //

        }


    }


    public function image($pic,$backgroundPicture)
    {
        $image = Image::open(ROOT_PATH . 'public/static/h5/images/wode/'.$backgroundPicture.'.png');
        $image->water(ROOT_PATH .'public/'. $pic, 8,100)->save(ROOT_PATH.'public/'.$pic);
        return $pic;
    }
}