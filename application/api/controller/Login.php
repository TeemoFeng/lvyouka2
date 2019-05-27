<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/26 0026
 * Time: 10:48
 */

namespace app\api\controller;

use app\h5\model\Member;
use think\Controller;
use think\Db;

class Login extends Controller {

    //微信登录
    public function wxlogin(){
        $get = input('get.');
//        $param['appid'] = 'wx581d28a281a532d6';     //小程序id
//        $param['secret'] = '873f52270cba4449206f659edce7f80b';    //小程序密钥
        $param['appid'] = 'wx1c7191b66723d7fc';     //小程序id
        $param['secret'] = 'dd3759d86a32fd9b58cb5a8e851f6e60';    //小程序密钥
        $param['js_code'] = define_str_replace($get['code']);
        $param['grant_type'] = 'authorization_code';
        $http_key = httpCurl('https://api.weixin.qq.com/sns/jscode2session', $param, 'GET');
        $session_key = json_decode($http_key,true);
        //print_r(http_build_query($param));
        dump($session_key);die;

        $session_key = ['session_key' => 'xKUtHcHi7xkgtFpBaMe+Lg==', 'openid' => 'ot_oo4wKi3nVvlJ3gg-OCyD7vjs4'];
        if (!empty($session_key['session_key'])) {
            $appid = $param['appid'];
            $encrypteData = urldecode($get['encrypteData']);

            $iv = define_str_replace($get['iv']);
            $errCode = decryptData($appid, $session_key['session_key'], $encrypteData, $iv);
            if(!is_array($errCode) || empty($errCode)){
                return json(['code' => 0, 'msg' => '获取用户信息失败']);
            }
            dump($errCode);die;
            //通用unionid
            $unionid = $errCode['unionid'];
            //查询用户是否已经存在
            $user_info = Db::name('member')->where(['unionid' => $unionid])->find();
            if($user_info){
                session('user_id', $user_info['id']);
                $session_id = session_id();
                return json(['code' => 1, 'msg' => '登录成功', 'sessionId' => $session_id, 'url' => 'index/index']);
            }else{
                //添加用户信息
                $member = new Member();
                $data['username'] = $errCode['nickName'];
                $data['password'] = '123456';
                $data['s_password'] = '123456';
                $data['wx_header_image'] = $errCode['avatarUrl'];
                $data['openid'] = $errCode['openId'];
                $data['create_time'] = time();
                $data['last_login_time'] = time();
                $data['unionid'] = $errCode['unionid'];
                $res = $member->add($data);
                if($res == false){
                    return json(['code' => 0, 'msg' => '登录失败']);
                }
                session('user_id', $res);
                $session_id = session_id();
                return json(['code' => 1, 'msg' => '登录成功', 'sessionId' => $session_id, 'url' => 'index/index']);
            }

        }else{
            return json(['code' => 0, 'msg' => '获取session_key失败！']);
        }
    }
}