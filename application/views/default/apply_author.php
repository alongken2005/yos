<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>


<div class="float_box">
<?php if($step == 1) {?>
	<h2>Author Account Step 1 of 3: Mailing Address</h2>

	<div>Please provide your or your organization’s mailing address:</div>
	<form method="post" action="<?=site_url('user/apply_author?step=1')?>">
		<table cellpadding="0" cellspacing="8" class="book_edit_table">
			<tr>
				<th>Organization Name:<br>(Optional)</th>
				<td><input type="text" class="input5" name="organization" value=""/></td>
			</tr>		
			<tr>
				<th>Your Name:</th>
				<td><input type="text" class="input5" name="username" value="<?=$user['username']?>"/></td>
			</tr>
			<tr>
				<th>Street Address:</th>
				<td><input type="text" class="input5" name="street"/></td>
			</tr>
			<tr>
				<th>City:</th>
				<td><input type="text" class="input5" name="city"/></td>
			</tr>
			<tr>
				<th>State:</th>
				<td><input type="text" class="input5" name="state"/></td>
			</tr>
			<tr>
				<th>Country:</th>
				<td><input type="text" class="input5" name="country"/></td>
			</tr>			
			<tr>
				<th></th>
				<td>
					<input type="submit" class="btn1" value="Continue">
					<input type="button" class="btn1 close" value="Cancel">
				</td>
			</tr>															
		</table>
	</form>
<?php } else if($step == 2) { ?>
	<h2>Author Account Step 2 of 3: Bank Account Info</h2>

	<div>The bank account is for receiving book sales remittance.</div>
	<form method="post" action="<?=site_url('user/apply_author?step=2')?>">
		<table cellpadding="0" cellspacing="8" class="book_edit_table">
			<tr>
				<th>Bank Name:</th>
				<td><input type="text" class="input5" name="bank_name" value=""/></td>
			</tr>		
			<tr>
				<th>Account Owner Name:</th>
				<td><input type="text" class="input5" name="owner_name" value="<?=$user['username']?>"/></td>
			</tr>
			<tr>
				<th>Bank Account #:</th>
				<td><input type="text" class="input5" name="bank_account"/></td>
			</tr>
			<tr>
				<th>Bank Routing #:</th>
				<td><input type="text" class="input5" name="bank_routing"/></td>
			</tr>
			<tr>
				<th>Bank Street Address:</th>
				<td><input type="text" class="input5" name="bank_street"/></td>
			</tr>
			<tr>
				<th>City:</th>
				<td><input type="text" class="input5" name="city"/></td>
			</tr>
			<tr>
				<th>State:</th>
				<td><input type="text" class="input5" name="state"/></td>
			</tr>
			<tr>
				<th>Country:</th>
				<td><input type="text" class="input5" name="country"/></td>
			</tr>			
			<tr>
				<th></th>
				<td>
					<input type="submit" class="btn1" value="Continue">
					<input type="button" class="btn1 close" value="Cancel">
				</td>
			</tr>															
		</table>
	</form>
<?php } else if($step == 3) {?>
	<h2>Author Account Step 3 of 3: Contract</h2>

	<div>Please review and agree to the following contract with YouShelf Inc..</div>
	<form method="post" action="<?=site_url('user/apply_author?step=3')?>">
		<div class="scrollstep">
		Publishing Agreement with YouShelf Inc.<br><br>
		This is the publishing agreement between the author/publisher and YouShelf Inc. Details are included in this area. Scroll down to review the content. This is the publishing agreement between the author/publisher and YouShelf Inc. Details are included in this area. Scroll down to review the content. This is the publishing agreement between the author/publisher and YouShelf Inc. Details are included in this area. Scroll down to review the content. This is the publishing agreement between the author/publisher and YouShelf Inc. Details are included in this area. Scroll down to review the content
		</div>
		<div>
			<input type="submit" class="btn1" value="Agree">
			<input type="button" class="btn1 close" value="Cancel">
			<a href="<?=site_url()?>" target="_blank" class="btn1">View PDF</a>
		</div>			
	</form>	
<?php } else if($step == 4) { ?>
	<h3 style="font-size:20px;">Dear User’s Name,</h3>
	<div style="font-size:14px;margin-top:20px;">
	You have successfully joined YouShelf community as an author!<br><br>
	You can now build your fan base among YouShelf community members and lead them through the exciting journeys you create for and with them!<br><br>
	As a YouShelf author, you can upload stories, check readership stats and sales stats all in one place: YouShelf Author Dashboard. Go ahead and explore. Let us know your feedback from the link on the dashboard or simply email us at author-support@youshelf.com.<br><br>
	</div>
	<a href="<?=site_url()?>" target="_blank" class="btn1 closego">Go To Author Dashboard</a>
	<a href="javascript:void(0)" class="btn1 close">Close</a>
<?php } ?>
</div>

<script type="text/javascript">
	$(function() {
		$('.close').click(function() {
			parent.callback();
			return false;
		})

		$('.closego').click(function() {
			parent.callback($(this).attr('href'));
			return false;
		})
	})
</script>
