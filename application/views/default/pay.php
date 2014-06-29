<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">

	<?=$slider_left?>

	<div class="space_box">
	<?php if($step == 'payTools') { ?>
		<div class="leader">My Account Info > Add Deposit</div>

		<h2>Please select deposit amount and the payment tool</h2>

		<ul class="selectMoney" style="padding-bottom:0">
			<li>
				<a href="javascript:void(0)" class="active">$5</a><input type="radio" name="money" value="5" checked/>
			</li>
			<li>
				<a href="javascript:void(0)">$15</a><input type="radio" name="money" value="15"/>
			</li>
			<li>
				<a href="javascript:void(0)">$25</a><input type="radio" name="money" value="25"/>
			</li>
			<li>
				<a href="javascript:void(0)">$50</a><input type="radio" name="money" value="50"/>
			</li>
		</ul>		
		<ul class="paytool">
			<li>
				<a href="javascript:void(0)" class="active" style="padding-top:7px; height:53px;">Credit Card</a><input type="radio" name="payType" value="selectCard" checked/>
			</li>
		</ul>
		<div class="clear"></div>
		<form action="<?=site_url('pay')?>" method="post" id="goSelectCard">
			<input type="hidden" name="step" id="step"/>
			<input type="hidden" name="selectMoney" id="selectMoney"/>
			<input type="button" class="btn1 continue" value="Continue">
		</form>
		
		<div class="deposit">Your current reading credit deposit is：<b> $<?=$userInfo['deposit']?></b></div>
		<script type="text/javascript">
			var paytype = 'selectCard';
			var selectMoney = 5;

			$('.paytool li').click(function() {
				$('.paytool a').removeClass('active');
				$('.paytool input').attr('checked',true);
				$(this).children('a').addClass('active');
				$(this).children('input').attr('checked',true);
			});

			$('.selectMoney li').click(function() {
				$('.selectMoney a').removeClass('active');
				$('.selectMoney input').attr('checked',true);
				$(this).children('a').addClass('active');
				$(this).children('input').attr('checked',true);
			});

			$('.continue').click(function() {
				paytype = $('.paytool input:checked').val();
				selectMoney = $('.selectMoney input:checked').val();				

				if(selectMoney < 0) {
					alert("Select deposit amount first");
				}

				$('#step').val(paytype);
				$('#selectMoney').val(selectMoney);				

				if(paytype == 'selectCard') {
					$("#goSelectCard").submit();
					//window.location.href='<?=site_url("pay?step=")?>'+paytype+'&selectMoney='+selectMoney;
				} else {
					alert('sorry')
				}
			});
		</script>
	<?php } else if($step == 'selectCard') { ?>
		<div class="leader">My Account Info > Credit Card Info</div>

		<h2>Please select the credit card</h2>
		<form action="<?=site_url('pay/index?step=selectCard')?>" method="post">
		<table cellspacing="5" cellpadding="0" class="cardTable">
		<?php if(isset($card) && $card) { ?>
			<tr>
				<th>Use the credit card:</th>
				<td colspan="3" style="line-height:20px">
				<?php foreach($card as $k => $v) { ?>
					ending with xxxx-<?=substr($v['cardinfo']['card_num'], -4)?> <input type="radio" name="whichCard" value="<?=$v['id']?>" <?=$k===0 ? 'checked="checked"' : ''?>/><br>
				<?php } ?>	
				</td>
			</tr>
		<?php } ?>
			<tr>
				<th>Use a new credit card:</th>
				<td colspan="3"><input type="radio" name="whichCard" value="new" <?=!$card ? 'checked="checked"' : ''?>/></td>
			</tr>
			<tr>
				<th>Card Holder First Name:</th>
				<td>
					<input type="text" name="holder_first_name" class="input2" value="<?=set_value('holder_first_name')?>"/>
					<?php if(form_error('holder_first_name')) { echo form_error('holder_first_name'); } ?>
				</td>
			</tr>
			<tr>
				<th>Card Holder Last Name:</th>
				<td>
					<input type="text" name="holder_last_name" class="input2" value="<?=set_value('holder_last_name')?>"/>
					<?php if(form_error('holder_last_name')) { echo form_error('holder_last_name'); } ?>
				</td>
			</tr>
			<tr>
				<th>Card Number:</th>
				<td>
					<input type="text" name="card_num" class="input2" value="<?=set_value('card_num')?>"/>
					<?php if(form_error('card_num')) { echo form_error('card_num'); } ?>
				</td>
			</tr>
			<tr>							
				<th>Billing Address:</th>
				<td><input type="text" name="billing_ad" class="input2" /></td>
			</tr>
			<tr>
				<th>Expiration Date:</th>
				<td>
					<input type="text" name="exp_date" class="input2" value="<?=set_value('exp_date')?>"/>
					<?php if(form_error('exp_date')) { echo form_error('exp_date'); } ?>
				</td>	
			</tr>
			<tr>						
				<th>Security Code:</th>
				<td><input type="text" name="scur_code" class="input2"/></td>
			</tr>
			<tr>
				<th>State:</th>
				<td><input type="text" name="state" class="input2"/></td>
			</tr>
			<tr>							
				<th>Country:</th>
				<td><input type="text" name="country" class="input2"/></td>
			</tr>
			<tr>
				<th>Save this credit card</th>
				<td colspan="3"><input type="checkbox" name="save" value="1" checked="checked" /></td>
			</tr>
			<tr>
				<th></th>
				<td colspan="3">
					<input type="hidden" name="selectMoney" value="<?=$selectMoney?>"/>
					<input type="submit" value="Continue" class="btn1"/>
					<input type="button" value="Cancel" class="btn1" style="margin-left:10px;" onclick="return checkOrder();" />
				</td>
			</tr>			
		</table>

		<div class="yourorder">
			<h3>Your order</h3>
			<h4>0% VAT <span>$0.00</span></h4>
			<h4>Total <span>$<?=$selectMoney?></span></h4>
		</div>
		</form>
		<div class="clear"></div>
		<div style="padding-left:150px;">Your credit card will be charged once you click Continue</div>
		<script type="text/javascript">
			function checkOrder() {
				if(confirm('Are you sure you want to cancel this order?')) {
					window.location.href="<?=site_url('pay')?>"
				}
				return false;
			}
		</script>
	<?php } ?>
	</div>
</div>