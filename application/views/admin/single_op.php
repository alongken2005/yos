<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/single/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/single/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" id="title" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>" class="input2"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 封面：</th>
			<td>
				<input type="file" name="cover"/> <span class="red">尺寸：240*300(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err1)):?><span class="err"><?=$upload_err1?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['cover'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=base_url(get_thumb($row['cover']))?>" style="height:300px"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th><b>*</b> 图1：</th>
			<td>
				<input type="file" name="pic1"/> <span class="red">尺寸：240*135(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err2)):?><span class="err"><?=$upload_err2?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['pic1'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=base_url(get_thumb($row['pic1']))?>" style="height:300px"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th><b>*</b> 图2：</th>
			<td>
				<input type="file" name="pic2"/> <span class="red">尺寸：240*135(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err2)):?><span class="err"><?=$upload_err2?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['pic2'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=base_url(get_thumb($row['pic2']))?>" style="height:300px"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th>作者：</th>
			<td>
				<input type="text" name="author1" value="<?=set_value('author1', isset($row['author1']) ? $row['author1'] : '')?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th>插图作者：</th>
			<td>
				<input type="text" name="author2" value="<?=set_value('author2', isset($row['author2']) ? $row['author2'] : '')?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th>译者：</th>
			<td>
				<input type="text" name="author3" value="<?=set_value('author3', isset($row['author3']) ? $row['author3'] : '')?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th>排序：</th>
			<td>
				<input type="text" name="sort" value="<?=set_value('sort', isset($row['sort']) ? $row['sort'] : 0)?>" class="input3"/> <span class="red">数字越大，排在越前面</span>
			</td>
		</tr>
		<tr>
			<th>简介：</th>
			<td>
				<textarea name="intro" id="intro" style="width:508px; height:250px"><?=set_value('intro', isset($row['intro']) ? $row['intro'] : '')?></textarea>
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

	<div class="mvalue ex">
		<a href="javascript:void(0)" class="del_play" alt="删除场次" title="删除场次"></a>
		播放时间：<input type="text" name="stime_n[]" class="Wdate input5" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value=""/>
		&nbsp;&nbsp;总票数：<input type="text" name="total_n[]" value="0" class="input3"/>
	</div>
</div>

<script type="text/javascript" src="<?=base_url('./common/kindeditor/kindeditor.js')?>"></script>
<script type="text/javascript" src="<?=base_url('./common/datepicker/WdatePicker.js')?>"></script>
<script type="text/javascript">
$(function() {
//	KindEditor.ready(function(K) {
//		K.create('#intro', {width: '738', height: '400', newlineTag:'br', filterMode : true});
//	});
})
</script>

<?php $this->load->view('admin/footer');?>