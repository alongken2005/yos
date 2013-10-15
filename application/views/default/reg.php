<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/reg_login.css"/>

<div class="reg_login_box">
	<h1 class="png"></h1>
	<form action="<?=site_url('user/login')?>" method="post" class="login_box">
		<h2 class="png"></h2>
		<label>用户名/邮箱：</label>
		<div class="input_box">
			<input type="text" name="username" value="" class="rl_input">
		</div>
		<label>登录密码：</label><a class="fgpwd" href="<?=site_url()?>">忘记密码？</a>
		<div class="clear"></div>
		<div class="input_box">
			<input type="password" name="password" class="rl_input">
		</div>
		<label class="dan"><input type="checkbox" name=""/> 记住我的账户：</label>
		<div class="input_box">
			<input type="submit" value=" " class="login_submit png">
			<input type="hidden" value="<?=site_url('reg/redirect')?>" class="direct_url">
		</div>
		<div class="clear"></div>
		<div class="error"></div>
	</form>
	<form action="<?=site_url('user/register')?>" method="post" class="reg_box png">
		<h2 class="png"></h2>
		<label>登录邮箱：</label>
		<div class="input_box">
			<input type="text" name="email" value="" class="rl_input">
		</div>
		<label>登录密码：</label>
		<div class="clear"></div>
		<div class="input_box">
			<input type="password" name="password" class="rl_input">
		</div>
		<label>确认密码：</label>
		<div class="clear"></div>
		<div class="input_box">
			<input type="password" name="password2" class="rl_input">
		</div>
		<div class="input_box">
			<input type="submit" value=" " class="reg_submit png">
			<input type="hidden" value="<?=site_url('reg/redirect')?>" class="direct_url">
		</div>
		<div class="agree"><input type="checkbox" name="agree" value="1"/> 同意儿童之路相关条款及优秀内容推送</div>
		<div class="error"></div>
	</form>
</div>
<!--[if IE 6]>
<script type="text/javascript" src="<?=base_url('./common/js/fixpng-min.js')?>"></script>
<script type="text/javascript">
DD_belatedPNG.fix('.png');
</script>
<![endif]-->
<script type="text/javascript">
	$(function() {
		$('.login_submit').click(function() {
			$.post($('.login_box').attr('action'), $('.login_box').serialize(), function(data) {
				if(data.state > 0) {
					$('.login_box .error').removeClass('ok').html(data.msg).fadeIn("fast").delay(2000).fadeOut();
				} else {
					$('.login_box .error').addClass('ok').html(data.msg).fadeIn("fast").delay(2000).fadeOut();
					window.location.href = $('.login_box .direct_url').val();
				}
			}, 'json');
			return false;
		})

		$('.reg_submit').click(function() {
			$.post($('.reg_box').attr('action'), $('.reg_box').serialize(), function(data) {
				if(data.state > 0) {
					$('.reg_box .error').removeClass('ok').html(data.msg).fadeIn("fast").delay(2000).fadeOut();
				} else {
					$('.reg_box .error').addClass('ok').html(data.msg).fadeIn("fast").delay(2000).fadeOut();
					window.location.href = $('.reg_box .direct_url').val();
				}
			}, 'json');
			return false;
		})
	})
</script>
<?php $this->load->view(THEME.'/footer');?>