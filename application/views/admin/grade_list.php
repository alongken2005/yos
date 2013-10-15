<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>管理<div class="operate"><a href="<?=site_url('admin/grade/op')?>">添加</a></div></h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th style="text-align: left">【分类】名称</th>
		<th width="280">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td style="text-align: left">【<?=$kinds[$v['type']]?>】<?=$v['name']?></td>
		<td>
			<a href="<?=site_url('admin/grade/pic_op?place_id='.$v['id'])?>">活动照片添加</a>&nbsp;&nbsp;
			<a href="<?=site_url('admin/grade/pic_lists?place_id='.$v['id'])?>">活动照片管理</a>&nbsp;&nbsp;
			<a href="<?=site_url('admin/grade/op?id='.$v['id'])?>">修改</a>&nbsp;&nbsp;
			<a href="<?=site_url('admin/grade/del?id='.$v['id'])?>" class="del">删除</a>
		</td>
	</tr>
<?php endforeach; endif;?>
</table>
<?=$pagination?>
<script type="text/javascript">
$(function() {
	$('.del').click(function() {
		if(confirm('确认删除？')){
			var po = $(this).parent().parent();
			$.get($(this).attr('href'), '', function(data) {
				if(data == 'ok'){
					po.hide();
				} else {
					alert('删除失败！');
				}
			})
		}
		return false;
	})
})
</script>
<?php $this->load->view('admin/footer');?>