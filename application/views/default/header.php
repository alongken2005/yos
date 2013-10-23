<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>YouShelf</title>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
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
		<div class="site_name">YouShelf</div>
		<a href="<?=site_url()?>" class="username">ZhangHao</a>
		<a href="<?=site_url()?>" class="header_pic"><img src="<?=THEME_VIEW?>images/noheader.jpg"/></a>
		<form action="" method="get" class="search_box">
			<input type="text" name="keyword" class="keyword"/>
			<input type="submit" value="搜索" class="dosearch"/>
		</form>
	</div>
	</div>