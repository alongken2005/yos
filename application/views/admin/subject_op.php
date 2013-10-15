<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/subject/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/subject/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>" class="input2"/>
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
				<img src="<?=get_thumb($row['cover'])?>"/><a href="<?=site_url('admin/subject/file_del?type=img&id='.$row['id'])?>" class="del">删除</a>
			</td>
		</tr>
	<?php endif; ?>
		<tr>
			<th>视频类型：</th>
			<td>
				<input type="radio" class="filetype" name="videoType" value="online" checked/> 在线视频
				<input type="radio" class="filetype" name="videoType" value="local" <?=set_value('videoType', isset($row['videoType']) && $row['videoType'] == 'local' ? 'checked' : '')?>/> 本地视频
				<?php if(form_error('videoType')) { echo form_error('videoType'); } ?>
			</td>
		</tr>
		<tr class="filetab onlineTab">
			<th>flash地址：</th>
			<td><input type="text" name="online" class="input2" value="<?=isset($row['videoType']) && $row['videoType'] == 'online' ? $row['video'] : ''?>"/></td>
		</tr>
		<?php if(isset($row['videoType']) && $row['videoType'] == 'local'):?>
		<tr class="filetab localTab">
			<th>视频路径：</th>
			<td>
				data/uploads/attach/<?=$row['video']?>
			</td>
		</tr>
		<?php endif;?>
		<tr class="filetab localTab">
			<th>本地视频：</th>
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
		<tr>
			<th> 时长：</th>
			<td>
				<input type="text" name="length" value="<?=set_value('length', isset($row['length']) ? $row['length'] : '')?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 教案类型：</th>
			<td>
				<select name="type" class="type">
				<?php foreach($kinds as $k=>$v): if($k != 'top'):?>
					<option value="<?=$k?>" <?=(isset($row['type']) && $row['type'] == $k) ? 'selected' : ''?>><?=$v?></option>
				<?php endif; endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 界数：</th>
			<td>
				<select name="grade" class="grade">
				<?php foreach($gradelist as $v):?>
					<option value="<?=$v['id']?>"><?=$v['title']?></option>
				<?php endforeach;?>
				</select> <span class="red">先选择教案类型</span>
			</td>
		</tr>
		<tr>
			<th> 标签：</th>
			<td>
				<input type="text" name="tag" value="<?=set_value('tag', isset($tags) ? $tags : '')?>" class="input2"/>
			</td>
		</tr>
		<tr>
			<th> 排序：</th>
			<td>
				<input type="text" name="sort" value="<?=set_value('sort', isset($row['sort']) ? $row['sort'] : 0)?>" class="input3"/>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 作者：</th>
			<td>
				<select name="authorid">
				<?php foreach($authorlist as $v):?>
					<option value="<?=$v['id']?>" <?=(isset($row['authorid']) && $row['authorid'] == $v['id']) ? 'selected' : ''?>><?=$v['name']?></option>
				<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th>介绍：</th>
			<td>
				<textarea name="content" id="content" style="width: 508px; height: 150px;"><?=set_value('content', isset($row['content']) ? $row['content'] : '')?></textarea>
				<?php if(form_error('content')) { echo form_error('content'); } ?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="kind" value="<?=$kind?>"/>
				<input type="submit" name="submit" value="提 交" class="but2"/>
			</td>
		</tr>
	</table>
	</form>
</div>

<script type="text/javascript">
$(function() {
	fileChange();
	typeChange();

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

	$('.type').change(function() {
		typeChange();
	})
})

function fileChange() {
	var val = $('.filetype:checked').val();
	$('.filetab').hide();
	$('.'+val+'Tab').show();
}

function typeChange() {
	var val = $('.type option:selected').val();
	$.get("<?=site_url('admin/subject/getSubGrade?grade='.(isset($row['grade']) ? $row['grade'] : 0).'&type=')?>"+val, '', function(data) {
		$('.grade').empty().append(data);
	})
}
</script>

<?php $this->load->view('admin/footer');?>