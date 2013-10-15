<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/resource/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/resource/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>" class="input2"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 分类：</th>
			<td>
				<select name="type1" class="type1">
				<?php foreach($docType as $v):?>
					<option value="<?=$v['tid']?>" <?=(isset($types['parent']) && $types['parent'] && $types['parent'] == $v['tid']) ?  'selected':''?>><?=$v['name']?></option>
				<?php endforeach;?>
				</select>
				<select name="type" class="type"></select>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 选择文件：</th>
			<td>
				<input type="file" name="userfile"/>
				<?php if(isset($upload_err)):?><span class="err"><?=$upload_err?></span><?php endif;?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 发布时间：</th>
			<td>
				<input type="text" name="ctime" value="<?=set_value('ctime', isset($content['ctime']) ? date('Y-m-d H:i:s', $content['ctime']) : date('Y-m-d H:i:s', time()))?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th> 排序：</th>
			<td>
				<input type="text" name="sort" value="<?=set_value('sort', isset($content['sort']) ? $content['sort'] : 0)?>" class="input1"/>
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
	getType();
	$('.type1').change(function() {
		getType();
	})
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

function getType() {
	$.post("<?=site_url('admin/resource/getType')?>", {parent:$('.type1').val(), value:"<?=isset($types['child']) ? $types['child'] : ''?>"}, function(data) {
		$('.type').html(data);
	})
}
</script>

<?php $this->load->view('admin/footer');?>