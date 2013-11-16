<?php $this->load->view(THEME.'/lake_header');?>
<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.mousewheel-3.0.4.pack.js')?>"></script>
<script type="text/javascript" src="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.pack.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url('./common/fancybox/jquery.fancybox-1.3.4.css')?>"/>
<div class="subject">
	<div class="overall clearfix">
		<?php
		if($subject['video']):
			$url = $subject['videoType'] == 'local' ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/attach/'.$subject['video']) : $subject['video'];
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
		<?php endif;?>
		<img class="left" src="<?=get_thumb($subject['cover'])?>"/>
		<div class="right">
			<h2><?=$subject['title']?></h2>
			<div class="msg">时 长：<?=$subject['length']?></div>
			<div class="msg">阅 读：<?=$subject['hits']?></div>
			<?php if(isset($author['id'])):?>
			<div class="msg">作 者：<a href="<?=site_url('lake/author?id='.$author['id'])?>"><?=$author['name']?></a></div>
			<?php endif;?>
			<div class="info"><?=t2h($subject['content'])?></div>
		</div>
	</div>

	<div class="tab clearfix">
		<a href="javascript:void(0)" class="a1 current"></a>
		<a href="javascript:void(0)" class="a2"></a>
	</div>
	<div class="clearfix content0">
	<?php foreach($attachs as $v):?>
		<div class="li">
			<?php if($v['filetype'] == 'online' || $v['filetype'] == 'local'):?>
			<a href="#subjecy_video<?=$v['id']?>" class="player_video"></a>
			<a href="#subjecy_video<?=$v['id']?>" class="subject_video"><img src="<?=get_thumb($v['cover'])?>"/></a>
			<?php else:?>
			<a href="<?=site_url('lake/attach_down?id='.$v['id']).'&sid='.$subject['id']?>"><img src="<?=get_thumb($v['cover'])?>"/></a>
			<?php endif;?>

			<h3 class="clearfix"><a href="#subjecy_video<?=$v['id']?>" class="left subject_video"><?=$v['title']?></a><b class="right"><?=$v['other']?></b></h3>
			<?php
				$url = '';
				if($v['filetype'] == 'online' || $v['filetype'] == 'local'):
				$url = $v['filetype'] == 'local' ? base_url("common/flvplayer/flvplayer.swf")."?vcastr_file=".base_url('./data/uploads/attach/'.$v['filename']) : $subject['video'];
			?>
			<div style="display: none;">
				<div id="subjecy_video<?=$v['id']?>" style="width:600px;">
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
	<?php endforeach;?>
	</div>
	<div class="content1 clearfix" style="display: none">
		<?php if(isset($author['id'])):?>
		<div class="author clearfix">
			<img src="<?=get_thumb($author['cover'])?>" class="left"/>
			<div class="right">
				<div class="title">
					<b><?=$author['name']?></b>
					<span><?=$author['title']?></span>
				</div>
				<div class="intro"><?=$author['content']?></div>
			</div>
		</div>

		<h3><?=$author['name']?>的作品</h3>
		<div class="attachs clearfix">
		<?php foreach($author_subject as $v):?>
			<div class="li">
				<a href="333"><img src="<?=get_thumb($v['cover'])?>"/></a>
				<div><?=$v['title']?></div>
			</div>
		<?php endforeach;?>
		</div>
		<?php endif;?>
	</div>
</div>
<div class="comment">
	<h3>留言</h3>
	<?php foreach($com_lists as $v):?>
	<div class="li">
		<div class="cinfo clearfix">
			<?php if($v['authorid']):?>
			<a href="javascript:void(0)" class="user"><?=$users[$v['authorid']]?></a>
			<?php endif;?>
			<a href="#submitForm" rel="<?=$v['id']?>" class="replybtn">回复</a>
			<?php if(isset($uid) && $uid == $v['authorid']):?>
			<a href="">修改</a>
			<a href="<?=site_url('lake/comment_del?id='.$v['id'])?>" class="commentDel">删除</a>
			<?php endif;?>
			<span><?=date('Y-m-d H:i', $v['ctime'])?></span>
		</div>
		<?php if($v['ccontent'] != ''):?>
		<div class="reply">
			<?=$v['ccontent']?>&nbsp;&nbsp;
			<?php if($v['cuid']):?>
			<a href="javascript:void(0)" class="reuser"><?=$users[$v['cuid']]?></a>
			<?php endif;?>
		</div>
		<?php endif;?>
		<div class="cmsg">
			<?=$v['content']?>
		</div>
	</div>
	<?php endforeach;?>
	<?=$pagination ? $pagination : ''?>
	<form action="<?=site_url('lake/comment')?>" method="post" id="submitForm">
		<div class="recontent">
			<div class="replyMark"></div>
			<a class="close" title="取消回复" rel="取消回复"></a>
		</div>
		<textarea name="content"></textarea>
		<input type="hidden" name="typeid" value="<?=$subject['id']?>"/>
		<input type="hidden" name="cid" value="0" id="cid"/>
		<input type="submit" class="btn1" value="留 言"/>
	</form>
</div>
<script type="text/javascript">
$(function(){
	$(".tab a").click(function() {
		$(".tab a").removeClass('current');
		$(this).addClass('current');
		var index = $(".tab a").index($(this));
		$(".subject .content0, .subject .content1").hide();
		$(".subject .content"+index).show();
	})

	$('.replybtn').click(function() {
		$('.recontent .replyMark').text($(this).parent().siblings('.cmsg').text());
		$('#cid').val($(this).attr('rel'));
		$('.recontent').show();
	})

	$('.recontent .close').click(function() {
		$('.recontent').hide();
		$('#cid').val(0);
	})

	//删除留言
	$('.commentDel').click(function() {
		$.get($(this).attr('href'), {}, function(data) {
			if(data === 'ok') {
				$(this).parent().parent().hide();
			} else {
				alert('删除失败');
			}
		});
		return false;
	})

	$(".slide_video, .subject_video, .player_video").fancybox();
});
</script>
<?php $this->load->view(THEME.'/lake_footer');?>