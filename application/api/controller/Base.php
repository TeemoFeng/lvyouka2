<?php

/**
 * 基础公共--控制器
 */
namespace app\api\controller;


use app\h5\model\Member;
use think\Controller;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        session('user_id',1);

        if(!session('user_id')){
            return json(['code' => 0, 'msg' => '登录过期请重新登录', 'url' => 'login/wxlogin']);
        }else{
            $this->userId = session('user_id');
            $this->userInfo = Member::getInfo ([
                'id'=>$this->userId
            ]);
        }

    }

    protected function validateCheck($validateName,$data){
        $validate = validate($validateName);
        if (!$validate->check($data)){
            \exception ($validate->getError ());
        }
    }

    //生成邀请码
    function createCode( $length = 8 )
    {

        $str = substr(md5(time()), 0, $length);

        return $str;

    }


}