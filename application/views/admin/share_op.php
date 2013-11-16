<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('cid')) ? '修改' : '添加'?></h2>
<div class="slider3">
	<form action="<?=site_url('admin/content/share_op'.(intval($this->input->get('cid')) ? '?cid='.intval($this->input->get('cid')) : ''))?>" method="POST">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 标题：</th>
			<td>
				<input type="text" name="title" id="title" value="<?=set_value('title', isset($content['title']) ? $content['title'] : '')?>" class="input2"/>
				<?php if(form_error('title')) { echo form_error('title'); } ?>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 类型：</th>
			<td>
				<select name="type" id="type">
					<option value="1">视频</option>
					<option value="2" <?=(isset($content['type']) && 2 == $content['type']) ? 'selected' : ''?>>日志</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><b>*</b> 发布时间：</th>
			<td>
				<input type="text" name="ctime" value="<?=set_value('ctime', isset($content['ctime']) ? date('Y-m-d H:i:s', $content['ctime']) : date('Y-m-d H:i:s', time()))?>" class="input1"/>
				<?php if(form_error('ctime')) { echo form_error('ctime'); } ?>
			</td>
		</tr>
	<?php if(!isset($content['type']) || (isset($content['type']) && $content['type'] == 1)):?>
		<tr class="sharevideo">
			<th>视频地址：</th>
			<td>
				<input type="text" name="videourl" value="<?=isset($content['url']) ? $content['url'] : ''?>" class="videourl input2"/><a href="<?=site_url('admin/content/getVideo')?>" class="getvideo">获取</a><br>
				<div class="preview" <?=isset($content['url']) ? '' : 'style="display:none"'?>>
					<img src="<?=isset($content['icon']) ? base_url('./data/uploads/content/'.$content['icon']) : ''?>" class="shareicon"/>
					<div class="sharetitle"><?=isset($content['title']) ? $content['title'] : ''?></div>
					<input type="hidden" value="<?=isset($content['content']) ? $content['content'] : ''?>" name="swfurl" id="swfurl"/>
					<input type="hidden" value="<?=isset($content['icon']) ? $content['icon'] : ''?>" name="icon" id="icon"/>
				</div>
			</td>
		</tr>
	<?php endif;?>
		<tr class="shareconent" <?=(isset($content['type']) && $content['type'] == 2) ? '' :  'style="display:none"'?> >
			<th><b>*</b> 内容：</th>
			<td>
				<textarea name="content" id="content"><?=set_value('content', isset($content['content']) ? $content['content'] : '')?></textarea>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="ac" value="<?=$ac?>"/>
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
		K.create('#content', {width : '670', height: '500', newlineTag:'br', filterMode : true});
	});

	$('.getvideo').click(function() {
		$.post($('.getvideo').attr('href'), {url:$('.videourl').val()}, function(data) {
			if(!data.err) {
				$('.shareicon').attr('src', data.thumbnail_url);
				$('#icon').val(data.thumbnail_url);
				$('.sharetitle').html(data.title);
				$('#title').val(data.title);
				$('#swfurl').val(data.url);
			}
			$('.preview').show();
		}, 'json')
		return false;
	})

	$('#type').change(function() {
		if($(this).val() == 1) {
			$('.sharevideo').show();
			$('.shareconent').hide();
		} else {
			$('.sharevideo').hide();
			$('.shareconent').show();
		}
	})
})
</script>

<?php $this->load->view('admin/footer');?>