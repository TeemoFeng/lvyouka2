<?php
/**
 * Created by PhpStorm.
 * User: 唐懿德
 * Date: 2019/5/19
 * Time: 21:53
 */

namespace app\wycadmin\model;


class Order extends \app\common\model\Order
{
    protected $updateTime = false;

    public function getWSiteAttr($value,$data){
        if (empty($data['site'])){
            return '自驾游';
        }
        return $data['site'];
    }
}