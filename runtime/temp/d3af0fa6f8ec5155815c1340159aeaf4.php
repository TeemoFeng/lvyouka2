<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:77:"/www/wwwroot/lv.wyc168.com/public/../application/h5/view/user/user_about.html";i:1558367310;s:65:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/header.html";i:1558149238;s:65:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/footer.html";i:1558340461;}*/ ?>
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
	<title>关于我们</title>
	<body>
		<header class="mui-bar mui-bar-nav  index-headers" >
			<a  class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left color" href='javascript:;' ></a>
			<h1 class="mui-title color ">关于我们</h1>
			
		</header>
		<div class="mui-content mui-scroll-wrapper" id="pullrefresh" >
			<div class="mui-scroll">
				
				<div class="mui-table-view mui-table-view-chevron  user_about ">
					<h3 class="mui-text-center">爱游旅行</h3>
					<div class="mui-text-center" style="margin-bottom: 5px;">公司简介</div>
					<div class="about_jj">
						爱游旅游卡是公司根据业务趋势，以"目的地旅游"为主要的发展方向规划推出"爱游旅游卡"，倡导游客用全新的结算方式轻松完成旅行。持卡游客可在官方小程序内选择任意精品线路出行，免费享受出游目的地的接送站、
	住宿、交通、餐饮、导游及景点首道门票等一站式服务				</div>
					<ul class="mui-list-unstyled">
						<li><label>客服电话</label> <a style="color: #2992ff;" href="tel:400-005-5212" class="mui-pull-right">400-005-5212</a></li>
						<li><label>工作日</label> <span class="mui-pull-right">周一至周五  9:00-18:00</span></li>
						
						<li class="mui-clearfix"><label>公司地址</label> <span class="mui-pull-right" style="width: 75%;">河南省郑州市金水区文化路与任寨北街路南50米互联空间爱游</span></li>
						
					</ul>
				</div>
			
			</div>
		</div>
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
						style:'circle',
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
		
			
		</script>
		
	</body>
</html>
