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
        $param['appid'] = 'wx581d28a281a532d6';     //小程序id
        $param['secret'] = '873f52270cba4449206f659edce7f80b';    //小程序密钥

        $param['js_code'] = define_str_replace($get['code']);
        $param['grant_type'] = 'authorization_code';
        $http_key = httpCurl('https://api.weixin.qq.com/sns/jscode2session', $param, 'GET');
        $session_key = json_decode($http_key,true);
        //print_r(http_build_query($param));
        if (!empty($session_key['session_key'])) {
            $appid = $param['appid'];
            $encrypteData = $get['encrypteData'];

            $iv = define_str_replace($get['iv']);

            $errCode = decryptData($appid, $session_key['session_key'], $encrypteData, $iv);
            if(!is_array($errCode) || empty($errCode)){
                return json(['code' => 0, 'msg' => '获取用户信息失败']);
            }
            //通用unionid
            $unionid = $errCode['unionId'];
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
                $data['unionid'] = $errCode['unionId'];
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


    public function synUser()
    {
        set_time_limit(0);
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx86f2c25b07aa9f93&secret=0d9c4906445b1660aaa408e5bd7c0d49';
        $param['grant_type'] = 'client_credential';
        $param['appid'] = 'wx86f2c25b07aa9f93';
        $param['secret'] = '0d9c4906445b1660aaa408e5bd7c0d49';
        $http_key = httpCurl('https://api.weixin.qq.com/cgi-bin/token', $param, 'GET');
        $token = "21_mHCrIi9YXC85D6qWjiQAh-TX31QI-BHMWOhoSiuf5lHUwGan7CzQgKKNcx97ydDi5JcFcdqmlB2tWZXXU2A6EuykVdvIOtrShwN-BbjsnDxe96OYe2pdwOjQDeLcN-r7K29NO5QbY009qZ0QYEWeAEAKQI";

        $list = Db::name('member')->select();
        foreach ($list as $k => $v){
            if(!empty($v['openid']) && empty($v['unionid'])){
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$v['openid'].'&lang=zh_CN';

                $res = getJson($url);
                if(isset($res['unionid']) && !empty($res['unionid'])){
                    Db::name('member')->where(['id' => $v['id']])->update(['unionid' => $res['unionid']]);
                }
                echo $v['id'] . PHP_EOL;
                usleep(50000);
            }

        }


    }

}