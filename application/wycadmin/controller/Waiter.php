<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/14
 * Time: 9:24
 */

namespace app\wycadmin\controller;

use app\common\controller\admin\AdminBase;
use app\wycadmin\model\Waiter as WaiterModel;
use app\wycadmin\model\OperationRecord;
use app\wycadmin\model\Scenic as ScenicModel;
use think\Db;
use think\Exception;

class Waiter extends AdminBase
{
    /**
     * 客服列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function waiterList($pid = 0)
    {
        $list = WaiterModel::getInfoPage ([
            'status' => ['neq', $this->delete],
        ], '*', 'id DESC', $this->listRows);
        $this->assign (
            [
                'pid' => $pid,
                'List' => $list,
            ]
        );
        return $this->fetch ();
    }


    //添加客服
    public function waiterAdd($id = 0)
    {
        if (request ()->isGet ()) {
            return $this->fetch ();
        } else if (request ()->isPost ()) {
            $data = input ('post.');
            $this->validateCheck ('Waiter', $data);
            Db::startTrans ();
            try {
                $result = $this->weiyiCheck($data['username']);
                if ($result){
                    \exception('该账号已存在,请换个账号试试');
                }
                WaiterModel::infoAdd ($data, [
                    'username', 'mobile','kf_num', 'name', 'create_time','password'
                ]);
                OperationRecord::recordAdd ($this->adminInfo, '新增了客服' . $data['name'] . '的客服');
                Db::commit ();
            } catch (Exception $e) {
                Db::rollback ();
                return $this->error ($e->getMessage ());
            }
            return $this->success ('客服添加成功', "waiter/waiterlist", null, 2);
        }
    }




    public function waiterEdit($id = 0)
    {
        if (request ()->isGet ()) {
            $waiterInfo = WaiterModel::get (['id' => $id]);
            $this->assign (['info' => $waiterInfo]);
            return $this->fetch ();
        } elseif (request ()->isPost ()) {
            $data = input ('post.');
            $this->validateCheck ('Waiter', $data);
          	Db::startTrans();
            try {
                $waiterInfo = WaiterModel::getInfo ([
                    'id' => $data['id']
                ], '*', true);
                if (!$waiterInfo) {
                    \exception ('修改信息不存在');
                }
                $result = $this->weiyiCheck($data['username'],$data['id']);
                if ($result){
                    \exception('该账号已存在,请换个账号试试');
                }
                $waiterInfo::infoEdit ($waiterInfo, [
                    'username', 'mobile','kf_num', 'name','password'
                ], $data);
                OperationRecord::recordAdd ($this->adminInfo, '修改了客服' . $data['name'] . '的资料');
                Db::commit ();
            } catch (Exception $e) {
                Db::rollback ();
                return $this->error ($e->getMessage ());
            }
            return $this->success ('客服修改成功', "waiter/waiterlist", null, 2);
        }
    }

    private function weiyiCheck($username,$type = 0){
        switch ($type){
            case $type == 0:
                $result = WaiterModel::getInfo([
                    'username'=>$username,
                    'status'=>['neq',-1]
                ],'id');
                break;
            case $type != 0:
                $result = WaiterModel::getInfo([
                    'id'=>['neq',$type],
                    'username'=>$username,
                    'status'=>['neq',-1]
                ],'id');
                break;
            default:
                $result = false;
        }
        return $result;
    }


    /**
     * 修改
     */
    public function waiterStatusUpdate()
    {
        $data = request ()->post ();
        if (empty($data['id'])) {
            return $this->success ('操作成功', '', null, 2);
        }
        Db::startTrans ();
        try {
            $model = new WaiterModel();
            $this->adminUpdateStatus ($model, $data['id'], $data['status']);
            Db::commit ();
        } catch (Exception $e) {
            Db::rollback ();
            return $this->error ($e->getMessage ());
        }
        return $this->success ('操作成功', '', null, 2);
    }
}