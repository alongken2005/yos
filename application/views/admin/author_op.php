<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/author/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/author/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 名字：</th>
			<td>
				<input type="text" name="name" value="<?=set_value('name', isset($content['name']) ? $content['name'] : '')?>" class="input2"/>
				<?php if(form_error('name')) { echo form_error('name'); } ?>
			</td>
		</tr>
		<tr>
			<th>头像：</th>
			<td>
				<input type="file" name="cover"/> <span class="red">尺寸：195*235</span>
				<?php if(isset($upload_err)):?><span class="err"><?=$upload_err?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($content['cover']) && $content['cover']):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=get_thumb($content['cover'])?>"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th>头衔：</th>
			<td>
				<input type="text" name="title" value="<?=set_value('title', isset($content['title']) ? $content['title'] : '')?>" class="input2"/>
			</td>
		</tr>
		<tr>
			<th>介绍：</th>
			<td>
				<textarea name="content" style="width:495px;height:100px;overflow:hidden;padding:5px"><?=set_value('content', isset($content['content']) ? $content['content'] : '')?></textarea>
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