<?php


namespace app\wycadmin\validate;


use think\Validate;

class Waiter extends Validate
{
    protected $rule = [
        ['username','require|max:24|min:6','账号不能为空|账号最长24位|账号最短6位'],
        ['password','require|max:16|min:6','密码不能为空|密码最长24位|密码最短6位'],
        ['name','require|max:8','姓名不能为空|姓名最长8位'],
        ['mobile','require|max:11|/^1[3-9]{1}[0-9]{9}$/','请输入手机号码|手机号码最多不能超过11个字符|手机号码格式不正确'],
        ['kf_num','require|between:1,4|integer','订单数字不能为空|订单数字只能填写1-4|订单数字只能填写整数'],
    ];
}