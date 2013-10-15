<?php $this->load->view(THEME.'/lake_header');?>
<div class="lake_lead">
	<a href="<?=base_url()?>">儿童之路首页 </a> > <a href="<?=site_url('lake')?>">千岛湖研习营</a> > <a href="<?=site_url('lake/notice_list')?>">文件通知</a>
</div>
<div class="nli">
	<?=$row['content']?>
</div>
<?php $this->load->view(THEME.'/lake_footer');?>