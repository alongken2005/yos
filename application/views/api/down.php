<?php $this->load->view('api/header')?>
<div class="module">

	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="4">token获取方式</th>
		</tr>
		<tr>
			<td class="title" width="100">名称</td>
			<td class="title" align="center" width="100">类型</td>
			<td class="title" width="300">值</td>
			<td class="title">说明</td>
		</tr>
		<tr>
			<td>调用URL</td>
			<td align="center">GET</td>
			<td><?=site_url('down/get_token?books=22,36&amp;random=asq432<br>&amp;timestamp=17002120&amp;sign=0da26aaa558a1478f')?></td>
			<td></td>
		</tr>
		<tr>
			<td>books</td>
			<td align="center">String</td>
			<td></td>
			<td>书本或杂志id，以半角逗号分隔</td>
		</tr>
		<tr>
			<td>random</td>
			<td align="center">String</td>
			<td></td>
			<td>随机数</td>
		</tr>
		<tr>
			<td>timestamp</td>
			<td align="center">Int</td>
			<td></td>
			<td>时间戳</td>
		</tr>
		<tr>
			<td>sign</td>
			<td align="center">String</td>
			<td></td>
			<td>验证串</td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="4">获取资源</th>
		</tr>
		<tr>
			<td class="title" width="100">名称</td>
			<td class="title" align="center" width="100">类型</td>
			<td class="title" width="300">值</td>
			<td class="title">说明</td>
		</tr>
		<tr>
			<td>调用URL</td>
			<td align="center">GET</td>
			<td><?=site_url('down/get_res?bookid=22&token=e28156ba31952a7cf7e2<br>&fpath=100ChengYuGuShi01_p1/p1_swf.swf')?></td>
			<td></td>
		</tr>
		<tr>
			<td>bookid</td>
			<td align="center">String</td>
			<td></td>
			<td>书本或杂志id</td>
		</tr>
		<tr>
			<td>token</td>
			<td align="center">String</td>
			<td></td>
			<td>token</td>
		</tr>
		<tr>
			<td>fpath</td>
			<td align="center">String</td>
			<td></td>
			<td>文件路径</td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回值</th>
		</tr>
		<tr>
			<td class="title" align="center" width="150">state</td>
			<td class="title" align="center" width="200">msg</td>
			<td class="title">说明</td>
		</tr>
		<tr>
			<td align="center">0</td>
			<td align="center">token字符串</td>
			<td>成功</td>
		</tr>
		<tr>
			<td align="center"></td>
			<td align="center"></td>
			<td><a href="<?=site_url('api/state_code')?>">错误代码查询</a></td>
		</tr>
	</table>
</div>
<?php $this->load->view('api/footer')?>