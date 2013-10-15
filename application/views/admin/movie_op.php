<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('id')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/movie/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/movie/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" id="title" value="<?=set_value('title', isset($row['title']) ? $row['title'] : '')?>" class="input2"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 竖封面：</th>
			<td>
				<input type="file" name="cover1"/> <span class="red">尺寸：225*300(按照这个比例就可以，尺寸可以稍微大一些)</span>
				<?php if(isset($upload_err1)):?><span class="err"><?=$upload_err1?></span><?php endif;?>
			</td>
		</tr>
	<?php if(isset($row['cover1'])):?>
		<tr>
			<th></th>
			<td>
				<img src="<?=get_thumb($row['cover1'])?>" style="width:120px"/>
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
				<img src="<?=get_thumb($row['cover2'])?>" style="width:120px"/>
			</td>
		</tr>
	<?php endif;?>
		<tr>
		<tr>
			<th>电影选择：</th>
			<td>
				<div id="navtab" class="tetab" style="width: 738px;overflow:hidden; border:1px solid #A3C0E8;">
					<div tabid="online" title="在线" style="padding: 10px;">
						flash地址：<input type="text" name="online" class="input2" value="<?=isset($row['is_local']) && $row['is_local'] == 0 ? $row['movie'] : ''?>"/>
					</div>
					<div tabid="local" title="本地"  style="padding: 10px;">
						<?php if(isset($row['is_local']) && $row['is_local'] == 1):?>
						<div>视频路径：data/uploads/stuff/<?=$row['movie']?></div>
						<?php endif;?>
						<div class="clear"></div>
						<div class="videoNameList" style="width:80px">
							<input type="radio" name="local" value="" checked/> 不选
						</div>
						<?php $dir_arr = get_filenames('./data/tmp/'); if($dir_arr): foreach($dir_arr as $v):?>
						<div class="videoNameList">
							<input type="radio" name="local" value="<?=$v?>" /> <?=$v?>
						</div>
						<?php endforeach; endif;?>
					</div>
				</div>
				<input type="hidden" name="is_local" class="is_local"/>
			</td>
		</tr>
		<tr>
			<th>电影所属：</th>
			<td>
			<?php foreach($areas as $k=>$v):?>
				<input type="checkbox" name="area[]" value="<?=$k?>" <?=isset($row['area']) && stripos($row['area'], (string)$k) !== false ? 'checked' : ''?>/> <?=$v?>&nbsp;
			<?php endforeach;?>
			</td>
		</tr>
		<tr>
			<th>电影场次：<br><a href="javascript:void(0)" class="abut2 add_play" style="margin:5px">增加场次</a></th>
			<td>
				<div class="mvlist">
				<?php if(isset($tickets) && $tickets): foreach($tickets as $v):?>
					<div class="mvalue">
						<a href="<?=site_url('admin/movie/ticket_del?id='.$v['id'])?>" class="del_oplay" alt="删除场次" title="删除场次"></a>
						播放时间：<input type="text" name="stime_o[<?=$v['id']?>]" class="Wdate input5" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value="<?=date('Y-m-d H:i:s', $v['stime'])?>"/>
						&nbsp;&nbsp;总票数：<input type="text" name="total_o[<?=$v['id']?>]" value="<?=$v['total']?>" class="input3"/>
						&nbsp;&nbsp;已索票数：<?=$v['used']?>
					</div>
				<?php endforeach; endif;?>
				</div>
			</td>
		</tr>
		<tr>
			<th>放映时间：</th>
			<td>
				<input type="text" name="stime" value="<?=set_value('stime', isset($row['stime']) ? date('Y-m-d H:i', $row['stime']) : date('Y-m-d H:i', time()))?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th>出品方：</th>
			<td>
				<input type="text" name="producer" value="<?=set_value('producer', isset($row['producer']) ? $row['producer'] : '')?>" class="input1"/>
			</td>
		</tr>
		<tr>
			<th>评分：</th>
			<td>
				<input type="text" name="score" value="<?=set_value('score', isset($row['score']) ? $row['score'] : 0)?>" class="input3"/>
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
				<textarea name="intro" id="intro"><?=set_value('intro', isset($row['intro']) ? $row['intro'] : '')?></textarea>
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
	KindEditor.ready(function(K) {
		K.create('#intro', {width: '738', height: '400', newlineTag:'br', filterMode : true});
	});

	$('.add_play').click(function() {
		$('.ex').clone(true).removeClass('ex').prependTo('.mvlist');
	})

	$('.del_play').click(function() {
		$(this).parent().remove();
	})

	$('.del_oplay').click(function() {
		if(confirm('确认删除？')) {
			var obj = $(this);
			$.get(obj.attr('href'), '', function(data) {
				obj.parent().hide();
			})
		}
		return false;
	})

	$("#navtab").ligerTab({
		onAfterSelectTabItem: function(tabid) {
			$('.is_local').val(tabid);
		}
	});
	$("#infotab").ligerTab();

	$("#navtab").ligerGetTabManager().selectTabItem("<?=isset($row['is_local']) && $row['is_local'] == 1 ? 'local' : 'online'?>");
})
</script>

<?php $this->load->view('admin/footer');?>