<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/15
 * Time: 12:53
 */

namespace app\h5\controller;


use app\common\controller\index\IndexBase;
use app\h5\model\Journey;
use \app\h5\model\Category as CategoryModel;
use think\Exception;
use app\common\model\Member;
use app\h5\model\MemberCard;

class Category extends Base
{
    private $month = [
        '1'=>'1',
        '2'=>'2',
        '3'=>'3',
        '4'=>'4',
        '5'=>'5',
        '6'=>'6',
        '7'=>'7',
        '8'=>'8',
        '9'=>'9',
        '10'=>'10',
        '11'=>'11',
        '12'=>'12',
    ];

    public function categoryTypeController($cid = 0,$displayType = 0,$thisMonth = 0){
         try{
            if ($displayType == 0 || $displayType == 1 && $cid == 0){
                exception ('参数错误');
            }
            $jGroup = '';
            $year = date('Y');
            if ($displayType == 1){
                if ($thisMonth == 0){
                    $thisMonth = date ('m');
                }
                $category = CategoryModel::getInfo ([
                    'status'=>1,
                    'id'=>$cid,
                    'display_type'=> 1
                ],'id');
                if (!$category){
                    exception ('不存在分类');
                }
                $order = 'week ASC';
                $jGroup = 'week';
                $field = 'week,year,month';
                if($thisMonth!=13){
                    $jWhere['month'] = ['eq',$thisMonth];
                }
                $template = 'category/through_list';
            }elseif ($displayType == 2){
                $categorys = CategoryModel::where([
                  	'cid'	=> 1,
                    'status'=>1,
                    'display_type'=>2
                ])->order ('sort DESC')->column ('title','id');
                if (!empty($categorys) && $cid == 0){
                    $cid = key ($categorys);
                }elseif (!isset($categorys[$cid])){
                    $cid = 0;
                }
                $this->assign ([
                    'categorys'=>$categorys
                ]);
                $order = 'id desc';
                $field = 'id,sc_id';
                $template = 'category/destination_list';
            }
            $jWhere['year'] = date ('Y');
            $jWhere['status'] = ['eq',1];
            $jWhere['cid'] = ['eq',$cid];
            $journeys = Journey::all (function ($query) use($field,$jWhere,$order,$jGroup){
                $query->field($field)->where($jWhere)->order($order)->group($jGroup);
            });
            if ($displayType == 1 ){
                foreach ($journeys as $k =>$v){
                    $v->title = $v->year.'年'.$v->month.'月第'.$v->week.'周';
                    $jWhere['week'] = $v->week;
                    $v->son = Journey::getAll ('id,s_time,e_time,vehicle,sc_id',$jWhere,'week DESC');

                    foreach ($v->son as $journey){
                        $journey->csInfo = model ('Scenic')->where('id',$journey->sc_id)->find();
                    }
                }
            }else{
                foreach ($journeys as $journey){
                    $journey->csInfo = $journey->getScenic('image_path,title,intro,is_duihuan')->find();
                }
            }
            $this->assign ([
                'journeys'=>$journeys,
                'thisMonth'=>$thisMonth,
                'month' =>$this->month,
                'year'  => $year,
                'cid'=>$cid,
                //'category'=>$categorys,
            ]);
            return $this->fetch($template);
        }catch (Exception $e){
           echo $e->getMessage ();
       }
    }

    public function duihuan()
    {
        $list = model ('Scenic')
            ->where ('status','neq',0)
            ->where('is_duihuan',1)
            ->select ();

        $this->assign (
            [
                'journeys'=>$list,
            ]
        );
        return $this->fetch ();
    }

    public function detail($jid = 0)
    {

        try{
            if (is_numeric ($jid)){
                $journey = Journey::getInfo ([
                    'id'=>['eq',$jid],
//                    'status'=>['eq',1]
                ],'id,v_id,mobile,this_people_count,max_people_count,s_time,e_time,sc_id');
                if (empty($journey)){
                    \exception ('');
                }
//                $info = $journey->getScenic('id,circuit,title,star,addr,introduce,notice')->where('status','eq',1)->find();
                $info = $journey->getScenic('id,circuit,title,star,addr,introduce,notice')->find();
                if (empty($info)){
                    \exception ('');
                }
                $banner = $info->ScenicImage()->select();
                $info->circuit = htmlspecialchars_decode($info->circuit);
                $info->introduce = htmlspecialchars_decode($info->introduce);
                $info->notice = htmlspecialchars_decode($info->notice);
            }else{
                \exception ('');
            }
        }catch (Exception $e){
            return $this->redirect ('h5/Index/index');
        }
        $this->assign ([
            'info'=>$info,
            'banner'=>$banner,
            'journey'=>$journey
        ]);
        return $this->fetch();
    }

    /**
     * 兑换专区预约
     */
    public function tripOrder($jid = 0)
    {
        try{
            $jouInfo = $this->getJourStatus($jid);
            MemberCard::memberCardCheck($jouInfo->card_id,$this->userId);
            $card = \app\h5\model\VipCard::getCard($jouInfo->card_id,'card_type,image_path');
            $memberCards = MemberCard::getMemberCards($this->userId,$jouInfo->card_id,$card->card_type,'id,card_number');
        }catch (Exception $e){
            if (request ()->isAjax ()){
                return $this->error ($e->getMessage ());
            }
            return $this->error ($e->getMessage ());
        }
        if (request ()->isAjax ()){
            return $this->success ('预约中..',"category/tripOrder?jid=".$jid,null,0);
        }
        $this->assign ([
            'card'=>$card,
            'jouInfo'=>$jouInfo,
            'memberCards'=>$memberCards
        ]);
        return $this->fetch();
    }
    public function getJourStatus($jid,$peopleCount = 1,$field='*'){
        if (!is_numeric ($jid)){
            \exception ('非法参数');
        }
        #获取行程信息
        $jouInfo = Journey::getInfo ([
            'id'=>$jid,
            'status'=>1
        ],$field);
        if (!$jouInfo || strtotime ($jouInfo->e_time) < time ()){
            \exception ('预约已结束');
        }
        $scInfo = $jouInfo->getScenic('title')->where('status','eq',1)->find();
        if (!$scInfo){
            \exception ('景区已下架');
        }
        $jouInfo->title = $scInfo->title;
        if ($jouInfo->site != '' && $jouInfo->vehicle == 1){
            $jouInfo->site = explode (',',$jouInfo->site);
        }
        #判断是否能继续预约
        if ($jouInfo->max_people_count < $jouInfo->this_people_count+$peopleCount){
            \exception ('预约位置已满');
        }
        return $jouInfo;
    }
}