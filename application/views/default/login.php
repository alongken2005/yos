<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/reg_login.css"/>

<div class="reg_login_box">
	<h1>Please Sign In</h1>
	<div class="left_info">
		<h2>Welcome,</h2>
		<div>
			Please sign in to get a personalized experience. Read a story as much or as little as you wish. Besides the free content, to read premium content, you only need to pay for what you care to read at a few cents per thousand words using your credit deposit. Also, we deposit $5 for every new users - use it for any premium content you like. Enjoy.			
		</div>
	</div>
	<form action="<?=site_url('user/do_login')?>" method="post" class="login_box">
		<table cellspacing="10" cellpadding="0">
			<tr>
				<th>Login Email:</th>
				<td><input type="text" name="username" value="" class="input5"></td>
			</tr>			
			<tr>
				<th>Password:</th>
				<td><input type="password" name="password" class="input5"></td>
			</tr>
			<tr>
				<th></th>
				<td>
					<label class="dan"><input type="checkbox" name=""/> 记住我的账户：</label>
					<a class="fgpwd" href="<?=site_url()?>">忘记密码？</a>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="submit" value="Sign In" class="btn1">
					<input type="hidden" value="<?=site_url('user/redirect')?>" class="direct_url">
				</td>
			</tr>
			<tr>
				<th></th>
				<td class="error"></td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	$(function() {
		$('.btn1').click(function() {
			$.post($('.login_box').attr('action'), $('.login_box').serialize(), function(data) {
				if(data.state != 1) {
					$('.login_box .error').removeClass('ok').html(data.msg).fadeIn("fast").delay(2000).fadeOut();
				} else {
					$('.login_box .error').addClass('ok').html(data.msg).fadeIn("fast").delay(2000).fadeOut();
					window.location.href = $('.login_box .direct_url').val();
				}
			}, 'json');
			return false;
		})
	})
</script>
<?php $this->load->view(THEME.'/footer');?>