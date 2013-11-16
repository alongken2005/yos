<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>课件<?=intval($this->input->get('id')) ? '修改' : '添加'?>
	<div class="operate"><a href="<?=site_url('admin/subject/lists')?>">教案管理</a></div>
	<div class="operate" style="margin-right: 10px;"><a href="<?=site_url('admin/subject/attach_lists?relaid='.$relaid)?>">课件管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/subject/attach_op?relaid='.$relaid.($this->input->get('id') ? '&id='.$this->input->get('id') : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th>标题：</th>
			<td>
				<input type="text" name="title" class="input2" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th>封面：</th>
			<td>
				<input type="file" name="cover"/>
			</td>
		</tr>
	<?php if(isset($row['cover']) && $row['cover']):?>
		<tr class="tr_icon">
			<th></th>
			<td>
				<img src="<?=get_thumb($row['cover'])?>"/><a href="<?=site_url('admin/lake_attach/file_del?type=img&id='.$row['id'])?>" class="del">删除</a>
			</td>
		</tr>
	<?php endif; ?>
		<tr>
			<th>课件类型：</th>
			<td>
				<input type="radio" class="filetype" name="filetype" value="doc" checked/> 文档
				<input type="radio" class="filetype" name="filetype" value="online" <?=set_value('filetype', isset($row['filetype']) && $row['filetype'] == 'online' ? 'checked' : '')?>/> 在线视频
				<input type="radio" class="filetype" name="filetype" value="local" <?=set_value('filetype', isset($row['filetype']) && $row['filetype'] == 'local' ? 'checked' : '')?>/> 本地视频
				<?php if(form_error('filetype')) { echo form_error('filetype'); } ?>
			</td>
		</tr>
		<tr class="filetab docTab">
			<th>选择文档：</th>
			<td><input type="file" name="doc" /></td>
		</tr>
		<tr class="filetab onlineTab">
			<th>flash地址：</th>
			<td><input type="text" name="online" class="input2" value="<?=isset($row['filetype']) && $row['filetype'] == 'online' ? $row['filename'] : ''?>"/></td>
		</tr>
		<?php if(isset($row['filetype']) && $row['filetype'] == 'local'):?>
		<tr class="filetab localTab">
			<th>视频路径：</th>
			<td>
				data/uploads/attach/<?=$row['filename']?>
			</td>
		</tr>
		<?php endif;?>
		<tr class="filetab localTab">
			<th>选择本地视频：</th>
			<td>
				<div class="videoNameList" style="width:80px">
					<input type="radio" name="local" value="" checked/> 不选
				</div>
				<?php $dir_arr = get_filenames('./data/tmp/'); if($dir_arr): foreach($dir_arr as $v):?>
				<div class="videoNameList">
					<input type="radio" name="local" value="<?=$v?>" /> <?=$v?>
				</div>
				<?php endforeach; endif;?>
			</td>
		</tr>
		<tr class="timeLength">
			<th> 视频时长：</th>
			<td>
				<input type="text" name="other" value="<?=set_value('other', isset($row['other']) ? $row['other'] : '')?>" class="input1"/>
				<span class="red">格式：1:34:59</span>
			</td>
		</tr>
		<tr>
			<th> 排序：</th>
			<td>
				<input type="text" name="sort" value="<?=set_value('sort', isset($row['sort']) ? $row['sort'] : 0)?>" class="input3"/>
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
	fileChange();

	KindEditor.ready(function(K) {
		K.create('#content', {width : '670', height: '500', newlineTag:'br', filterMode : true});
	});

	$('.del').click(function() {
		obj = $(this);
		if(confirm('确定删除？')) {
			$.get($('.del').attr('href'), '', function(data) {
				if(data == 'ok') {
					obj.parent().parent('.tr_icon').hide();
				} else {
					alert('删除失败');
				}
			})
		}
		return false;
	})

	$('.filetype').change(function() {
		fileChange();
	})
})

function fileChange() {
	var val = $('.filetype:checked').val();
	$('.filetab').hide();
	$('.'+val+'Tab').show();
	if(val == 'doc') {
		$('.timeLength').hide();
	} else {
		$('.timeLength').show();
	}
}
</script>

<?php $this->load->view('admin/footer');?>