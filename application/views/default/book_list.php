<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Manage Books</div>
		<a href="<?=site_url('book/edit')?>" class="btn1" style="float:left;margin-right:24px;">Add New Book</a>
		<div class="clear"></div>
		<div class="h">
			<h3>Book Activity (<?=count($books)?> Total)</h3>
			<a href="<?=site_url('book/lists?view=grid')?>" class="viewgrid"></a>
			<a href="<?=site_url('book/lists?view=list')?>" class="viewlist"></a>
		</div>
	<?php if($view == 'grid'):?>
		<ul class="grid">
		<?php foreach($books as $v):?>
			<li>
				<a href="<?=site_url('book/edit?id='.$v['id'])?>"><img src="data/books/<?=$v['cover']?>"/></a>
				<div><a href="<?=site_url('book/edit?id='.$v['id'])?>"><?=$v['title']?></a></div>
			</li>
		<?php endforeach;?>
		</ul>
	<?php elseif($view == 'list'):?>
		<table cellpadding="0" cellspacing="0" class="book_list_table">
			<tr>
				<th>Book Title</th>
				<th width="130">Last Modified</th>
				<th width="80">Status</th>
			</tr>
		<?php foreach($books as $v):?>
			<tr>
				<td><a href="<?=site_url('book/edit?id='.$v['id'])?>"><?=$v['title']?></a></td>
				<td><?=date('Y-m-d H:i', $v['mtime'])?></td>
				<td><?=$v['status'] == 0 ? 'Pending' : 'Active'?></td>
			</tr>
		<?php endforeach;?>
		</table>
	<?php endif;?>
	</div>

</div>
<?php $this->load->view(THEME.'/footer');?>