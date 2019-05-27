<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/7
 * Time: 13:01
 */

namespace app\wycadmin\controller;


use app\common\controller\admin\AdminBase;
use app\common\model\OrderPeopleInfo;
use app\wycadmin\model\MemberInfo;
use think\Db;
use think\Exception;

class Order extends AdminBase
{
    public function orderList1($jid = 0){
        $list = \app\wycadmin\model\Order::getInfoPage ([
            'status'=>1,
            'state'=>['>',2],
            'j_id'=>$jid
        ], '*', 'id DESC', $this->listRows);
        foreach ($list as $value){
            $memberInfo = MemberInfo::getInfo([
                'uid'=>$value->uid,
                'bind'=>1
            ],'name,name_id,mobile');
            if (!$memberInfo){
                $value->name = '未绑定';
                $value->mobile = '未绑定';
                $value->name_id = '未绑定';
            }else{
                $value->name = $memberInfo->name;
                $value->mobile = $memberInfo->mobile;
                $value->name_id = $memberInfo->name_id;
            }
            $value->fukuan_time = date('Y-m-d H:i:s',$value->fukuan_time);
            $scId = \app\wycadmin\model\Journey::getValue([
                'id'=>$jid
            ],'sc_id');
            $title = \app\wycadmin\model\Scenic::getValue([
                'id'=>$scId
            ],'title');
            if (empty($this)){
                $value->title = '未知';
            }else{
                $value->title = $title;
            }
        }
        $this->assign (
            [
                'List' => $list,
            ]
        );
        return $this->fetch ();
    }

    public function getOrderPeople($oid = 0){
        if (request()->isAjax()){
            try{
                $infos = OrderPeopleInfo::getAll('name,name_id',[
                    'oid'=>$oid
                ],'id DESC');
            }catch (Exception $e){
                return $this->error('获取失败');
            }
            return $this->success('获取成功','',$infos);
        }
    }


    public function orderList2($kfNum = 0){
        $list = \app\wycadmin\model\Order::getInfoPage ([
            'status'=>1,
            'state'=>['>',1],
            'kf_num'=>['neq',0]
        ], '*', 'id DESC', $this->listRows);
        foreach ($list as $value){
            $memberInfo = MemberInfo::getInfo([
                'uid'=>$value->uid,
                'bind'=>1
            ],'name,name_id,mobile');
            if (!$memberInfo){
                $value->name = '未绑定';
                $value->mobile = '未绑定';
                $value->name_id = '未绑定';
            }else{
                $value->name = $memberInfo->name;
                $value->mobile = $memberInfo->mobile;
                $value->name_id = $memberInfo->name_id;
            }
            $value->fukuan_time = date('Y-m-d H:i:s',$value->fukuan_time);
            $scId = \app\wycadmin\model\Journey::getValue([
                'id'=>$value->j_id
            ],'sc_id');
            $title = \app\wycadmin\model\Scenic::getValue([
                'id'=>$scId
            ],'title');
            if (empty($this)){
                $value->title = '未知';
            }else{
                $value->title = $title;
            }
        }
        $this->assign (
            [
                'List' => $list,
            ]
        );
        return $this->fetch ();
    }


    /**
     * 确认审核
     * @param $id
     */
    public function queren($id = 0){
        Db::startTrans();
        try{
            $order = \app\wycadmin\model\Order::getInfo([
                'id'=>$id,
                'state'=>2,
                'status'=>1
            ],'id,state',true);
            if (!$order){
                \exception('订单不存在或已处理');
            }
            #发短信的通知用户


            #修改订单状态为已预约
            $order::infoEdit($order,['state'],
                [
                    'state'=>3
                ]);
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            return $this->error($e->getMessage());
        }
        return $this->success('审核已通过');
    }
}