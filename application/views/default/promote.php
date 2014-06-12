<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Promote Books</div>
		<div class="clear"></div>

		<table cellpadding="0" cellspacing="0" class="sales_list_table">
			<tr>
				<th>Title</th>
				<th width="370">URL</th>
				<th width="80">Shorten URL</th>
			</tr>
		<?php 
			if(isset($lists) && $lists) { 
				foreach($lists as $v) { 
		?>
			<tr>
				<td><?=$v['title']?></td>
				<td><?=site_url('book/detail?do=detail&bid='.$v['id'])?></td>
				<td>http://goo.gl</td>
			</tr>
		<?php } } ?>
		</table>
		<?php echo isset($pagination) ? $pagination : '';?>
	</div>
</div>
<?php $this->load->view(THEME.'/footer');?>