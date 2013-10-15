<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>课件管理
	<div class="operate"><a href="<?=site_url('admin/subject/lists')?>">教案管理</a></div>
	<div class="operate" style="margin-right: 10px;"><a href="<?=site_url('admin/subject/attach_op?relaid='.$relaid)?>">添加课件</a></div>
</h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>课件标题</th>
		<th width="150">发布日期</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td><?=$v['title']?></td>
		<td><?=date('Y-m-d H:i', $v['ctime'])?></td>
		<td>
			<a href="<?=site_url('admin/subject/attach_op?id='.$v['id'].'&relaid='.$relaid)?>">修改</a>
			<a href="<?=site_url('admin/subject/attach_del?id='.$v['id'].'&relaid='.$relaid)?>" class="del">删除</a>
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