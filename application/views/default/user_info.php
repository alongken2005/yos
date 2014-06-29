<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">My Account Info > Login Info</div>

		<?php if($account['facebookId']) { ?>
		<h2>Signed in via Facebook</h2>
		<?php } else { ?>
		<h2>Login Info</h2>
		<?php } ?>
		
		<?php if(isset($card['cardinfo'])) { ?>
		<div style="padding-top:10px;">My Credit Card: ending with xxxx-<?=substr($card['cardinfo']['card_num'], -4)?> &nbsp;&nbsp;<a href="<?=site_url('dashboard/creditEdit?id='.$card['id'])?>" class="blue">Edit</a></div>
		<?php } ?>
		<div style="padding-top:10px;">My Reading Credit Deposit: $<?=$account['deposit']?> &nbsp;&nbsp;<a href="<?=site_url('pay')?>" class="blue">Deposit more credit</a></div>
		
		<?php if($account['facebookId']) { ?>
		<form action="<?=site_url('user/facebookEdit')?>" method="post">
		<table cellpadding="0" cellspacing="8" class="book_edit_table">
			<tr>
				<th>Preferred Name:</th>
				<td><input type="text" name="username" class="input5" value="<?=$account['username']?>"/></td>
			</tr>		
			<tr>
				<th>Account Login:</th>
				<td><?=$account['loginname']?></td>
			</tr>			
			<tr>
				<th>Email:</th>
				<td><input type="text" name="email" class="input5" value="<?=$account['email']?>"/></td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="submit" class="btn1" value="Save">
				<?php if($account['is_author'] != 1) { ?>	
					<a href="<?=site_url('user/apply_author')?>" class="applyAuthor">Apply to be an author for YouShelf ></a>
				<?php } ?>
				</td>
			</tr>			
		</table>
		</form>
		<?php } else { ?>
		<form action="<?=site_url('user/userEdit')?>" method="post">
		<table cellpadding="0" cellspacing="8" class="book_edit_table">
			<tr>
				<th>Preferred Name:</th>
				<td><input type="text" name="username" class="input5" value="<?=$account['username']?>"/></td>
			</tr>		
			<tr>
				<th>Account Login:</th>
				<td><?=$account['loginname']?></td>
			</tr>		
			<tr>
				<th>Login Email:</th>
				<td><input type="text" name="email" class="input5" value="<?=$account['email']?>"/></td>
			</tr>
			<tr>
				<th>Old Password:</th>
				<td><input type="password" name="oldPassword" class="input5"/></td>
			</tr>
			<tr>
				<th>New Password:</th>
				<td><input type="password" name="newPassword" class="input5"/></td>
			</tr>
			<tr>
				<th>Confirm Password:</th>
				<td><input type="password" name="confirmNewPassword" class="input5"/></td>
			</tr>			
			<tr>
				<th></th>
				<td>
					<input type="submit" class="btn1" value="Save">
				<?php if($account['is_author'] != 1) { ?>	
					<a href="<?=site_url('user/apply_author')?>" class="applyAuthor">Apply to be an author for YouShelf ></a>
				<?php } ?>
				</td>
			</tr>
		</table>
		</form>
		<?php } ?>	
	</div>
</div>