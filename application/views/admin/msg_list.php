<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=isset($tid) ? $types[$tid] : ''?>管理</h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>内容</th>
		<th width="60">状态</th>
		<th width="150">IP</th>
		<th width="150">提交日期</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td style="text-align: left; padding-left: 10px">
			<?=$v['msg']?><br>
			邮箱：<?=$v['email']?> &nbsp;&nbsp;公司名称：<?=$v['company']?>&nbsp;&nbsp;电话：<?=$v['tel']?>
		</td>
		<td><?=$v['status'] == 1?'已回复':'未处理'?></td>
		<td><?=$v['ip']?></td>
		<td><?=date('Y-m-d H:i', $v['ctime'])?></td>
		<td>
			<a href="<?=site_url('admin/msgs/op?id='.$v['id'])?>">回复</a>
			<a href="<?=site_url('admin/msgs/del?id='.$v['id'])?>" class="del">删除</a>
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