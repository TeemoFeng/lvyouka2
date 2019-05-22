<?php
/**
 * Created by PhpStorm.
 * User: 唐懿德
 * Date: 2019/5/20
 * Time: 0:50
 */

namespace app\wycadmin\model;


class Member extends \app\common\model\Member
{
    public function getWParentIdAttr($value,$data){
        $parent = self::getInfo([
            'id'=>$data['parent_id']
        ]);
        if (!$parent){
            return '最上级会员';
        }
        return $parent->mobile.'('.$parent->username.')';
    }
}