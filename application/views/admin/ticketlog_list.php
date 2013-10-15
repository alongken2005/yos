<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>索票记录</h2>
<form action="<?=site_url('admin/dataview/sell_tj')?>" method="get">
<table cellspacing="0" cellpadding="0" border="0" class="condition">
	<tr>
		<th width="60">电影：</th>
		<td width="160">
			<select name="channel">
				<option value=''>请选择</option>
			</select>
		</td>
		<th>上映时间：</th>
		<td width="55">
			<select name="type">
				<option value='1' <?php if($this->input->get('type') && $this->input->get('type') == 1) { echo 'selected';}?>>按日</option>
				<option value="2" <?php if($this->input->get('type') && $this->input->get('type') == 2) { echo 'selected';}?>>按月</option>
			</select>
		</td>
		<th>索票时间：</th>
		<td width="190">
			<input type="text" name="time_s" class="Wdate input5" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd 00:00:00'})" value="<?=$this->input->get('time_s')?>"/>
		</td>
		<td>
			<input type="submit" value="查 询" class="search"/>
		</td>
	</tr>
</table>
</form>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th width="120">索票确认号</th>
		<th>电影</th>
		<th width="180">索票人</th>
		<th width="60">票数</th>
		<th width="150">上映时间</th>
		<th width="150">索票时间</th>
		<th width="70">状态</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td><?=$v['uniqid']?></td>
		<td><?=$v['title']?></td>
		<td><?=$v['email']?></td>
		<td><?=$v['num']?></td>
		<td><?=date('Y-m-d H:i', $v['stime'])?></td>
		<td><?=date('Y-m-d H:i', $v['ctime'])?></td>
		<td><?=$v['state'] == 1 ? '已发' : '未发'?></td>
	</tr>
<?php endforeach; endif;?>
</table>
<?=$pagination?>
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
			})
		}
		return false;
	})
})
</script>
<?php $this->load->view('admin/footer');?>