<?php $this->load->view(THEME.'/lake_header');?>
<div class="search">
	<form action="<?=site_url('lake/search')?>" method="get" class="search_box" id="search_box">
		<input type="text" name="keyword" class="keyword" value="<?=$keyword?>"/>
		<input type="hidden" name="type" id="type" value="<?=$type?>"/>
		<input type="hidden" name="stype" id="stype" value="<?=$stype?>"/>
		<a href="javascript:void(0)" class="selected"><?=$stype == 'subject' ? '课件' : '作者'?></a>
		<input type="submit" value="搜&nbsp;&nbsp;索" class="search_btn"/>

		<div class="select_option">
			<a href="javascript:void(0)" rel="subject">课件</a>
			<a href="javascript:void(0)" rel="author">作者</a>
		</div>
	</form>
	<div class="clear">&nbsp;</div>
	<?php if($keyword):?>
	<div class="result">
		<b>关键词：</b><?=$keyword?>&nbsp;&nbsp;&nbsp;<b>结果条数：</b><?=$total_num?>
	</div>
	<?php endif;?>

	<?php
	$url = '';
	if($this->input->get('keyword')) $url .= '&keyword='.$this->input->get('keyword');
	$url .= '#search_box';
	if($stype == 'subject'):
	?>
	<div class="tab clearfix" id="tab">
		<a href="<?=site_url('lake/search?'.$url)?>" <?=$type=='' ? 'class="current"' : ''?>>全部课件</a>
		<?php foreach($subject_kinds as $k=>$v):?>
		<a href="<?=site_url('lake/search?type='.$k.$url)?>" <?=$type==$k ? 'class="current"' : ''?>><?=$v?></a>
		<?php endforeach;?>
	</div>
	<div class="subject_li clearfix cn">
	<?php foreach($lists as $v):?>
		<div class="li">
			<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="img" target="_blank"><img src="<?=get_thumb($v['cover'])?>"/></a>
			<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="a1" target="_blank"><?=$v['title']?></a>
			<?php if($v['gname']):?>
				<a href="<?=site_url('lake/grade?id='.$v['grade'])?>" target="blank" class="gname">[<?=$v['gname']?>]</a>
			<?php endif;?>
			<div class="clear"></div>
			作者：<a href="<?=site_url('lake/author?id='.$v['authorid'])?>" class="author"><?=$v['name']?></a>&nbsp;&nbsp;&nbsp;浏览：<?=$v['hits']?>
		</div>
	<?php
	endforeach;
	if($pagination):
	?>
		<div class="clear10"></div>
	<?php echo $pagination; endif;?>
	</div>
	<?php elseif($stype == 'author'):?>
	<div class="subject_li clearfix" id="lake_tab_c">
	<?php foreach($lists as $v):?>
		<div class="li">
			<a href="<?=site_url('lake/author?id='.$v['id'])?>" class="img"><img src="<?=get_thumb($v['cover'])?>"/></a>
			<div class="name"><?=$v['name']?></div>
			<div class="zc"><?=$v['title']?></div>
		</div>
	<?php
	endforeach;
	if($pagination):
	?>
		<div class="clear10"></div>
	<?php echo $pagination; endif;?>
	</div>
	<?php endif;?>
</div>
<script type="text/javascript">
$(function() {
	$('.search_box .selected').click(function() {
		var item = $('.search_box .select_option');
		item.show('fast', function() {
			$('body').click(function() {
				item.hide();
				$('body').unbind("click");
			});
		});
	})

	$('.search_box .select_option a').click(function() {
		var type = $(this).attr('rel');
		if(type == 'author') $('#type').val('');
		$('#stype').val($(this).attr('rel'));
		$('.search_box .selected').text($(this).text());
	})
})
</script>
<?php $this->load->view(THEME.'/lake_footer');?>