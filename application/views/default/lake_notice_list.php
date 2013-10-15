<?php $this->load->view(THEME.'/lake_header');?>
<div class="lakeNotice ">
	<div class="nli clearfix">
		<div class="leader">浙江儿童阅读推广研究中心 | 文件通知</div>
	<?php foreach($lists as $v):?>
		<div class="li clearfix">
			<a class="left <?=$v['mark'] == 1 ? 'red' : ''?>" href="<?=site_url('lake/notice_detail?id='.$v['id'])?>">•&nbsp;<?=$v['title']?></a>
			<div class="right"><?=date('Y-m-d', $v['ctime'])?></div>
		</div>
	<?php endforeach;?>
	<?=$pagination?>
	</div>
</div>
<?php $this->load->view(THEME.'/lake_footer');?>