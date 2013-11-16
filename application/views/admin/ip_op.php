<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/ip/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/ip/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th>ip：</th>
			<td><input type="text" name="ip" class="input1" value="<?=isset($content['ip']) ? $content['ip'] : ''?>"/></td>
		</tr>
		<tr>
			<th>过期时间：</th>
			<td><input type="text" name="date_expire" class="Wdate input1" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value="<?=isset($content['date_expire']) ? date('Y-m-d H:i', $content['date_expire']) : date('Y-m-d H:i')?>"/></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" name="submit" value="提 交" class="but2"/>
			</td>
		</tr>
	</table>
	</form>
</div>
<script type="text/javascript" src="<?=base_url('./common/datepicker/WdatePicker.js')?>"></script>
<?php $this->load->view('admin/footer');?>