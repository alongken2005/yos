<?php $this->load->view(THEME.'/lake_header');?>
<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.mousewheel-3.0.4.pack.js')?>"></script>
<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.pack.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.css')?>"/>
<div class="subject grade">
	<div class="overall clearfix">
		<?php
		if($row['video'] && $ext = strtolower(pathinfo($row['video'], PATHINFO_EXTENSION))):
			if($ext == 'flv' or $ext == 'swf'):
			$url = $ext == 'flv' ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/stuff/'.$row['video']) : $row['video'];
		?>
		<a href="#focus_video" class="slide_video"></a>
		<div style="display: none;">
			<div id="focus_video" style="width:600px;">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="600" height="480">
				<param name="movie" value="<?=$url?>">
				<param name="quality" value="high">
				<param name="allowFullScreen" value="true" />
				<embed src="<?=$url?>" allowFullScreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="600" height="480"></embed>
				</object>
			</div>
		</div>
		<?php endif; endif;?>
		<img class="cover left" src="<?=get_thumb($row['cover'])?>"/>
		<div class="right">
			<h2><?=$row['name']?></h2>
			<div class="info"><?=$row['content']?></div>
		</div>
	</div>

	<div class="tabs clearfix">
		<a href="<?=site_url('lake/grade?id='.$row['id'])?>" <?=$tab == 'index' ? 'class="current"' : ''?>>会务手册</a>
		<a href="<?=site_url('lake/grade?tab=pic&id='.$row['id'])?>" <?=$tab == 'pic' ? 'class="current"' : ''?>>活动图片</a>
		<a href="<?=site_url('lake/grade?tab=subject&id='.$row['id'])?>" <?=$tab == 'subject' ? 'class="current"' : ''?>>本期课件</a>
	</div>
	<?php if($tab == 'pic'):?>
	<ul class="album">
	<?php foreach($pic_lists as $v):?>
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
	<?=$pagination?>
	<?php elseif($tab == 'subject'):?>
	<div class="clearfix cn">
	<?php foreach($subject_lists as $v):?>
		<div class="li">
			<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="img" target="_blank"><img src="<?=get_thumb($v['cover'])?>"/></a>
			<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="a1" target="_blank"><?=$v['title']?></a>
			<div class="clear"></div>
			作者：<a href="<?=site_url('lake/author?id='.$v['authorid'])?>" class="author"><?=$v['name']?></a>&nbsp;&nbsp;&nbsp;浏览量：<?=$v['hits']?>
		</div>
	<?php endforeach;?>
	</div>
	<?=$pagination?>
	<?php else:?>
	<div class="clearfix chm"><?=$row['chm']?></div>
	<?php endif;?>
</div>
<script type="text/javascript">
	$(function() {
		$(".slide_video").fancybox();
	})
</script>
<?php $this->load->view(THEME.'/lake_footer');?>