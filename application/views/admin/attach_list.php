<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>附件管理</h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>附件名</th>
		<th width="150">所属模块</th>
		<th width="80">关联ID</th>
		<th width="100">文件大小(KB)</th>
		<th width="80">下载次数</th>
		<th width="100">创建时间</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td style="text-align: left; padding-left: 10px"><?=$v['realname']?></td>
		<td><?=$kinds[$v['kind']]?></td>
		<td><?=$v['relaid']?></td>
		<td><?=$v['filesize']?></td>
		<td><?=$v['downs']?></td>
		<td><?=date('Y-m-d', $v['ctime'])?></td>
		<td>
			<a href="<?=site_url('admin/attach/del?id='.$v['id'])?>" class="del">删除</a>
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