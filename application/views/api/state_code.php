<?php $this->load->view('api/header')?>
<div class="module">
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回值</th>
		</tr>
		<tr>
			<td class="title" align="center" width="150">state</td>
			<td class="title" align="center" width="200">msg</td>
			<td class="title">说明</td>
		</tr>
		<?php foreach($state_code as $v):?>
		<tr>
			<td align="center"><?=$v['code']?></td>
			<td align="center"><?=$v['msg']?></td>
			<td><?=$v['info']?></td>
		</tr>
		<?php endforeach;?>
	</table>
</div>
<?php $this->load->view('api/footer')?>