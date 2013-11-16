<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/grade/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/grade/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 名称：</th>
			<td>
				<input type="text" name="name" class="input1" value="<?=isset($row['name']) ? $row['name'] : ''?>"/>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 分类：</th>
			<td>
				<select name="type">
				<?php foreach($kinds as $k=>$v): if($k != 'top'):?>
					<option value="<?=$k?>" <?=(isset($row['type']) && $row['type'] == $k) ? 'selected' : ''?>><?=$v?></option>
				<?php endif; endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th>封面：</th>
			<td><input type="file" name="cover"/> <span class="red">尺寸：225*300(按照这个比例就可以，尺寸可以稍微大一些)</span></td>
		</tr>
	<?php if(isset($row['cover'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=get_thumb($row['cover'])?>"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<th valign="top">视频：</th>
			<td>
				<div id="navtab" class="tetab" style="width: 720px;overflow:hidden; border:1px solid #A3C0E8; margin-bottom: 10px">
					<div tabid="online" title="在线" style="padding: 10px;">
						flash地址：<input type="text" name="online" class="input2" value="<?=isset($row['is_local']) && $row['is_local'] == 0 ? $row['video'] : ''?>"/>
					</div>
					<div tabid="local" title="本地"  style="padding: 10px;">
						<?php if(isset($row['is_local']) && $row['is_local'] == 1):?>
						<div>视频路径：data/uploads/attach/<?=$row['video']?></div>
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
			<th>介绍：</th>
			<td>
				<textarea name="content" style="width: 720px; height: 150px; margin-bottom: 15px;"><?=isset($row['content']) ? $row['content'] : ''?></textarea>
			</td>
		</tr>
		<tr>
			<th>会务手册：</th>
			<td>
				<textarea name="chm" id="chm"><?=isset($row['chm']) ? $row['chm'] : ''?></textarea>
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
		K.create('#chm', {
			width : '720',
			height: '400',
			newlineTag:'br',
			filterMode : true,
			uploadJson : '<?=site_url('tool/upload')?>',
		});
	});

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
	$("#navtab").ligerTab({
		onAfterSelectTabItem: function(tabid) {
			$('.is_local').val(tabid);
		}
	});
	$("#navtab").ligerGetTabManager().selectTabItem("<?=isset($row['video']) && $row['is_local'] == 1 ? 'local' : 'online'?>");
})
</script>
<?php $this->load->view('admin/footer');?>