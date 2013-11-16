<ul class="menu">
	<li <?=$active == 'login' ? 'class="active"' : ''?>><a href="<?=site_url('api/login')?>">登录</a></li>
	<li <?=$active == 'register' ? 'class="active"' : ''?>><a href="<?=site_url('api/register')?>">注册</a></li>
	<li <?=$active == 'down' ? 'class="active"' : ''?>><a href="<?=site_url('api/down')?>">资源下载</a></li>
	<li <?=$active == 'pay' ? 'class="active"' : ''?>><a href="<?=site_url('api/pay')?>">apple支付</a></li>
	<li <?=$active == 'state_code' ? 'class="active"' : ''?>><a href="<?=site_url('api/state_code')?>">返回值列表</a></li>
</ul>