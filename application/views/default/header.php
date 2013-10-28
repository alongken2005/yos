<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>YouShelf</title>
	<base id="headbase" href="<?=base_url()?>">
	<script type="text/javascript" src="common/js/jquery.js"></script>
	<script type="text/javascript">
		$(function() {
			$('.browse').hover(function() {
				$('.header_menu').show();
			});
			
			$('.header_menu').hover(null, function() {
				$('.header_menu').hide();
			});
		})
	</script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/style.css"/>
</head>
<body>
	<div class="header_box">
	<div class="home_header">
		<div class="header_menu">
			<a class="browse png" href="javascript:void(0)">Browse</a>
			<a href="">Home</a>
			<a href="">Genres</a>
			<a href="">Adventure</a>
			<a href="">Biography</a>
			<a href="">Business</a>
			<a href="">Cartoon</a>
			<a href="">Fantasy</a>
			<a href="">Kids & Famay</a>
			<a href="">Lifestyle</a>
			<a href="">Romance</a>
			<a href="">Sci-Fi</a>
		</div>
		<a class="browse png" href="javascript:void(0)">Browse</a>
		<div class="site_name"><a href="<?=site_url()?>">YouShelf</a></div>
	<?php if(isset($user) && $user) { ?>
		<a href="<?=site_url('user/loginout')?>" class="username">退出</a>
		<a href="<?=site_url()?>" class="username"><?=$user['username']?></a>
		<a href="<?=site_url()?>" class="header_pic"><img src="<?=THEME_VIEW?>images/noheader.jpg"/></a>
	<?php } else {?>
		<a href="<?=site_url('user/reg')?>" class="username">注册</a>
		<a href="<?=site_url('user/reg')?>" class="username">登陆</a>
	<?php } ?>
		<form action="" method="get" class="search_box">
			<input type="text" name="keyword" class="keyword"/>
			<input type="submit" value="搜索" class="dosearch"/>
		</form>
	</div>
	</div>