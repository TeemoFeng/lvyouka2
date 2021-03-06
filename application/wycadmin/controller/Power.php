<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 17:20
 */

namespace app\wycadmin\controller;


use app\common\controller\admin\AdminBase;
use think\Exception;

class Power extends AdminBase
{
    protected $PowerModelName = '';
    protected $MenuModelName = '';
    protected $AccessRightModelName = '';
    
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->PowerModelName = config('ModelName.Power');
        $this->MenuModelName = config('ModelName.Menu');
        $this->AccessRightModelName = config('ModelName.AccessRight');
        //var_dump($this->action);
    }

    public function powerList(){
        $powerList = model($this->PowerModelName)
            ->where('status','neq',$this->delete)
            ->paginate($this->listRows,false,$this->page);
        $this->assign(['powerList'=>$powerList]);
        return $this->fetch ();
    }

    /*
     * 添加角色
     */
    public function powerAdd(){
        if (request()->isGet()) {
            return $this->fetch();
        }elseif (request()->isPost()){
            $data = request()->post();
            $this->validateCheck($this->PowerModelName,$data);
            $data['status'] = $this->normal;
            //$newData = $this->fieldFilter('status,title,content',$data);
            $Power = model($this->PowerModelName);
            $this->publicAddOrEdit($this->editKey,$Power,$data,'用户组添加','power/permissionAccess','title',true,true,'powerId');
        }
    }
    /**
     * 修改角色
     */
    public function powerEdit($id){
        if (request()->isGet()) {
            $powerInfo = model ($this->PowerModelName)->get(['id'=>$id]);
            $this->assign(['powerInfo'=>$powerInfo]);
            return $this->fetch();
        }elseif (request()->isPost()){
            $data = request()->post();
            $this->validateCheck($this->PowerModelName,$data);
            $Power = model($this->PowerModelName);
            $this->publicAddOrEdit($this->editKey,$Power,$data,'用户组修改','power/permissionAccess','title',true,true,'powerId');
        }
    }


    /**
     *
     * 权限访问
     */
    public function permissionAccess($powerId = 0){
        if(request()->isGet()){
            $Menu = model($this->MenuModelName);
            $prowerListOne = $this->powerSelect(0,$Menu);
            $MenuIdStr = model($this->AccessRightModelName)
            ->where('power_id',$powerId)
            ->value('menu_id');
            if (empty($MenuIdStr)){
                $MenuIdArr = [];
            }else{
                $MenuIdArr = explode(',',$MenuIdStr);
            }
            foreach ($prowerListOne as $k=>$v){
                $resultOne =  $Menu->get(['parent_id'=>$v->id]);
                if (array_search($v->id,$MenuIdArr) !== false){
                    $v->check = 1;
                    $MenuIdArr = array_diff($MenuIdArr, [$v->id]);
                }else{
                    $v->check = 0;
                }
                if($resultOne){
                    $v->sonOne = $this->powerSelect ($v->id,$Menu);
                    foreach ($v->sonOne as $kk=>$vv){
                        $resultTwo =  $Menu->get(['parent_id'=>$vv->id]);
                        if (array_search($vv->id,$MenuIdArr) !== false){
                            $vv->check = 1;
                            $MenuIdArr = array_diff($MenuIdArr, [$vv->id]);
                        }else{
                            $vv->check = 0;
                        }
                        if ($resultTwo){
                            $vv->sonTwe = $this->powerSelect ($vv->id,$Menu);
                            foreach ($vv->sonTwe as $kkk=>$vvv){
                                if (array_search($vvv->id,$MenuIdArr) !== false){
                                    $vvv->check = 1;
                                    $MenuIdArr = array_diff($MenuIdArr, [$vvv->id]);
                                }else{
                                    $vvv->check = 0;
                                }
                            }
                        }else{
                            $vv->sonTwe = null;
                        }
                    }
                }else{
                    $v->sonOne = null;
                }
            }
            $this->assign (['prowerListOne'=>$prowerListOne,'powerId'=>$powerId]);
            return $this->fetch ();
        }elseif (request()->isPost()){
            $data = request()->post();
            try{
                $AccessRight = model($this->AccessRightModelName);
                $Power = model($this->PowerModelName);
                $existence = $AccessRight->get(['power_id'=>$data['power_id']]);
                $data['menu_id'] = implode(',',$data['rules']);
                $data['title'] = $Power->where('id',$powerId)->value('title');
                if($existence){
                    //return $this->error('');
                    $this->publicAddOrEdit($existence->id,$AccessRight,$data,'权限访问修改','power/powerlist');
                }else{
                    $this->publicAddOrEdit(0,$AccessRight,$data,'权限访问修改','power/powerlist');
                }
            }catch (Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @param int $parentId
     * @return mixed
     * 查询出所有菜单
     */
    protected function powerSelect($parentId = 0,$Menu){
        $prowerList = $Menu
            ->field ('title,id')
            ->where ('parent_id',$parentId)
            ->where ('status',$this->normal)
            ->select ();
        return $prowerList;
    }

    /**
     * 删除权限
     */
    public function powerDelete(){
        $this->publicModify($this->PowerModelName,$this->delete,'power/powerlist');
    }
    /**
     * 禁用权限
     */
    public function powerPadding(){
        $this->publicModify($this->PowerModelName,$this->padding,'power/powerlist');
    }

    /**
     * 启用权限
     */

    public function powerEnable(){
        $this->publicModify($this->PowerModelName,$this->normal,'power/powerlist');
    }
}