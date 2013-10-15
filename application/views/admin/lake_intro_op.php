<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><?=intval($this->input->get('cid')) ? '修改' : '添加'?><div class="operate"><a href="<?=site_url('admin/lake_intro/lists')?>">管理</a></div></h2>
<div class="slider3">
	<form action="<?=site_url('admin/lake_intro/op'.(intval($this->input->get('id')) ? '?id='.intval($this->input->get('id')) : ''))?>" method="POST">
	<table cellspacing="0" cellpadding="0" border="0" class="table1">
		<tr>
			<th><b>*</b> 内容：</th>
			<td>
				<textarea name="content" id="content"><?=isset($content['content']) ? $content['content'] : ''?></textarea>
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
		K.create('#content', {
			width : '670',
			height: '500',
			newlineTag:'br',
			filterMode : false,
			uploadJson : '<?=site_url('tool/upload')?>',
		});
	});
})
</script>

<?php $this->load->view('admin/footer');?>