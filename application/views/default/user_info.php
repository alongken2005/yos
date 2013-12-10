<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
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
				<td><a href="<?=site_url('user/apply_author')?>" class="applyAuthor">Apply to be an author for YouShelf ></a></td>
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

<script type="text/javascript" src="common/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="common/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
	var obj;
	$(function() {
		$(".applyAuthor").fancybox({
			'width'				: 540,
			'height'			: 400,
			'padding'			: 2,
			'type'				: 'iframe',
			'centerOnScroll'	: true,
			'overlayOpacity'	: 0
		});		

	})

	function callback(url) {
		$.fancybox.close();
		if(url) window.location.href="<?=site_url('')?>";
	}
</script>