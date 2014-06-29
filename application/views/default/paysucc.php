<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">

	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">My Account Info > Payment Tool</div>

		<div style="float:left;width:400px; line-height:1.5">
			<h2>Thank you</h2>
			<div style="padding:5px 0;">We're placing your order</div>
			<a href="<?=site_url('user/info')?>" class="btn2">Back</a>
		</div>

		<div class="yourorder">
			<h3>Your order</h3>
			<h4><?=$order['orderID']?></h4>
			<h4>0% VAT <span>$0.00</span></h4>
			<h4>Total <span>$<?=$order['price']?></span></h4>
		</div>
		
	</div>
</div>