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
		<a href="<?=site_url()?>" class="logo"></a>
	
		<div class="home_header">
		<?php
		    if($user = get_cookie('user')) {
			    $user = json_decode(authcode($user), true);
		?>
			<a href="<?=site_url('user/info')?>" class="header_pic"><img src="<?=THEME_VIEW?>images/noheader.jpg"/></a>
			<!--a href="<?=site_url('user/loginout')?>" class="username">退出</a-->
			<a href="<?=site_url('user/info')?>" class="username"><?=$user['username']?></a>
			
		<?php } else {?>
			<a href="<?=site_url('user/reg')?>" class="username">注册</a>
			<a href="<?=site_url('user/login')?>" class="username">登陆</a>
		<?php } ?>

		</div>
		<form action="<?=site_url('search/lists')?>" method="get" class="search_box" target="_blank">
			<input type="text" name="keyword" value="<?=isset($keyword) && $keyword ? $keyword : ''?>" class="keyword"/>
			<input type="submit" value=" " class="dosearch"/>
		</form>			
		<div class="header_menu">
			<div class="menu">
				<a href="<?=site_url('search/clists')?>">Genres</a>
				<a href="<?=site_url('search/clists?genre=1')?>">Adventure</a>
				<a href="<?=site_url('search/clists?genre=2')?>">Biography</a>
				<a href="<?=site_url('search/clists?genre=3')?>">Business</a>
				<a href="<?=site_url('search/clists?genre=7')?>">Fantasy</a>
				<a href="<?=site_url('search/clists?genre=4')?>">Kids & Famay</a>
				<a href="<?=site_url('search/clists?genre=16')?>">Romance</a>
				<a href="<?=site_url('search/clists?genre=17')?>">Sci-Fi</a>
			</div>
			<a class="browse m" href="<?=site_url()?>">Home</a>	
		<?php
		if(isset($menu_list) && $menu_list) {
			foreach($menu_list as $v) {
		?>
			<a class="m" href="<?=site_url()?>"><?=$v?></a>	
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
			$('.user_menu').css('width', $('.home_header').width());

			$('.browse').powerFloat({
				eventType: "hover",
				targetMode: "other",
				target: $('.menu'),
				offsets: {x: 14, y: -20},
			});

		<?php if($user) { ?>
			$('.home_header').powerFloat({
				eventType: "hover",
				targetMode: "other",
				target: $('.user_menu'),
				offsets: {x: 0, y: 0},
			});
		<?php } ?>				
		})
	</script>
