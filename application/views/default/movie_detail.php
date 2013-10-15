<!DOCTYPE html>
<html style="background: url(<?=THEME_VIEW?>/images/bg.jpg);">
<head>
	<meta charset="utf-8">
	<title>儿童之路-运河儿童电影赏析</title>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.scrollable.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.mousewheel-3.0.4.pack.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.pack.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.css')?>"/>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/movie.css"/>
</head>
<body style="background: url(<?=THEME_VIEW?>/images/top_bg.jpg) repeat-x;">
<div class="box">
	<div class="room_header">
		<a class="logo png" href="<?=base_url()?>" target="_blank"></a>
		<!--div class="menu">
			<div class="first_menu">
				<a href="#" class="room1 png"></a>
				<a href="#" class="room2 active png"></a>
				<a href="#" class="room3 png"></a>
			</div>
			<div class="sec_menu png">
				<a href="#" class="sec1 png"></a>
				<a href="<?=site_url('movie')?>" class="sec2 active png"></a>
			</div>
		</div-->
	</div>
	<div class="movie_leader"><a href="<?=base_url()?>">儿童之路首页</a> > <a href="<?=site_url('movie')?>">运河儿童电影赏析</a> > <a href="#"><?=$movie['title']?></a></div>
	<div class="clear"></div>
	<div class="movie_box">
		<div class="cover">
			<img src="<?=get_thumb($movie['cover1'])?>"/>
		</div>

		<div class="movie_intro">
			<h2><?=$movie['title']?></h2>
			<div class="tools_menu">
			<?php if(stripos($movie['area'], '1') !== false):?>
				<!--a href="mailto:ticket@childroad.com?subject=我要索票" class="get_ticket">索票</a-->
				<a href="<?=site_url('movie/get_ticket?id='.$movie['id'])?>" class="movie_sprite get_ticket">索票</a>
			<?php endif;?>
				<a href="mailto:review@childroad.com" class="write_review">影评</a>
			</div>
			<div class="clear"></div>
			<?php if(stripos($movie['area'], '1') !== false):?>
			<div class="prev_info">放映时间：<?=date('Y年m月d日 H:i', $movie['stime'])?>&nbsp;&nbsp;<?=$movie['producer']?></div>
			<?php endif;?>
			<div class="content">
				<b>影片简介：</b>
				<?php
				$content = strip_tags($movie['intro']);
				$len = 300;
				echo cutstr($content, $len);
				if(mb_strlen($content, 'utf-8') > $len):
				?>
				&nbsp;{<a href='#all_intro' class="all_intro">查看全文</a>}
				<div style="display: none;">
					<div id="all_intro" style="width:800px;"><?=$movie['intro']?></div>
				</div>
				<?php endif; ?>
			</div>
			<div class="menu_list">
				<!--a href="#m1" class="m1"></a-->
				<a href="#m2" class="m2"></a>
				<a href="#m3" class="m3"></a>
				<!--a href="#m4" class="m4"></a>
				<a href="" class="m5"></a-->
			</div>

			<?php if((stripos($movie['area'], '2') !== false || stripos($movie['area'], '4') !== false) && $movie['movie']): $url = $movie['is_local'] == 1 ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('data/uploads/stuff/'.$movie['movie']) : $movie['movie'];?>
			<a href="#video_play" class="play"></a>
			<div style="display: none;">
				<div id="video_play">
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="600" height="480">
					<param name="movie" value="<?=$url?>">
					<param name="quality" value="high">
					<param name="allowFullScreen" value="true" />
					<embed src="<?=$url?>" allowFullScreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="600" height="480"></embed>
					</object>
				</div>
			</div>
			<?php endif;?>
		</div>
	</div>

	<div class="gray_left png"><div class="gray_right png">
		<?php if($mviews):?>
		<h2 class="detail_xin png" id="m1"></h2>
		<div>
		<a class="prev browse" style="margin-left: 10px; margin-right: 5px; margin-top: 60px;"></a>
		<div class="scrollable yugao" id="scrollable">
			<div class="items">
				<div>
				<?php
					foreach($mviews as $k=>$v):
						$url = '';
						if($k != 0 && $k%3 == 0) { echo "</div><div>";}
						if($v['video']) { $url = $v['is_local'] == 1 ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/stuff/'.$v['video']) : $v['video'];}
				?>
					<span class="li">
						<a href="#xinshang_<?=$k?>" class="detail_xinshang">
							<img src="<?=get_thumb($v['cover2'])?>" />
						</a>
						<a href="#xinshang_<?=$k?>" class="detail_xinshang" style="height:30px;line-height: 30px; text-align: center;"><?=cutstr($v['title'], 16, '')?></a>
						<div style="display: none;">
							<div id="xinshang_<?=$k?>" style="width:600px;">
							<?php if(isset($url) && $url):?>
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="600" height="480">
								<param name="movie" value="<?=$url?>">
								<param name="quality" value="high">
								<param name="allowFullScreen" value="true" />
								<embed src="<?=$url?>" allowFullScreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="600" height="480"></embed>
								</object>
								<div class="clear10"></div>
							<?php endif;?>
								<div><?=$v['intro']?></div>
							</div>
						</div>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin-right: 10px; margin-top: 60px;"></a>
		</div>
		<?php endif;?>

		<div class="clear"></div>
		<h2 class="detail_hua png" id="m2"></h2>
		<div>
		<a class="prev browse" style="margin-left: 10px; margin-right: 5px; margin-top: 60px;"></a>
		<div class="scrollable yugao" id="scrollable">
			<div class="items">
				<div>
				<?php
					foreach($mclips as $k=>$v):
						$url = '';
						if($k != 0 && $k%3 == 0) { echo "</div><div>";}
						if($v['video']) { $url = $v['is_local'] == 1 ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/stuff/'.$v['video']) : $v['video'];}
				?>
					<span class="li">
						<a href="#pianhua_<?=$k?>" class="detail_pianhua">
							<img src="<?=get_thumb($v['cover2'])?>" />
						</a>
						<a href="#pianhua_<?=$k?>" class="detail_pianhua" style="height:30px;line-height: 30px; text-align: center;"><?=$v['title']?></a>

						<div style="display: none;">
							<div id="pianhua_<?=$k?>">
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="600" height="480">
								<param name="movie" value="<?=$url?>">
								<param name="quality" value="high">
								<param name="allowFullScreen" value="true" />
								<embed src="<?=$url?>" allowFullScreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="600" height="480"></embed>
								</object>
							</div>
						</div>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin-right: 10px; margin-top: 60px;"></a>
		</div>

		<div class="clear"></div>
		<h2 class="detail_pic png" id="m3"></h2>
		<div>
		<a class="prev browse" style="margin-left: 10px; margin-right: 5px; margin-top: 60px;"></a>
		<div class="scrollable yugao" id="scrollable">
			<div class="items">
				<div>
				<?php foreach($mimage as $k=>$v): if($k != 0 && $k%3 == 0) { echo "</div><div>";}?>
					<span class="li">
						<a href="<?=base_url('./data/uploads/pics/'.$v['filename'])?>" rel="album_group">
						<img src="<?=get_thumb($v['filename'])?>" />
						</a>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin-right: 10px; margin-top: 60px;"></a>
		</div>

		<?php if($likes):?>
		<div class="clear"></div>
		<h2 class="detail_like png"></h2>
		<div>
		<a class="prev browse" style="margin-left: 10px; margin-right: 5px; margin-top: 90px;"></a>
		<div class="scrollable huigu" id="scrollable">
			<div class="items">
				<div>
				<?php foreach($likes as $k=>$v): if($k != 0 && $k%5 == 0) { echo "</div><div>";}?>
					<span class="li">
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>">
							<img src="<?=get_thumb($v['cover1'])?>" />
						</a>
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>" style="margin-top: 6px; text-align: center;"><?=$v['title']?></a>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin-right: 10px; margin-top: 90px;"></a>
		</div>
		<?php endif;?>

	</div></div>

	<div class="bracket png"></div>
</div>
<table cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="bottom_left" width="50%"></td>
		<td class="bottom_center">
			<div class="box" style="text-align: center; font-family: '微软雅黑'; font-size: 20px; font-weight: 600; margin-top: 30px;"><a href="<?=base_url()?>" target="_blank" style="color: #3399CC;">成为儿童之路会员，有机会参加更多活动！ </a></div>
		</td>
		<td class="bottom_right" width="50%"></td>
	</tr>
</table>

<!--[if IE 6]>
<script type="text/javascript" src="<?=base_url('./common/js/fixpng-min.js')?>"></script>
<script type="text/javascript">
DD_belatedPNG.fix('.png, .browse');
</script>
<![endif]-->

<script type="text/javascript">
	$(function() {
		$(".scrollable").scrollable();
		$(".shangxi_intro").fancybox();
		$(".play").fancybox();
		$(".detail_xinshang").fancybox();
		$(".detail_pianhua").fancybox();
		$(".all_intro").fancybox();
		$(".get_ticket").fancybox();

		$("a[rel=album_group]").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over'
		});
	})

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-26645818-3']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
</body>
</html>