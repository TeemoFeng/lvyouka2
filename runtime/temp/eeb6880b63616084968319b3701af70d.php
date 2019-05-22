<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/lv.wyc168.com/public/../application/h5/view/trip/trip_order.html";i:1558495756;s:65:"/www/wwwroot/lv.wyc168.com/application/h5/view/public/footer.html";i:1558340461;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<title>订单详情</title>

	<link href="/static/h5/css/mui.min.css" rel="stylesheet">
  	<link rel="stylesheet" href="/static/h5/css/daterangepicker.min.css" />
  
	<link href="/static/h5/css/swiper-3.4.2.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/static/h5/fonts/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/static/h5/css/swiper-3.4.2.min.css" />
	<link rel="stylesheet" href="/static/h5/css/index.css">
	<style type="text/css">
		body,.mui-content{background: #c6c7ce;}
		#youhui .card{padding: 10px;font-size: 14px;}
		#youhui .card img{vertical-align: middle;margin-right: 10px;margin-top: -3px;}
		#youhui .card a{color:#000 ;display: block;}
		#picture  .mui-checkbox input[type=checkbox],#picture  .mui-radio input[type=radio]{top: 25px;left: 0;}
		#picture .mui-input-row label{padding: 0;}
		#picture label {position: relative;}
		#picture label p{position: absolute;bottom: 1vh;right: 14vw;color: #fff;font-size: 12px;transform: scale(0.8)}
      .date-picker-wrapper{z-index: 999;position: fixed!important;top:40%!important}
			
  </style>
</head>
<body >
<header class="mui-bar mui-bar-nav  index-headers">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left color" href='javascript:;'></a>
	<h1 class="mui-title color">订单详情</h1>
</header>
<div class="mui-content mui-scroll-wrapper mui-bottom" id="pullrefresh">
	<div class="mui-scroll">
			<div class="mui-table-view mui-table-view-chevron   " style="background: #c6c7ce;">

				<div class="dingdan">

					<div class="dingdan_header">
						<div class="dd_header mui-ellipsis"><?php echo $jouInfo->title; ?></div>

						<div class="dd_piao">
							<div class="font15">成人票 <span class="mui-pull-right">￥<del id="price"><?php echo $jouInfo->tickets_price; ?></del></span></div>
							<i class="fa fa-check-square"></i>游玩保障 <i class="fa fa-check-square" style="margin-left: 10px;"></i>免费提醒
							<div class="dd_yajin">押金:<b id="yajin"><?php echo $jouInfo->cash; ?></b>元/人</div>
							<div class="dd_tishi">温馨提示</div>
							<div class="dd_num">
								<button class="mui-btn jian" type="button">-</button>
								<input id="num" type="number" name="number" value="1" readonly />
								<button class="mui-btn add" type="button">+</button>
							</div>
						</div>
						<p class="mui-ellipsis">时间：<?php echo $jouInfo->s_time; ?>-<?php echo $jouInfo->e_time; ?></p>
					</div>
					<form method="post" name="orderInfo">
						<input type="hidden" value="<?php echo $jouInfo->id; ?>" name="j_id">
					<div class="dingdan_xx ">
						<div class="dd_header mui-ellipsis">出行人信息 <p>需要写<span class="mui-red"><a class="mui-red" href="javascript:;" id="renshu">1</a>个</span>出行人</p></div>
						<div class="ddxx_main add_list">
							<button type="button"  class="mui-btn mui-btn-blue add_ren">出游人信息</button>
                          <div class="dingdan_xx_list ">
										<div class="mui-input-row">
											<label>姓名</label>
											<input type="text"  name="name[]" placeholder="请输入姓名">
											
										</div>
										<div class="mui-input-row">
											<label>身份证号</label>
											<input type="text" class="mui-input-clear" name="name_id[]" placeholder="请输入身份证号">
										</div>
									</div>
						</div>

					</div>
					<div class="dingdan_xx ">
						<div class="dd_header mui-ellipsis">联系信息</div>
						<div class="ddxx_main">

							<div class="dingdan_xx_list">
								<div class="mui-input-row">
									<label>手机号</label>
									<input type="text"  name="phone" placeholder="<?php echo $jouInfo->mobile; ?>">

								</div>
								<div class="mui-input-row">
                                  <label>出行时间</label>
                                  <input id="date-range0"    size="40" value="" style="line-height: 37px;" placeholder="请选择出行时间" >

                              </div>
                             
							</div>

						</div>

					</div>
						<?php if($card && $card->card_type == 2): ?>
						<div class="dingdan_xx " id="youhui">
							<div class="dd_header mui-ellipsis">优惠</div>
							<div class="card"><a href="#picture"  ><img src="/static/h5/images/ka.png" width="22">选择会员卡</a></div>

						</div>
						<?php endif; if($jouInfo->vehicle): ?>
					<div class="dingdan_xx ">
						<div class="dd_header mui-ellipsis">乘车点</div>
						<div class="mui-input-row flex" style="padding-left: 15px;">
							<?php if(is_array($jouInfo->site) || $jouInfo->site instanceof \think\Collection || $jouInfo->site instanceof \think\Paginator): $i = 0; $__LIST__ = $jouInfo->site;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ao): $mod = ($i % 2 );++$i;?>
							<div class=" mui-radio mui-left">
								<label style="width: 100px;padding-left: 25px;"><?php echo $ao; ?></label>
								<input name="site" type="radio" value="<?php echo $i; ?>" <?php if($i == 1): ?>checked <?php endif; ?> >
							</div>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>

			</div>
	</div>
</div>
<nav class="mui-bar mui-bar-tab offer-bottom">
	<div class="footer">
		<div class="flex">
			<div class="flex-item"><span class="mui-red font15">总额：￥<b id="zong">180</b></span></div>
			<div class="flex-item"><button type="button" id="submite">立即预定</button></div>
		</div>
	</div>

</nav>
<div id="picture" class="mui-popover mui-popover-action ">
		<ul class="mui-table-view">
			<?php if(is_array($memberCards) || $memberCards instanceof \think\Collection || $memberCards instanceof \think\Paginator): $i = 0; $__LIST__ = $memberCards;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?>
			<li class="mui-table-view-cell">
				<div class="mui-input-row mui-checkbox mui-left">
					<label>
						<img src="<?php echo $card->w_image_path; ?>" alt="" width="280" style="margin-left: 30px;">
						<p>编号：<?php echo $co->card_number; ?></p>
					</label>
					<input name="cards[]" value="<?php echo $co->id; ?>"  type="checkbox">
				</div>
			</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<ul class="mui-table-view">
			<li class="mui-table-view-cell ">
				<a href="#picture"><b>确认</b></a >
			</li>
		</ul>

</div>
</form>
<script src="/static/h5/js/jquery-1.11.0.min.js"></script>
<script src="/static/h5/js/mui.js"></script>
<script src="/static/h5/js/index.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/h5/js/swiper-3.4.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/h5/js/ajax_Submit.js" type="text/javascript" charset="utf-8"></script>

<script src="/static/h5/js/moment.min.js" type="text/javascript"></script>
<script src="/static/h5/js/jquery.daterangepicker.min.js"></script>
<script src="/static/h5/js/demo.js"></script>
<script type="text/javascript" charset="utf-8">
    mui.init({
        swipeBack: false,
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
 
</script>
<script>
  	(function(){
      var num=$('#num').val()
      $('#zong').text( parseFloat($('#yajin').text())*num)
      
    })()
    $('.jian')[0].addEventListener('tap',function(){
        var num=$('#num').val()
        console.log(num)
        if(num=='1'){mui.toast('人数不能小于1');return false}
        num=parseFloat(num)-1;
        $('#num').val(num)
        $('#renshu').text(num)
       $('#zong').text( parseFloat($('#yajin').text())*num)
      $('.dingdan_xx_list:last-child').remove()
    })
    $('.add')[0].addEventListener('tap',function(){
        var num=$('#num').val()
        num=parseFloat(num)+1;
        $('#num').val(num)
        $('#renshu').text(num)
       $('#zong').text( parseFloat($('#yajin').text())*num)
       $('.add_list').append('<div class="dingdan_xx_list">'+
            '<div class="mui-input-row">'+
            '<label>姓名</label>'+
            '<input type="text"  name="name[]" placeholder="请输入姓名">'+
            '<i class="del" iid="'+a+'">-</i>'+
            '</div>'+
            '<div class="mui-input-row">'+
            '<label>身份证号</label>'+
            '<input type="text" class="mui-input-clear" name="name_id[]" placeholder="请输入身份证号">'+
            '</div>'+
            '</div>')
    })
    var a=0;
   
    $('#submite')[0].addEventListener('tap',function(){
        // var phone = $('#phone').val();
        // var pattern = /^1[34578]\d{9}$/;
        // if(!pattern.test(phone)){
        //     mui.toast('请输入手机号');
        //     return;
		// }
        var data=$('form').serialize();
        $.ajax({
			url:"<?php echo url('trip/reserveSubmit'); ?>",
			type:'post',
			dataType:'json',
			data:data,
			success:function (res) {
			    console.log(res)
				if (res.data.code == 1){
				    mui.toast(res.msg)
                  setTimeout(function () {
                      location.href="<?php echo url('user/userorder'); ?>";
                  },2000);
					
				}
                mui.toast(res.msg)
            },
		})
    })

   // $('#kabao')[0].addEventListener('tap',function(){
     //   var form1=$('.form1').serializeArray()
       // mui.toast(1)
    //})
</script>
</body>
</html>
