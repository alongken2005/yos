<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<?php $sname = array(1 => 'Basic Info', 2 => 'Content', 3 => 'Preview'); ?>
		<div class="leader">Author Dashboard > Add New Book  > <?=isset($sname[$step]) ? $sname[$step] : 'Basic Info'?></div>

		<div class="book_step">
			<a href="<?=site_url('user/apply_author?step=1')?>" <?=$step == 1 ? 'class="step"' : ''?>>Mailing Address</a>
			<a href="<?=site_url('user/apply_author?step=2')?>" <?=$step == 2 ? 'class="step"' : ''?>>Bank Account Info</a>
			<a href="<?=site_url('user/apply_author?step=3')?>" <?=$step == 3 ? 'class="step"' : ''?>>Contract</a>
		</div>	
		<div class="book_step_bottom"></div>
	<?php if($step == 1) {?>
		<div class="author_des">Please provide your or your organization’s mailing address:</div>
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
				</tr>			<tr>
					<th>Zip Code:</th>
					<td><input type="text" class="input5" name="zipcode"/></td>
				</tr>
				<tr>
					<th>Country:</th>
					<td><input type="text" class="input5" name="country"/></td>
				</tr>			
				<tr>
					<th></th>
					<td>
						<input type="submit" class="btn1" value="Continue"/>
						<a href="<?=site_url('user/info')?>" class="btn1">Cancel</a>
					</td>
				</tr>															
			</table>
		</form>
	<?php } else if($step == 2) { ?>
		<div class="author_des">The bank account is for receiving book sales remittance.</div>
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
				</tr>			<tr>
					<th>Zip Code:</th>
					<td><input type="text" class="input5" name="zipcode"/></td>
				</tr>
				<tr>
					<th>Country:</th>
					<td><input type="text" class="input5" name="country"/></td>
				</tr>			
				<tr>
					<th></th>
					<td>
						<input type="submit" class="btn1" value="Continue"/>
						<a href="<?=site_url('user/info')?>" class="btn1">Cancel</a>
					</td>
				</tr>															
			</table>
		</form>
	<?php } else if($step == 3) {?>
		<div class="author_des">Please review and agree to the following contract with YouShelf Inc..</div>
		<form method="post" action="<?=site_url('user/apply_author?step=3')?>">
			<div class="scrollstep">
			
			<h3>Author/Publisher Terms and Conditions by YouShelf Inc.</h3>

			<div>To publish content through YouShelf platforms (website <a href="http://www.youshelf.com" target="_blank">www.youshelf.com</a> plus mobile apps), all author/publisher need to agree to the following terms and conditions: </div>
			<div class="p"><em>1. &nbsp;</em><span>As an author/publisher, you guarantee that you own the necessary rights to publish via YouShelf for all the content for the duration you publish through us.</span></div>
			<div class="p"><em>2. &nbsp;</em><span>As an author/publisher, you ensure the accuracy and presentation of the content are up to the best quality standard possible.</span></div>
			<div class="p"><em>3. &nbsp;</em><span>As an author/publisher, you are responsible for any third-party’s claim of rights and damages; you imdemnify YouShelf for any such claims and damages related to the content you publish on YouShelf.</span></div>
			<div class="p"><em>4. &nbsp;</em><span>We have the rights to take your content down without warning for any purpose(s) we deem as necessary or reasonable.</span></div>
			<div class="p"><em>5. &nbsp;</em><span>As an author/publisher, you can complain any takedown decision to a YouShelf committee for review, but you agree that you will take the final decision without any legal or public/media relations actions against YouShelf. </span></div>
			<div class="p"><em>6. &nbsp;</em><span>No hatred, pornographic, raciest, child abuse contents will be tolerated on YouShelf.  Violation of this principle may lead to the ban of your account and content altogether on YouShelf.</span></div>
			<div class="p"><em>7. &nbsp;</em><span>As an author/publisher, you can take your content down anytime by contacting us.  Our author support team will respond to your take down request with the best effort possible, usually within 2 business days.  </span></div>
			<div class="p"><em>8. &nbsp;</em><span>We may need to take down your content due to legal requirement by the government or third-parties with legal authorities.  We will notify such takedown actions after fact through email and snail mail.  </span></div>
			<div class="p"><em>9. &nbsp;</em><span>We currently pay 90% of proceeds of all paid content sales to author/publisher publishing via YouShelf.  Proceed is book sales revenue excluding any necessary withholding taxes, banking and payment tool provider fees (e.g., credit card company charges, remittance fees, etc), marketplace platform charges (e.g., Apple app store charges). </span></div>
			<div class="p"><em>10. &nbsp;</em><span>We may adjust the proceed sharing plan anytime and give 60 days written noitice ahead of the change through email and snail mail.  During the time, you can decide whether to continue to publish the content through us by responding to such notice.</span></div>
			</div>
			<div>
				<input type="hidden" name="agree" value="1"/>
				<input type="submit" class="btn1" value="Agree"/>
				<a href="<?=site_url('user/info')?>" class="btn1">Cancel</a>
				<a href="data/YouShelf Author-Publisher Terms and Conditions.pdf" target="_blank" class="btn1">View PDF</a>
			</div>			
		</form>	
	<?php } else if($step == 4) { ?>
		<h3 style="font-size:20px;margin-top:10px">Dear <?=$user['username']?>,</h3>
		<div style="font-size:14px;margin-top:20px;">
		You have successfully joined YouShelf community as an author!<br><br>
		You can now build your fan base among YouShelf community members and lead them through the exciting journeys you create for and with them!<br><br>
		As a YouShelf author, you can upload stories, check readership stats and sales stats all in one place: YouShelf Author Dashboard. Go ahead and explore. Let us know your feedback from the link on the dashboard or simply email us at author-support@youshelf.com.<br><br>
		</div>
		<a href="<?=site_url('book/lists')?>" class="btn1 closego">Go To Author Dashboard</a>
	<?php } ?>
	</div>
</div>
