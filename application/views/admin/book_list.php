<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<form action="<?=site_url('admin/book/status')?>" method="post" class="statusForm">
<h2>
	<div class="status">
		<select name="status">
			<option value="0">Pending</option>
			<option value="1">Active</option>
			<option value="2">Blocked</option>
		</select>
		<input type="submit" value="提交" class="statusSbumit"/>
	</div>
	<div class="operate"><a href="<?=site_url('admin/book/op')?>">添加</a></div>
</h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>选择</th>
		<th>书名</th>
		<th width="150">作者</th>
		<th width="150">状态</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td><input type="checkbox" name="bookId[]" value="<?=$v['id']?>" /></td>
		<td><?=$v['title']?></td>
		<td><?=$v['author']?></td>
		<td>
			<?php 
				if($v['status'] == 0) {
					echo 'Pending';
				} else if($v['status'] == 1) {
					echo 'Active';
				} else {
					echo 'Blocked';
				}
			?>			
		</td>
		<td>
			<a href="<?=site_url('admin/book/audioList?id='.$v['id'])?>" class="audioIframe">语音</a>
			<a href="<?=site_url('admin/book/del?id='.$v['id'])?>" class="del">删除</a>
		</td>
	</tr>
<?php endforeach; endif;?>
</table>
</form>
<?=$pagination?>
<script type="text/javascript" src="<?=base_url('common/fancybox/jquery.fancybox-1.3.4.pack.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url('common/fancybox/jquery.fancybox-1.3.4.css')?>"/>
<script type="text/javascript">
$(function() {
	$('.del').click(function() {
		if(confirm('确认删除？')) {
			var po = $(this).parent().parent();
			$.get($(this).attr('href'), '', function(data) {
				if(data == 'ok') {
					po.hide();
				} else {
					alert('删除失败！');
				}
			});
		}
		return false;
	});

	$('.statusSbumit').click(function() {
		$.post($('.statusForm').attr('action'), $('.statusForm').serialize(), function(data) {
			if(data == 'ok') {
				alert('修改成功');
				window.location.reload();
			} else {
				alert('操作失败');
			}
			return false;
		});
		return false;
	});

	$(".audioIframe").fancybox({
		'width'				: 600,
		'height'			: 250,
		'padding'			: 2,
		'type'				: 'iframe',
		'centerOnScroll'	: true,
		'overlayOpacity'	: 0
	});	
})
</script>
<?php $this->load->view('admin/footer');?>