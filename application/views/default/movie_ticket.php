<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>儿童之路-运河儿童电影赏析</title>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/movie.css"/>
</head>
<body>
<?php
if($action == 'index'):
	$name = isset($user['first_name']) && $user['first_name'] ? $user['first_name'].$user['middle_name'].$user['last_name'] : $user['email'];
?>
<div class="ticket_index">
	<div class="h2">
		<div class="movie_sprite online_get"></div>
		<div class="movie_sprite today"></div>
		<div class="today_text"><?=date('m月d日').' ('.$weeks[date('N')].')'?></div>
		<div class="hello">Hi，<?=$name?>，欢迎您&nbsp;<a href="<?=site_url('reg')?>">(不是您?)</a></div>
	</div>
	<div class="clear"></div>
	<img class="cover" src="<?=get_thumb($movie['cover1'])?>"/>
	<div class="ticket_box">
		<div class="movie_name"><?=$movie['title']?></div>
		<div class="step1">
			<div class="movie_time">请选择播放时间</div>
			<ul class="ticket_time">
			<?php foreach($tickets as $v):?>
				<li>
					<?=date('m月d日', $v['stime']).'('.$weeks[date('N', $v['stime'])].')'?><br>
					<?=date('H:i', $v['stime'])?><br>
					<?php if($v['used']>=$v['total']):?>
					<a href="javascript:void(0)" class="full">名额已满</a>
					<?php else:?>
					<a href="select<?=$v['id']?>" class="nfull">申请索票</a>
					<?php endif;?>
				</li>
			<?php endforeach;?>
			</ul>
		</div>
		<?php foreach($tickets as $v): $left = $v['total']-$v['used'];?>
		<div class="step2 select<?=$v['id']?>">
			<div class="movie_time" style="font-size:14px;">您选择了：</div>
			<div class="left_ticket"><?=date('m月d日', $v['stime']).'&nbsp;&nbsp;&nbsp;('.$weeks[date('N', $v['stime'])].')&nbsp;&nbsp;&nbsp;'.date('H:i', $v['stime'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目前还有余票 <?=$left?> 张</div>
			<?php if($left>0):?>
			<div class="movie_time" style="font-size:14px;">选择索票张数：</div>
			<div class="num_ticket">
			<?php $lnum = (($left >= 6) ? 6 : $left); for($i=1; $i<=$lnum; $i++):?>
				<input type="radio" name="num" value="<?=$i?>"/> <b><?=$i?></b> 张
			<?php endfor;?>
			</div>
			<div class="shensubmit">
				<a href="" style="margin-top:5px;color: #0199CC" class="fclose">取消索票</a>
				<input type="button" hf="<?=site_url('movie/ticket_submit?id='.$v['id'])?>" class="shen" value="申请索票"/>
			</div>
			<div class="loading">正在发送邮件，可能需要十几秒钟，请耐心等待...</div>
			<?php endif;?>
			<div style="text-align: center; color: #CC3466; margin-top: 10px;">提醒：如果您需要索票 6 张以上，请及时联系工作人员申请索票，0571-87349682</div>
		</div>
		<?php endforeach;?>
	</div>
	<div class="clear"></div>
	<div class="step3">
		<div>
			亲爱的 <span style="padding: 0 5px; color: #0099CC"><?=$name?></span> ，恭喜您：<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您索取的电影赏析活动---<span style="color: #CC3366">《<?=$movie['title']?>》，放映时间<span class="end_time" ></span></span>，成功索票 <span class="end_amount" style="color: #CC3366"></span> 张，索票确认号：<span class="end_num" style="color: #CC3366"></span>，请以索票确认号码在运河电影院入口出取票进场。<br>
			此索票确认号码会以邮件方式发送到您的邮箱，请注意查收。<br><br>
			运河儿童电影院地址：杭州拱墅区图书馆二楼运河儿童电影院（拱墅区政府运河文化广场南面）<br>
			<b style="margin-right: 80px;">联系电话：0571-87349682</b>邮箱：ticket@childroad.com<br>
		</div>
		<div>
			<a href="<?=base_url('movie')?>" class="c1"></a>
			<a href="<?=base_url()?>" class="c2"></a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.nfull').click(function() {
		$('.step2, .step1').hide();
		var selected = $(this).attr('href');
		$('.'+selected+' .num_ticket input:first').attr('checked', true);
		$('.'+selected).show();

		return false;
	})

	$('.shen').click(function() {
		var num = $(this).parent().prev('.num_ticket').children("input[name='num']:checked").val();
		$('.loading').show();
		$(this).attr('disabled', 'disabled').addClass('dis');
		$.post($(this).attr('hf'), {num:num, name:"<?=$name?>", movie:"<?=$movie['title']?>"}, function(data) {
			if(data.result == 'yes') {
				$('.step3 .end_time').html(data.endtime);
				$('.step3 .end_amount').html(data.num);
				$('.step3 .end_num').html(data.uniqid);
				$('.ticket_box, .ticket_index .cover').hide();
				$('.step3').show();
			} else {
				alert('申请失败，请重试')
				$('.shen').attr('disabled', '').removeClass('dis');
				$('.loading').hide();
			}
		}, 'json')
		return false;
	})
</script>
<?php endif; ?>
</body>
</html>
