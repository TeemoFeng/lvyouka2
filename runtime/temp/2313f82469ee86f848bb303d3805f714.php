<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"/www/wwwroot/lv.wyc168.com/public/../application/h5/view/vipcard/card_list.html";i:1558425539;s:65:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/header.html";i:1558149238;s:62:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/nav.html";i:1558364049;s:65:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/footer.html";i:1558340461;}*/ ?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title></title>

    <link href="/static/h5/css/mui.min.css" rel="stylesheet">
    <link href="/static/h5/css/swiper-3.4.2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/static/h5/fonts/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/static/h5/css/swiper-3.4.2.min.css"/>
    <link rel="stylesheet" href="/static/h5/css/index.css">
</head>
<style type="text/css">
  body,
  .mui-content {
    background: #fff;
  }
  .huiyuan_header{text-align: center;}
  .huiyuan_header a{color: #333;line-height: 40px;display: block;}
  .huiyuan_header .flex-item.mui-active a{color: #f29b76;border-bottom: 1px solid #f29b76;}

  .gallery-top>div{display: none;}
  .gallery-top>div.mui-active{display: block;}
  .gallery-top div>img{width: 100%;}
  .gallery-top div .flex{margin-top: 10px;}
  .gallery-top div .flex img{width: 25px;height: 22px;margin-right: 10px;}
  .gallery-top div .mui-btns{text-align: center;background: #f29b76;border-radius: 20px;line-height: 40px;}
</style>
<body>
<header class="mui-bar mui-bar-nav  index-headers">

    <h1 class="mui-title color">会员</h1>

</header>
<div class="mui-content mui-scroll-wrapper mui-bottom" id="pullrefresh">
    <div class="mui-scroll">
		<div class="huiyuan_header">
              <div class="flex">
                <div class="flex-item mui-active">
                  <a class="" href="javascript:;">直通车</a>
                </div>
                <div class="flex-item">
                  <a href="javascript:;">目的地</a>
                </div>
              </div>
      	</div>
      	<div class=" gallery-top">
				<?php if(is_array($cards) || $cards instanceof \think\Collection || $cards instanceof \think\Paginator): $i = 0; $__LIST__ = $cards;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?>
					<div class=" assd ">
						<img src="<?php echo $co->w_image_path; ?>" alt="">
						<div class="flex">
							<img src="/static/h5/images/huiyuan/huangguan.png" alt="">
							<div class="card_jieshao">
								<h5>卡种介绍</h5>
								<div><?php echo $co->introduce; ?></div>
							</div>
						</div>
						<div class="flex">
							<img src="/static/h5/images/huiyuan/xunzhang.png" alt="">
							<div class="card_jieshao">
								<h5>卡种攻略</h5>
								<div><?php echo $co->strategy; ?></div>
							</div>
						</div>
						<a href="<?php echo url('vipcard/cardbuy',['id'=>$co->card_id]); ?>" class="mui-btns">点击进入</a>
					</div>
					
          		<?php endforeach; endif; else: echo "" ;endif; ?>
			</div>	  
    </div>
</div>
<nav class="mui-bar mui-bar-tab offer-bottom">
    <a class="mui-tab-item mui-active" href="<?php echo url('index/index'); ?>">
        <img src="/static/h5/images/shouye.png" >
        <span class="mui-tab-label">首页</span>
    </a>

    <!-- <a class="mui-tab-item " href="xingcheng.html">
        <img src="/static/h5/images/xingcheng.png" >
        <span class="mui-tab-label">官网</span>
    </a> -->
    <a class="mui-tab-item " href="<?php echo url('vipcard/cardlist'); ?>">
        <img src="/static/h5/images/huiyuanka.png" >
        <span class="mui-tab-label">在线购买</span>
    </a>
    <a class="mui-tab-item" href="<?php echo url('user/index'); ?>">
        <img src="/static/h5/images/wode.png" >
        <span class="mui-tab-label">我的</span>
    </a>

</nav>
<script src="/static/h5/js/jquery-1.11.0.min.js"></script>
<script src="/static/h5/js/mui.js"></script>
<script src="/static/h5/js/index.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/h5/js/swiper-3.4.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/h5/js/ajax_Submit.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    mui.init({
        pullRefresh: {
            container: '#pullrefresh',
            down: {
                style: 'circle',
                callback: pulldownRefresh
            }
        }
    });

    function pulldownRefresh() {
        setTimeout(function() {
            window.location.reload()
            mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
        }, 1500);
    }
	$('.huiyuan_header .flex-item').each(function(i,btn){
      btn.addEventListener('tap',function(index){
        $(this).addClass('mui-active').siblings().removeClass('mui-active')
        $($('.assd')[i]).addClass('mui-active').siblings().removeClass('mui-active')
      })

    })
    a()
  function a(){
  
    $('.gallery-top .assd:first-child').addClass('mui-active')
  }
			
</script>

</body>
</html>
