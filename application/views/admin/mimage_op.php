<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>图片<?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/mimage/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/mimage/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" class="input2" value="<?=isset($row['title']) ? $row['title'] : ''?>"/>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 选择电影：</th>
			<td>
				<select name="mid">
				<?php foreach($movies as $v):?>
					<option value="<?=$v['id']?>" <?=(isset($row['place_id']) && $row['place_id'] == $v['id']) ? 'selected' : ''?>><?=$v['title']?></option>
				<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 排序：</th>
			<td>
				<input type="text" name="sort" class="input4" value="<?=isset($row['sort']) ? $row['sort'] : 0?>"/> <b>数字越大越后面</b>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 文件：</th>
			<td>
				<input type="file" name="userfile"/> <span class="red">尺寸：240*135(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err)):?><span class="err"><?=$upload_err?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['filename'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=get_thumb($row['filename'])?>"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th></th>
			<td><input type="submit" name="submit" value="提 交" class="but2"/></td>
		</tr>
	</table>
	</form>
</div>

<?php $this->load->view('admin/footer');?>