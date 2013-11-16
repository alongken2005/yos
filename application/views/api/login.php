<?php $this->load->view('api/header')?>
<div class="module">
	<form method="post" action="<?=site_url('user/login')?>">
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="2">登录</th>
		</tr>
		<tr>
			<td class="name">账号</td>
			<td><input type="text" name="username" value="" class="input3"></td>
		</tr>
		<tr>
			<td class="name">密码</td>
			<td><input type="password" name="password" class="input3"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="login" value="登 录" class="submit"></td>
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
			<td align="center">POST</td>
			<td><?=base_url('user/login')?></td>
			<td></td>
		</tr>
		<tr>
			<td>usename</td>
			<td align="center">String</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>password</td>
			<td align="center">String</td>
			<td></td>
			<td></td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="3">返回值</th>
		</tr>
		<tr>
			<td class="title" align="center" width="80">值</td>
			<td class="title" align="center" width="100">类型</td>
			<td class="title">说明</td>
		</tr>
		<tr>
			<td align="center">1</td>
			<td align="center">Int</td>
			<td>用户名或密码为空</td>
		</tr>
		<tr>
			<td align="center">2</td>
			<td align="center">Int</td>
			<td>密码错误</td>
		</tr>
		<tr>
			<td align="center">3</td>
			<td align="center">Int</td>
			<td>账号被锁</td>
		</tr>
		<tr>
			<td align="center">4</td>
			<td align="center">Int</td>
			<td>账号不存在</td>
		</tr>
		<tr>
			<td align="center">100</td>
			<td align="center">Int</td>
			<td>登录成功</td>
		</tr>
	</table>
</div>
<?php $this->load->view('api/footer')?>