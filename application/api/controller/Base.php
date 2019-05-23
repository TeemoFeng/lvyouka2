<?php

/**
 * 基础公共--控制器
 */
namespace app\api\controller;

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
    }

}