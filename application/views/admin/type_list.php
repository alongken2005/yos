<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>分类管理<div class="operate"><a href="<?=site_url('admin/type/op')?>">添加</a></div></h2>
<form action="<?=site_url('admin/type/op')?>" method="post">
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>名称</th>
		<th width="150">所属模块</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td style="text-align: left; padding-left: 10px"><?=$v['name']?></td>
		<td style="text-align: left; padding-left: 10px"><?=$kinds[$v['type']]?></td>
		<td>
			<a href="<?=site_url('admin/type/op?id='.$v['id'])?>">修改</a>
			<a href="<?=site_url('admin/type/del?id='.$v['id'])?>" class="del">删除</a>
		</td>
	</tr>
<?php endforeach; endif;?>
</table>
</form>
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