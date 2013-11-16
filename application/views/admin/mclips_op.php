<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/mclips/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/mclips/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>" class="input2"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 选择电影：</th>
			<td>
				<select name="mid">
				<?php foreach($movies as $v):?>
					<option value="<?=$v['id']?>" <?=(isset($row['mid']) && $v['id'] == $row['mid'] ? 'selected' : '')?>><?=$v['title']?></option>
				<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 竖封面：</th>
			<td>
				<input type="file" name="cover1"/>	<span class="red">尺寸：225*300(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err1)):?><span class="err"><?=$upload_err1?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['cover1'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=get_thumb($row['cover1'])?>"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th><b>*</b> 横封面：</th>
			<td>
				<input type="file" name="cover2"/> <span class="red">尺寸：240*135(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err2)):?><span class="err"><?=$upload_err2?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['cover2'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=get_thumb($row['cover2'])?>"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th valign="top">片花选择：</th>
			<td>
				<div id="navtab" class="tetab" style="width: 720px;overflow:hidden; border:1px solid #A3C0E8; margin-bottom: 10px">
					<div tabid="online" title="在线" style="padding: 10px;">
						flash地址：<input type="text" name="online" class="input2" value="<?=isset($row['is_local']) && $row['is_local'] == 0 ? $row['video'] : ''?>"/>
					</div>
					<div tabid="local" title="本地"  style="padding: 10px;">
						<?php if(isset($row['is_local']) && $row['is_local'] == 1):?>
						<div>视频路径：data/uploads/stuff/<?=$row['video']?></div>
						<?php endif;?>
						<div class="clear"></div>
						<div class="videoNameList" style="width:80px">
							<input type="radio" name="local" value="" checked/> 不选
						</div>
						<?php $dir_arr = get_filenames('./data/tmp/'); foreach($dir_arr as $v):?>
						<div class="videoNameList">
							<input type="radio" name="local" value="<?=$v?>" /> <?=$v?>
						</div>
						<?php endforeach;?>
					</div>
				</div>
				<input type="hidden" name="is_local" class="is_local"/>
			</td>
		</tr>
		<tr>
			<th>排序：</th>
			<td>
				<input type="text" name="sort" value="<?=set_value('sort', isset($row['sort']) ? $row['sort'] : 0)?>" class="input3"/> <span class="red">数字越大，排在越前面</span>
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
	$("#navtab").ligerTab({
		onAfterSelectTabItem: function(tabid) {
			$('.is_local').val(tabid);
		}
	});

	$("#navtab").ligerGetTabManager().selectTabItem("<?=isset($row['is_local']) && $row['is_local'] == 1 ? 'local' : 'online'?>");
})
</script>
<?php $this->load->view('admin/footer');?>