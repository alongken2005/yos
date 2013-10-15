<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>介绍管理</h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>标题</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td style="text-align: left; padding-left: 10px"><?=$v['title']?></td>
		<td>
			<a href="<?=site_url('admin/lake_intro/op?id='.$v['id'])?>">修改</a>
		</td>
	</tr>
<?php endforeach; endif;?>
</table>
<?=$pagination?>
<?php $this->load->view('admin/footer');?>