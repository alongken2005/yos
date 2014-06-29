<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/reg_login.css"/>

<div class="reg_login_box">
	<h1>Sign Up</h1>
	<div class="left_info">
		<h2>Dear Reader,</h2>
		<div><br>
		Welcome to YouShelf community!<br><br>
		Our authors here let you vote with cents on their works. Using reading credit you deposit, you can pay for any premium section content at a few cents per thousand words with no interruption to your reading. Follow the story as far as your heart and mind wish to, and pay less than the full book price when you finish the whole book!<br><br>
		To get you started, we will deposit $5 to your account after you sign up to use toward any premium content.
		</div>
	</div>
	<form action="<?=site_url('user/do_reg')?>" method="post" class="login_box">
		<table cellspacing="10" cellpadding="0">
			<tr>
				<th>Your Preferred Name:</th>
				<td><input type="text" name="username" value="" class="input5"></td>
			</tr>			
			<tr>
				<th>Login Email:</th>
				<td><input type="text" name="email" value="" class="input5"></td>
			</tr>
			<tr>
				<th>Password:</th>
				<td><input type="password" name="password" class="input5"></td>
			</tr>
			<tr>
				<th>Confirm Password:</th>
				<td><input type="password" name="password2" class="input5"></td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="submit" value="Sign Up" class="btn1 reg_submit left">
					<input type="hidden" value="<?=site_url('user/redirect')?>" class="direct_url">
					<a href="<?=site_url('user/fLogin')?>" class="facebookLogin left"><img src="<?=THEME_VIEW?>images/facebook.png" width="16" class="left"/><span style="float:left; padding-left:5px; line-height:16px">Sign In</span></a>
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
		$('.reg_submit').click(function() {
			$.post($('.login_box').attr('action'), $('.login_box').serialize(), function(data) {
				if(data.state != 1) {
					$('.login_box .error').removeClass('ok').html(data.msg).fadeIn("fast");
				} else {
					$('.login_box .error').addClass('ok').html(data.msg).fadeIn("fast");
					window.location.href = $('.login_box .direct_url').val();
				}
			}, 'json');
			return false;
		})
	})
</script>
<?php $this->load->view(THEME.'/footer');?>