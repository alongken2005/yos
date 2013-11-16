<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?></h2>
<div class="slider3">
	<form action="<?=site_url('admin/msgs/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th>咨询内容：</th>
			<td><?=$row['msg']?></td>
		</tr>
		<tr>
			<th>邮箱地址：</th>
			<td><?=$row['email']?></td>
		</tr>
		<tr>
			<th>公司名称：</th>
			<td><?=$row['company']?></td>
		</tr>
		<tr>
			<th>电话：</th>
			<td><?=$row['tel']?></td>
		</tr>
		<tr>
			<th>咨询时间：</th>
			<td><?=date('Y-m-d H:i', $row['ctime'])?></td>
		</tr>

		<tr>
			<th><b>*</b> 回复邮件标题：</th>
			<td>
				<input type="text" name="retitle" value="<?=set_value('retitle', isset($row['retitle']) ? $row['retitle'] : '')?>" class="input2"/>
				<?php if(form_error('retitle')) { echo form_error('retitle'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 回复邮件内容：</th>
			<td>
				<textarea name="reply" id="content"><?=set_value('reply', isset($row['reply']) ? $row['reply'] : '')?></textarea>
				<?php if(form_error('reply')) { echo form_error('reply'); } ?>
			</td>
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


<script type="text/javascript" src="<?=base_url('./common/kindeditor/kindeditor.js')?>"></script>
<script type="text/javascript">
$(function() {
	KindEditor.ready(function(K) {
		K.create('#content', {width : '670', height: '500', newlineTag:'br', filterMode : true, urlType:'domain'});
	});
})
</script>

<?php $this->load->view('admin/footer');?>