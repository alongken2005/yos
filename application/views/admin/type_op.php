<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?></h2>
<div class="slider3">
	<form action="<?=site_url('admin/type/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 名称：</th>
			<td>
				<input type="text" name="name" value="<?=set_value('name', isset($content['name']) ? $content['name'] : '')?>" class="input1"/>
				<?php if(form_error('name')) { echo form_error('name'); } ?>
			</td>
		</tr>
		<tr>
			<th>所属模块：</th>
			<td>
				<select name="type">
					<option value="video">视频</option>
					<option value="stuff">教案</option>
				</select>
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

<script type="text/javascript">
$(function() {

	$('.del').click(function() {
		if(confirm('确定删除？')) {
			$.get($('.del').attr('href'), '', function(data) {
				if(data == 'ok') {
					$('.tr_icon').hide();
				} else {
					alert('删除失败');
				}
			})
		}
		return false;
	})
})
</script>

<?php $this->load->view('admin/footer');?>