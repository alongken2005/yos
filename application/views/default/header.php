<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>YouShelf</title>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/style.css"/>
</head>
<body>
	<div class="header_box">
	<div class="home_header">
		<a class="browse png" href="<?=base_url()?>" target="_blank">Browse</a>
		<div class="site_name">YouShelf</div>
		<a href="<?=site_url()?>" class="username">ZhangHao</a>
		<a href="<?=site_url()?>" class="header_pic"><img src="<?=THEME_VIEW?>images/noheader.jpg"/></a>
		<form action="" method="get" class="search_box">
			<input type="text" name="keyword" class="keyword"/>
			<input type="submit" value="搜索" class="dosearch"/>
		</form>
	</div>
	</div>