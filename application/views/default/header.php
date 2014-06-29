<!DOCTYPE html>
<html class="html">
<head>
	<meta charset="utf-8">
	<title>YouShelf</title>
	<base id="headbase" href="<?=base_url()?>">
	<script type="text/javascript" src="common/js/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="common/powerFloat/jquery-powerFloat.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/style.css"/>
	<link rel="stylesheet" href="common/powerFloat/powerFloat.css" type="text/css"/>
</head>
<body>
	<div class="header_fix"></div>
	<div class="header_box">
		<a href="<?=site_url()?>" class="logo"><img src="<?=THEME_VIEW?>images/logo.png"/></a>
		<div class="sv"></div>
		<div class="home_header">
		<?php
		    if($user = get_cookie('user')) {
			    $user = json_decode(authcode($user), true);
		?>
			<a href="<?=site_url('user/info')?>" class="header_pic"><img src="<?=THEME_VIEW?>images/noheader.png"/></a>
			<a href="<?=site_url('user/info')?>" class="username"><?=$user['username']?></a>
		<?php } else {?>
			<a href="<?=site_url('user/reg')?>" class="reg_log">Sign Up</a>
			<a href="<?=site_url('user/login')?>" class="reg_log">Sign In</a>
		<?php } ?>

		</div>
		<form action="<?=site_url('search/lists')?>" method="get" class="search_box" target="_blank">
			<input type="text" name="keyword" value="<?=isset($keyword) && $keyword ? $keyword : ''?>" class="keyword"/>
			<input type="submit" value=" " class="dosearch"/>
		</form>			
		<div class="header_menu">
			<div class="menu">
			<?php if(!(isset($nothome) && $nothome)) { ?>
				<a href="<?=site_url()?>" style="padding-bottom: 5px">Home</a>
			<?php } 
			$result = $this->base->get_data('book_genre', array(), '*', 0, 0, 'dis ASC')->result_array();
			?>
				<a href="javascript:void(0)" class="t">Genres</a>
			<?php foreach($result as $v) { ?>	
				<a href="<?=site_url('search/clists?genre='.$v['id'])?>"><?=$v['name']?></a>
			<?php } ?>
			</div>
			<a class="browse m" href="<?=site_url()?>">Browse</a>	
		<?php
		if(isset($menu_list) && $menu_list) {
			foreach($menu_list as $k=>$v) {
		?>
			<a class="m gomenu" rel="<?=$k?>" href="<?=site_url().'#'.$k?>"><?=$v?></a>	
		<?php		
			}
		}	
		?>
		</div>

		<div class="user_menu">
			<a href="<?=site_url('user/info')?>" style="border-bottom:1px solid #EDEDED">Your Account</a>
			<a href="<?=site_url('user/loginout')?>">Sign Out</a>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$('.gomenu').click(function() {
				$('html,body').animate({scrollTop: $('#'+$(this).attr('rel')).offset().top-95}, 500);
			})
			

			$('.user_menu').css('width', $('.home_header').width());

			$('.browse').hover(function() {

				var offset = $(this).position();
				$('.menu').css({ "left": parseInt(offset.left+14)+"px", "top": parseInt(offset.top+48)+"px" }).show();
				//$('.menu').show();
			})

			$(window).click(function() {
				$('.menu').hide();
			})
			/*
			$('.browse').powerFloat({
				eventType: "hover",
				targetMode: "other",
				target: $('.menu'),
				offsets: {x: 14, y: -20},
			});*/

		<?php if(isset($user) && $user) { ?>
			$('.home_header').powerFloat({
				eventType: "hover",
				targetMode: "other",
				target: $('.user_menu'),
				offsets: {x: 0, y: 0},
			});
		<?php } ?>				
		})
	</script>
