<?php $this->load->view('api/header')?>
<div class="module">
	<form method="post" action="<?=site_url('user/register')?>">
	<table cellspacing="0" cellpadding="0" border="0" class="api_table1">
		<tr>
			<th colspan="2">注册</th>
		</tr>
		<tr>
			<td class="name">邮箱</td>
			<td><input type="text" name="email" class="input3"></td>
		</tr>
		<tr>
			<td class="name">密码</td>
			<td><input type="password" name="password" class="input3"></td>
		</tr>
		<tr>
			<td class="name">确认密码</td>
			<td><input type="password" name="password2" class="input3"></td>
		</tr>
		<tr>
			<td class="name">姓名</td>
			<td><input type="text" name="name" class="input3"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="注 册" class="submit"></td>
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
			<td><?=base_url('user/register')?></td>
			<td></td>
		</tr>
		<tr>
			<td>email</td>
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
		<tr>
			<td>password2</td>
			<td align="center">String</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>name</td>
			<td align="center">String</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>country</td>
			<td align="center">Int</td>
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
			<td>邮箱格式错误</td>
		</tr>
		<tr>
			<td align="center">2</td>
			<td align="center">Int</td>
			<td>密码长度不能少于6位</td>
		</tr>
		<tr>
			<td align="center">3</td>
			<td align="center">Int</td>
			<td>两次密码输入不一致</td>
		</tr>
		<tr>
			<td align="center">4</td>
			<td align="center">Int</td>
			<td>注册失败,其他错误</td>
		</tr>
		<tr>
			<td align="center">100</td>
			<td align="center">Int</td>
			<td>注册成功</td>
		</tr>
	</table>
</div>
<?php $this->load->view('api/footer')?>