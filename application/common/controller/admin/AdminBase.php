<?php
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2019/1/17
 * Time: 20:14
 */

namespace app\common\controller\admin;


use app\common\controller\Base;
use app\common\model\PublicModel;
use app\wycadmin\model\OperationRecord;
use think\Db;
use think\Exception;

class AdminBase extends Base
{
  //  protected $Menulist = null;
   // protected $title = null;
    //分页设置
   // protected $page = [];
    //管理员信息
    protected $adminInfo = null;
    //管理员id
    protected $adminId = null;
    //分页每页条数
    protected $listRows = 8;

    protected $recordFileName = 'RecordInfo';

    protected $accessRight = [];

    protected $editKey = 0;

    protected function _initialize()
    {
        $isLogin = $this->isLogin ();
        if(!$isLogin){
            return $this->redirect ('login/index');
        }else{
            parent::_initialize (); // TODO: Change the autogenerated stub
            $this->controller = request ()->controller ();
            $this->action = request ()->action ();
            $this->powerCheck();
            if(strIpos($this->action,'edit') !== false){
                $this->editKey = request()->param('id');
                //var_dump(request()->param('id'));
            }
            $menuList = $this->getMenuList ();
            $title = $this->getTitle ();
            $this->assign(['menuList'=>$menuList,'title'=>$title]);
        }
    }

    /**
     * 修改
     * @param $updateInfos 修改的数据对象
     * @param $status   修改的状态
     * @param string $msg 操作名
     * @param string $field 关键字字段
     */
    protected function adminUpdateStatus($model = null,$idArr = [],$status = 1,$field = ''){
        $msg = $model->modelName;
        switch ($status){
            case $this->normal:
                $type = '启用了';
                break;
            case $this->delete:
                $type = '删除了';
                break;
            case $this->padding:
                $type = '禁用了';
                break;
            default:
                $type = '未知状态';
                break;
        }
        if ($field == ''){
            $content = $type.$msg;
        }else{
            $fieldArr = $model::where('id','in',$idArr)->column($field);
            $str = implode (',',$fieldArr);
            $msg = $type.$msg.'+';
            $content = str_replace ('+',$str,$msg);
        }
        $model::updateStatus($idArr,$status);
        OperationRecord::recordAdd($this->adminInfo,$content);
    }



    public function fileUploads($filename,$glId = 0,$folder = 'scenic',$msg = '请上传图片',$validate = ['jepg','jpg','png']){
        $files = request ()->file ($filename);
        if (!empty($files)){
            $returnArr = [];
            foreach ($files as $k=>$file){
                $info = $file->validate($validate)->move(ROOT_PATH . 'public' . DS .$folder);
                if($info){
                    $returnArr[$k]['image'] = DS.$folder.DS.$info->getSaveName();
                    $returnArr[$k]['gl_id'] = $glId;
                }else{
                    \exception ($file->getError());
                }
            }
            return $returnArr;
        }
        \exception ($msg);
    }



    protected function getSonList($fid = 0,$modelName = 'category',$pidField = 'cid', $target = [])
    {
        $one = model($modelName)->field('id,title')->where($pidField, $fid)->where('status','eq',1)->select();
        static $n = 0;
        foreach ($one as $c) {
            $c->level = $n;
            $target[$c->id] = $c->toArray();
            $n++;
            $target = $this->getSonList($c->id,$modelName,$pidField,$target);
            $n--;
        }
        return $target;
    }

    protected function powerCheck(){
        $adminPower = $this->adminInfo->power;
        if($adminPower != 0){
            $this->accessRight = explode(',',model(config('ModelName.AccessRight'))
                ->where('power_id','eq',$adminPower)
                ->value('menu_id'));
            $menuId = model(config('ModelName.Menu'))
                ->where('action',$this->action)
                ->value('id');
            $switch = false;
            foreach ($this->accessRight as $k=>$value){
                if ($value == $menuId) {
                    $switch = true;
                    break;
                }
                continue;
            }
            if (!$switch){
                $this->error('您没有操作该功能的权限');
            }
            return;
        }
    }

    protected function publicResult($result,$msg,$url){
        if ($result){
            return $this->success($msg.'成功',$url);
        }else{
            return $this->error($msg.'失败');
        }
    }

    protected function validateCheck($validateName,$data){
        $validate = validate($validateName);
        if (!$validate->check($data)){
            return $this->error($validate->getError());
        }
    }
    protected  function getMenuList(){
        if (empty($this->accessRight)){
            $idWhere = '';
        }else{
            $idWhere = ['id'=>['in',$this->accessRight]];
        }
        $modelMenuName = config ('ModelName.Menu');
        $menuList = model($modelMenuName)
            ->field('title,id,controller,action')
            ->where($idWhere)
            ->where('parent_id',0)
            ->where ('status',$this->normal)
            ->order('sort DESC')
            ->select();
        foreach ($menuList as $k=>$v){
            $v->son = model($modelMenuName)
                ->field('title,id,controller,action')
                ->where($idWhere)
                ->where('parent_id',$v->id)
                ->where ('status',$this->normal)
                ->order('sort DESC')
                ->select();
        }
        return $menuList;
    }

    protected function getTitle(){
        $title = model('menu')
        ->where('action',request()->action())
        ->value('title');
        return $title;
    }

    /**
     * 判断是否登录
     */
    protected function isLogin(){
        //获取session
        $user = session (config ('admin.session_user'),'',config ('admin.session_user_scope'));
        if($user && $user->id){
            return $this->AdminStatus($user->id);
        }else{
            return false;
        }
    }

    protected function AdminStatus($adminId)
    {
        $adminInfo = model(config('ModelName.AdminUser'))
            ->where('id',$adminId)
            ->where('status',config('code.status_normal'))
            ->find();
        if($adminInfo){
            $this->adminId = $adminId;
            $this->adminInfo = $adminInfo;
            $this->assign ('adminInfo',$adminInfo);
            return true;
        }else{
            session (null,config ('admin.session_user_scope'));
            return false;
        }
    }
    /**
     * 后台通用修改状态
     * @param $model string 表名
     * @param $deleteArr  array  删除的数据主键id
     * @return int
     */
    protected function modifyStatus($model,$deleteArr,$status){
        try{
            $result = $model
                ->where ('id','in',$deleteArr)
                ->setField ('status',$status);
            return $result;
        }catch (Exception $e){
            $this->error ($e->getMessage ());
        }
    }

    protected function publicModify($modeName,$status,$url,$titleField = 'title'){
            $modifyArr = input ('post.')['id'];
            $model = model($modeName);//config ('ModelName.Menu');
            $result = $this->modifyStatus ($model,$modifyArr,$status);
            $recordPrefix = $this->recordPrefix();
            $operation = $this->getRecordInfo($titleField,$modifyArr,$recordPrefix,$model);
            $this->resultMsg($result,$url,$operation,true);
    }

    /**
     * @param $result
     * @param $url
     * @param bool $record  是否记录
     * @param string $operation
     * @param string $msg
     */
    protected function resultMsg($result,$url,$operation,$record = false,$msg ='操作成功'){
        if ($result){
            if ($record){
                $msg = $this->operationRecordAdd($operation);
            }
            $this->success ($msg,$url);
        }else{
            $this->error('操作失败');
        }
    }

    protected function operationRecordAdd($operation){
        try{
            $result = model (config ('ModelName.OperationRecord'))->add($operation);
            if ($result){
                return '操作成功';
            }else{
                return '操作成功记录失败';
            }
        }catch (Exception $e){
            return '操作成功记录失败';
        }
    }

    /**
     * 记录前缀
     */

    protected function recordPrefix(){
        $str = '管理员'.$this->adminInfo->username;
        $str .= config ("$this->recordFileName.$this->controller")[$this->action];
        return $str;
    }

    /**
     * 获取管理员操作内容
     */
    protected function getRecordInfo($titleField,$keyArr,$content='',$model='',$data = []){
        $objs = $model
            ->field ($titleField)
            ->where ('id','in',$keyArr)
            ->select ();
        foreach ($objs as $key=>$value){
            $content .= $value->$titleField.',';
        }
        $content = substr ($content,0,-1);
        if (mb_strlen ($content) >= 250){
            $content = substr ($content,0,250).'...';
        }
        $data['content'] = $content;
        $data['aid'] = $this->adminId;
        return $data;
    }

    protected function publicAddOrEdit($Key,$model,$data,$msgPrefix,$url,$titleField ='title',$record = true,$param = false,$Suffix = ''){
        try{
            if($Key){
                $result = $model->edit($data,$Key);
            }else{
                $result = $model->add($data);
            }
            $url = $param?$url.'?'.$Suffix.'='.$result:$url;
            $this->addTimeRecord($result,$record,$model,$titleField,$url);
            $this->publicResult($result,$msgPrefix,$url);
        }catch (Exception $e){
            Db::rollback();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 添加时记录
     */
    protected function addTimeRecord($result,$record,$model,$titleField,$url){
        if ($result && $record){
            $content = $this->recordPrefix();
            $data = $this->getRecordInfo($titleField,[$result],$content,$model);
            $msg = $this->operationRecordAdd($data);
            if ($msg != '操作成功'){
                $this->success($url);
            }else{
                return;
            }
        }else{
            return;
        }
    }
}