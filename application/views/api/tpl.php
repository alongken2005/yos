<div class="module">
<?php foreach($declare as $value) {?>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr id="<?php echo $value['id']?>" class='top_line'>
			<th colspan="4"><?php echo $value['title']?></th>
		</tr>
		<tr class="api_url">
			<td>调用URL</td>
			<td width="60"><?php echo $value['useway']?></td>
			<td colspan="2"><?php echo $value['apiurl']?>  <?php if(isset($value['sam'])){ ?><a href="<?=$value['sam']?>" target="_blank">示例</a><?php } ?></td>
		</tr>
		<tr>
			<td class="title" width="100">参数名称</td>
			<td class="title" width="60" >类型</td>
			<td class="title" width="300">值</td>
			<td class="title">说明</td>
		</tr>
		<?php foreach($value['arguments'] as $arg) {?>
		<tr>
			<td><?php echo $arg['name']?></td>
			<td><?php echo $arg['type']?></td>
			<td><?php echo $arg['value']?></td>
			<td><?php echo $arg['desc']?></td>
		</tr>
		<?php }?>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回值</th>
		</tr>
		<tr>
			<td class="title" width="100">result</td>
			<td class="title" width="200">message</td>
			<td class="title">说明</td>
		</tr>
		<?php if(isset($value['code'])) { foreach($value['code'] as $code) { ?>
		<tr>
			<td><?php echo $code['result']?></td>
			<td><?php echo $code['message']?></td>
			<td><?php echo $code['desc']?></td>
		</tr>
		<?php } } ?>
	</table>
	<?php if(isset($value['subdesc'])) { foreach($value['subdesc'] as $subdesc) { ?>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
	<tr>
		<th colspan="3"><?php echo $subdesc['title'] ?></th>
	</tr>
	<tr>
		<td class="title" width="100">名称</td>
		<td class="title" width="200">类型</td>
		<td class="title">描述</td>
	</tr>
	<?php foreach ($subdesc['arguments'] as $subargu){?>
	<tr>
		<td><?php echo $subargu['name'] ?></td>
		<td><?php echo $subargu['type'] ?></td>
		<td><?php echo $subargu['desc'] ?></td>
	</tr>
	<?php }?>	
	</table>
	<?php } } ?>
	
	<?php
	if(isset($value['message'])) {
	?>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回数据列表说明</th>
		</tr>
		<tr>
			<td class="title" width="285">字段</td>
			<td class="title">说明</td>
		</tr>
		<?php foreach($value['message'] as $msg) {?>
		<tr>
			<td><?php echo $msg['key']?></td>
			<td><?php echo $msg['desc']?></td>
		</tr>
		<?php }?>
	</table>
<?php
	}
}
?>

</div>