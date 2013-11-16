<?php $this->load->view('api/header')?>
<div class="module">
	<form action="<?=site_url('pay/figureUp')?>" method="post">
		<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
			<tr class="top_line">
				<th colspan="2">计算总价，产生订单</th>
			</tr>
			<tr>
				<td class="name">产品ID</td>
				<td><input type="text" name="pids" class="input3"> id之间用,隔开，例如：1,45,33</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="提 交" class="submit"></td>
			</tr>
		</table>
	</form>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="4">调用方式</th>
		</tr>
		<tr>
			<td class="title" width="100">名称</td>
			<td class="title" align="center" width="100">类型</td>
			<td class="title" width="300">值</td>
			<td class="title">说明</td>
		</tr>
		<tr>
			<td>调用URL</td>
			<td align="center"><?=$info1['done']['method']?></td>
			<td><?=$info1['done']['url']?></td>
			<td></td>
		</tr>
		<?php foreach($info1['done']['params'] as $v):?>
		<tr>
			<td><?=$v['name']?></td>
			<td align="center"><?=$v['type']?></td>
			<td><?=$v['value']?></td>
			<td><?=$v['desc']?></td>
		</tr>
		<?php endforeach;?>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回值</th>
		</tr>
		<tr>
			<td class="title" align="center" width="80">state</td>
			<td class="title">msg</td>
		</tr>
		<tr>
			<td align="center">0</td>
			<td>invoid_id:订单号， amount：总价</td>
		</tr>
	</table>

	<form action="<?=site_url('pay/billover')?>" method="post">
		<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
			<tr class="top_line">
				<th colspan="2">支付完成</th>
			</tr>
			<tr>
				<td class="name">option</td>
				<td><input type="text" name="option" value="54" class="input3"/> 支付方式id</td>
			</tr>
			<tr>
				<td class="name">invoid_id</td>
				<td><input type="text" name="invoid_id" class="input3"/> 订单号</td>
			</tr>
			<tr>
				<td class="name">receipt</td>
				<td><input type="text" name="receipt" class="input3"/></td>
			</tr>
			<tr>
				<td class="name">mode</td>
				<td>
					<select name="mode">
						<option value="0">测试</option>
						<option value="1">正式</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="提 交" class="submit"></td>
			</tr>
		</table>
	</form>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="4">调用方式</th>
		</tr>
		<tr>
			<td class="title" width="100">名称</td>
			<td class="title" align="center" width="100">类型</td>
			<td class="title" width="300">值</td>
			<td class="title">说明</td>
		</tr>
		<tr>
			<td>调用URL</td>
			<td align="center"><?=$info1['done']['method']?></td>
			<td><?=$info2['done']['url']?></td>
			<td></td>
		</tr>
		<?php foreach($info2['done']['params'] as $v):?>
		<tr>
			<td><?=$v['name']?></td>
			<td align="center"><?=$v['type']?></td>
			<td><?=$v['value']?></td>
			<td><?=$v['desc']?></td>
		</tr>
		<?php endforeach;?>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回值</th>
		</tr>
		<tr>
			<td class="title" align="center" width="80">state</td>
			<td class="title" width="200">msg</td>
			<td class="title">说明</td>
		</tr>
	<?php foreach($info2['return'] as $v):?>
		<tr>
			<td align="center"><?=$v['state']?></td>
			<td><?=$v['msg']?></td>
			<td><?=$v['desc']?></td>
		</tr>
	<?php endforeach;?>
	</table>
</div>
<?php $this->load->view('api/footer')?>