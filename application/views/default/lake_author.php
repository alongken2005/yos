<?php $this->load->view(THEME.'/lake_header');?>
<div class="authors">
	<div class="content1 clearfix">
		<div class="author clearfix">
			<img src="<?=get_thumb($author['cover'])?>" class="left"/>
			<div class="right">
				<div class="title">
					<b><?=$author['name']?></b>
					<span><?=$author['title']?></span>
				</div>
				<div class="intro"><?=t2h($author['content'])?></div>
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
	</div>

	<div class="ge"></div>
	<div class="author_title"></div>
	<div class="author_top">
	<?php foreach($author_top as $v):?>
		<div class="li">
			<a href="<?=site_url('lake/author?id='.$v['id'])?>"><img src="<?=get_thumb($v['cover'])?>"/></a>
			<h3><?=$v['name']?></h3>
		</div>
	<?php endforeach;?>
	</div>
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
});
</script>
<?php $this->load->view(THEME.'/lake_footer');?>