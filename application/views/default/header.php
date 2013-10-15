<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>儿童之路</title>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/style.css"/>
</head>
<body>
	<div class="box">
		<div class="room_header">
			<a class="logo png" href="<?=base_url()?>" target="_blank"></a>
			<div class="menu">
				<div class="first_menu">
					<a href="javascript:void(0)"></a>
					<!--a href="#" class="room1 png"></a-->
					<a href="#" class="room2 active png"></a>
					<!--a href="#" class="room3 png"></a-->
				</div>
				<div class="sec_menu png">
					<a href="javascript:void(0)"></a>
					<a href="<?=site_url('movie')?>" class="sec2 png <?=$this->uri->segment(1) == 'movie' ? 'active' : ''?>"></a>
					<a href="<?=site_url('lake')?>" class="sec3 png <?=$this->uri->segment(1) == 'lake' ? 'active' : ''?>"></a>
				</div>
			</div>
		</div>