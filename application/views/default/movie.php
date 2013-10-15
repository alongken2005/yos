<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>儿童之路-运河电影院</title>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.scrollable.js')?>"></script>
	<script type="text/javascript" src="<?=THEME_VIEW?>/js/slide.js"></script>
	<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.mousewheel-3.0.4.pack.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.pack.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.css')?>"/>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/movie.css"/>
</head>
<body>
<!--div class="color_line"></div>
<table cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="header_left" width="50%"></td>
		<td class="header_center">
			<div class="box" ></div>
		</td>
		<td class="header_right" width="50%"></td>
	</tr>
</table-->
<div class="back_layer">
	<div class="box"><a href="<?=base_url()?>" class="back">返回儿童之路首页</a></div>
</div>
<?php if($focus):?>
<div class="box">
	<div class="movie_slide gamefoucs" >
		<div class="num">
		<?php for($i=0, $length = count($focus); $i<$length; $i++): echo '<a>'.($i+1).'</a>'; endfor;?>
		</div>
		<ul>
		<?php
			foreach ($focus as $k=>$v):
			$url = '';
			$ext = strtolower(pathinfo($v['url'], PATHINFO_EXTENSION));
			if($ext == 'flv' or $ext == 'swf') {
				$href = '#focus_video_'.$k;
				$class= 'class="slide_video_list"';
				$target= '';
			} else {
				$href = $v['url'];
				$target = 'target="_blank"';
				$class = '';
			}
		?>
			<li>
				<a href="<?=$href?>" <?=$target?> <?=$class?>>
					<div class="slide_title"><?=$v['title']?></div>
					<img src="<?=base_url('./data/uploads/pics/'.$v['filename'])?>" alt="<?=$v['filename']?>" />
				</a>
				<?php
				if($ext == 'flv' or $ext == 'swf'):
				$url = $ext == 'flv' ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/stuff/'.$v['url']) : $v['url'];
				?>
				<div style="display: none;">
					<div id="focus_video_<?=$k?>" style="width:600px;">
						<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="600" height="480">
						<param name="movie" value="<?=$url?>">
						<param name="quality" value="high">
						<param name="allowFullScreen" value="true" />
						<embed src="<?=$url?>" allowFullScreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="600" height="480"></embed>
						</object>
					</div>
				</div>
				<?php endif;?>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>
<script type="text/javascript">
    slidshow($('.movie_slide'));
</script>
<?php endif;?>

<div class="box" style="position: relative; padding-top: 50px;">
	<div class="title_shangxi png"></div>
	<div class="shangxi">
		<div class="intro">
	快来加入我们，一起经历多彩神奇、奇妙跌宕的儿童电影世界吧。
		</div>

		<a class="prev browse" style="margin-top: 210px; margin-left: 15px;"></a>
		<div class="scrollable spage" id="scrollable">
			<div class="items">
				<div>
				<?php
					foreach($mviews as $k=>$v):
						$url = '';
						if($k != 0 && $k%4 == 0) { echo "</div><div>"; }
						if($v['movie']) { $url = $v['is_local'] == 1 ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/stuff/'.$v['movie']) : $v['movie'];}
				?>
					<span class="li">
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>"><img src="<?=get_thumb($v['cover1'])?>"/></a>
						<span class="right_info">
							<h3 class="title"><a href="<?=site_url('movie/detail?id='.$v['id'])?>"><?=$v['title']?></a></h3>
							<p class="sintro"><?=cutstr(strip_tags($v['intro']), 138)?>｛<a href="<?=site_url('movie/detail?id='.$v['id'])?>" >查看全文</a>｝</p>
						</span>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin: 210px 15px 0 0;"></a>
	</div>

	<div class="gray_left png"><div class="gray_right png">
		<div class="cinema_intro ong">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;运河少儿电影院是在拱墅区文广新局的领导和浙江儿童阅读推广研究中心的指导下,由拱墅区图书馆和杭州同道教育共同打造的基于运河文化的少年儿童电影院。<br>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;运河少儿电影院集电影观看与欣赏、辩论与习作、微电影制作与研究于一体，旨在培养少年儿童的审美情趣和鉴赏能力，提高少年儿童的艺术文化修养。</div>

		<h2 class="yu_title png"> <span style="float: left;margin:30px 0 0 485px;color: #0580BB; font-size: 18px; font-weight: 600">(点击电影预告索票)</span><!--a href="mailto:ticket@childroad.com?subject=我要索票" style="float:right;margin-right:260px; margin-top: 28px; " class="get_ticket">在线索票（限杭州地区）</a--></h2>
		<div>
		<a class="prev browse" style="margin-left: 10px; margin-top: 60px; margin-right: 8px;"></a>
		<div class="scrollable yugao" id="scrollable">
			<div class="items">
				<div>
				<?php foreach($yugao as $k=>$v): if($k != 0 && $k%3 == 0) { echo "</div><div>";}?>
					<span class="li">
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>">
							<img src="<?=get_thumb($v['cover2'])?>" />
						</a>
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>" style="height:30px;line-height: 30px; text-align: center;"><?=cutstr($v['title'], 16, '')?></a>
						<h6><?=date('m月d日', $v['stime'])?></h6>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin-right: 10px; margin-top: 60px;"></a>
		</div>

		<?php if($huigu):?>
		<div class="clear"></div>
		<h2 class="gu_title png"></h2>
		<div>
		<a class="prev browse" style="margin-left: 10px; margin-top: 90px; margin-right: 7px"></a>
		<div class="scrollable huigu" id="scrollable">
			<div class="items">
				<div>
				<?php foreach($huigu as $k=>$v): if($k != 0 && $k%5 == 0) { echo "</div><div>";}?>
					<span class="li">
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>">
							<img src="<?=get_thumb($v['cover1'])?>" />
						</a>
						<a href="<?=site_url('movie/detail?id='.$v['id'])?>" style="margin-top: 6px; text-align: center;"><?=cutstr($v['title'], 16, '')?></a>
					</span>
				<?php endforeach;?>
				</div>
			</div>
		</div>
		<a class="next browse" style="margin-right: 10px; margin-top: 90px;"></a>
		</div>
		<?php endif;?>

		<div class="clear"></div>
		<h2 class="album_title png"></h2>
		<ul class="album">
		<?php foreach($album as $v):?>
			<li>
				<div class="cover">
					<span class="cron_tl"></span>
					<span class="cron_tr"></span>
					<span class="cron_bl"></span>
					<span class="cron_br"></span>
					<a rel="album_group" href="<?=base_url('./data/uploads/pics/'.$v['filename'])?>"><img src="<?=get_thumb($v['filename'])?>" /></a>
				</div>
			</li>
		<?php endforeach;?>
		</ul>
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
<div class="scrollable-trigger"></div>

<!--[if IE 6]>
<script type="text/javascript" src="<?=base_url('./common/js/fixpng-min.js')?>"></script>
<script type="text/javascript">
DD_belatedPNG.fix('.png, .browse');
</script>
<![endif]-->

<script type="text/javascript">

	$(function() {
		$(".scrollable").scrollable();

		$("a[rel=album_group]").fancybox();
		$(".slide_video_list").fancybox();
	})

	$(window).load(function() {
		$.each($('.album .cover'), function(i, n){
			var width = $(this).width();
			var height = $(this).height();
			$(this).css({'margin-left':(220-width)/2+"px", 'margin-top':(200-height)/2+"px"});
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