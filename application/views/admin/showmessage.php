<!DOCTYPE html>
<html>
<head>
	<title>manage</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?=BASE_VIEW?>admin/css/style.css" type="text/css" />
</head>
<body>
<div class="box">
	<div class="showmessage">
		<h2>Information Tips</h2>
		<div class="b8_contenti">
			<p><?=$message?></p>
			<p>
			<?php
				if($url_forward):
					echo anchor($url_forward, 'Jumping...');
				else:
			?>
				<!--a href="javascript:history.go(-1);">Go back</a> |
				<a href="<?=base_url()?>">Back home</a-->
			<?php endif;?>
			</p>
		</div>
	</div>
</div>
</body>
</html>