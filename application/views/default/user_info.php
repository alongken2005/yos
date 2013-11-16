<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box">
	<?php $this->load->view(THEME.'/slider_left');?>

	<div class="space_box">
		<h2>My Account Info</h2>

		<table cellpadding="0" cellspacing="8" class="book_edit_table">
			<tr>
				<th>Login Email:</th>
				<td><?=$account['email']?></td>
			</tr>		
			<tr>
				<th>Preferred Name:</th>
				<td><input type="text" class="input5" value="<?=$account['username']?>"/></td>
			</tr>
			<tr>
				<th>Old Password:</th>
				<td><input type="text" class="input5"/></td>
			</tr>
			<tr>
				<th>New Password:</th>
				<td><input type="text" class="input5"/></td>
			</tr>
			<tr>
				<th>Confirm Password:</th>
				<td><input type="text" class="input5"/></td>
			</tr>
			<tr>
				<th></th>
				<td><a href="<?=site_url()?>">Apply to be an author for YouShelf ></a></td>
			</tr>			
			<tr>
				<th></th>
				<td>
					<input type="submit" class="btn1" value="Save">
				</td>
			</tr>															
		</table>
	</div>
</div>