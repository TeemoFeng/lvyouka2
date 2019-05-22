<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/lv.wyc168.com/public/../application/h5/view/user/user_order.html";i:1558286936;s:65:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/header.html";i:1558149238;}*/ ?>
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
	<style>
		html,
		body {
			background-color: #efeff4;
		}
		.mui-bar~.mui-content .mui-fullscreen {
			top: 44px;
			height: auto;
		}
		.mui-pull-top-tips {
			position: absolute;
			top: -20px;
			left: 50%;
			margin-left: -25px;
			width: 40px;
			height: 40px;
			border-radius: 100%;
			z-index: 1;
		}
		.mui-bar~.mui-pull-top-tips {
			top: 24px;
		}
		.mui-pull-top-wrapper {
			width: 42px;
			height: 42px;
			display: block;
			text-align: center;
			background-color: #efeff4;
			border: 1px solid #ddd;
			border-radius: 25px;
			background-clip: padding-box;
			box-shadow: 0 4px 10px #bbb;
			overflow: hidden;
		}
		.mui-pull-top-tips.mui-transitioning {
			-webkit-transition-duration: 200ms;
			transition-duration: 200ms;
		}
		.mui-pull-top-tips .mui-pull-loading {
			/*-webkit-backface-visibility: hidden;
            -webkit-transition-duration: 400ms;
            transition-duration: 400ms;*/

			margin: 0;
		}
		.mui-pull-top-wrapper .mui-icon,
		.mui-pull-top-wrapper .mui-spinner {
			margin-top: 7px;
		}
		.mui-pull-top-wrapper .mui-icon.mui-reverse {
			/*-webkit-transform: rotate(180deg) translateZ(0);*/
		}
		.mui-pull-bottom-tips {
			text-align: center;
			background-color: #efeff4;
			font-size: 15px;
			line-height: 40px;
			color: #777;
		}
		.mui-pull-top-canvas {
			overflow: hidden;
			background-color: #fafafa;
			border-radius: 40px;
			box-shadow: 0 4px 10px #bbb;
			width: 40px;
			height: 40px;
			margin: 0 auto;
		}
		.mui-pull-top-canvas canvas {
			width: 40px;
		}
		.mui-slider-indicator.mui-segmented-control,.mui-segmented-control.mui-scroll-wrapper .mui-scroll {
			background-color: #fff;height: 45px;
		}
		.mui-segmented-control .mui-control-item{line-height: 45px;}
		.mui-segmented-control.mui-scroll-wrapper .mui-control-item{width: 24%;}
		.mui-segmented-control.mui-scroll-wrapper .mui-scroll{width: 100%;}
	</style>

<body>
<header class="mui-bar mui-bar-nav  index-headers">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left color" href='javascript:;' ></a>
	<h1 class="mui-title color">我的订单</h1>

</header>
	<div class="mui-content mui-scroll-wrapper user_dd" id="pullrefresh">
		<div class="mui-scroll" style="padding-bottom: 40px;">
				
			<div class="mui-table-view mui-table-view-chevron  "  >

				<div class=" mui-segmented-control">
					<?php if(is_array($headerList) || $headerList instanceof \think\Collection || $headerList instanceof \think\Paginator): if( count($headerList)==0 ) : echo "" ;else: foreach($headerList as $key=>$value): ?>
					<a class="mui-control-item <?php if($key == $state): ?> mui-active <?php endif; ?>" state="<?php echo $key; ?>"  href="<?php echo url('',['state'=>0]); ?>">
						<?php echo $value; ?>
					</a>
					<?php endforeach; endif; else: echo "" ;endif; ?>

				</div>
			</div>
			<div class="mui-slider-group" style="margin-top: 5px;">
				<div id="item1mobile" class="mui-slider-item mui-control-content mui-active" >		
					<ul class="mui-table-view" >
						<!-- <li class="mui-table-view-cell">
							<div>
								<h4>河南省焦作市修武县云台山风景区</h4>
								<p>订单编号：12314568876463133584</p>
								<p>订单金额：450元</p>
								<p>订单数：3人</p>
								<p>目的地：河南省焦作市修武县云台山风景区</p>
								<p>预约时间：2019-04-08 13:52</p>
								<p class="mui-blue">活动时间：2019-04-10-2019-04-11</p>
								<a href="" class="mui-pull-right">景区详情</a>
								<span>已预约</span>
							</div>
						</li>
						<li class="mui-table-view-cell">
							<div>
								<h4>河南省焦作市修武县云台山风景区</h4>
								<p>订单编号：12314568876463133584</p>
								<p>订单金额：450元</p>
								<p>订单数：3人</p>
								<p>目的地：河南省焦作市修武县云台山风景区</p>
								<p>预约时间：2019-04-08 13:52</p>
								<p class="mui-blue">活动时间：2019-04-10-2019-04-11</p>
								<a href="" class="mui-pull-right">景区详情</a>
								<span class="bg-red">已出行</span>
							</div>
						</li>
 -->
					</ul>
						
					
				</div>
				

			</div>
		</div>
	</div>
<script src="/static/h5/js/jquery-1.11.0.min.js"></script>
<script src="/static/h5/js/mui.min.js"></script>
<script src="/static/h5/js/mui.pullToRefresh.js"></script>
<script src="/static/h5/js/mui.pullToRefresh.material.js"></script>
<script>
	mui.init({
		pullRefresh: {
			container: '#pullrefresh',
			down: {
				style:'circle',
				callback: pulldownRefresh
			},
			up: {
				contentrefresh: '正在加载...',
				callback: pullupRefresh
			}
		}
	});
	function pullupRefresh() {
				
			setTimeout(function() {
				
				a(index,page)
				
			}, 1500);
		}
	function pulldownRefresh() {
		setTimeout(function() {
			window.location.reload()
			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
		}, 1500);
	}
	var index = 0 ;
	var page=1;
	a(index,page)
	function a(index) {
	    var state1 = $('.mui-active').attr('state')
		 $.ajax({
			url:"<?php echo url('user/pageUserOrder'); ?>",
			type:'get',
			dataType:'json',
			//data:{page:page,index:index},
			 data:'state='+state1+'&page='+page,
			success:function (res) {
				mui('#pullrefresh').pullRefresh().endPullupToRefresh(res.data.data.length==0);
				if(res.data.data.length!==0){
					page = parseInt(page)+1
					$.each(res.data.data, function (index, value) {
					 	$('ul.mui-table-view').append('<li class="mui-table-view-cell">'+
					 		'<div>'+
					 			'<h4>'+value.title+'</h4>'+
					 			'<p>订单编号：'+value.order_number+'</p>'+
					 			'<p>订单金额：'+value.cash+'元</p>'+
					 			'<p>订单数：'+value.people_count+'人</p>'+	
					 			'<p>预约时间：'+value.fukuan_time+'</p>'+
					 			'<p class="mui-blue">活动时间：'+value.s_time+'-'+value.e_time+'</p>'+
					 			'<a href="<?php echo url("trip/detail"); ?>?jid='+value.j_id+'" class="mui-pull-right">景区详情</a>'+
					 			'<span class="bg-red">'+value.state+'</span>'+
					 		'</div>'+
					 	'</li>')
					 	
					 })		
				}
				
			}
		})
	}
	var btns=$('.mui-segmented-control a.mui-control-item')
	for (var i = 0;i<btns.length;i++){
		$('.mui-segmented-control a.mui-control-item')[i].addEventListener('tap',function(){
			$('ul.mui-table-view').html('')
			mui('#pullrefresh').pullRefresh().refresh(true);
			index = $(this).index()
			page = 1
			a(index,page)
		})
	}
	
</script>
</body>

</html>