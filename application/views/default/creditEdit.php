<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Login Info > Edit</div>
		<form method="post" action="<?=site_url('dashboard/creditEdit')?>">
			<table cellpadding="0" cellspacing="8" class="book_edit_table">
				<tr>
					<th>Card Holder First Name:</th>
					<td>
						<input type="text" name="holder_first_name" class="input2" value="<?=set_value('holder_first_name', isset($card['cardinfo']['holder_first_name']) ? $card['cardinfo']['holder_first_name'] : '')?>"/>
						<?php if(form_error('holder_first_name')) { echo form_error('holder_first_name'); } ?>
					</td>
				</tr>
				<tr>
					<th>Card Holder Last Name:</th>
					<td>
						<input type="text" name="holder_last_name" class="input2" value="<?=set_value('holder_last_name', isset($card['cardinfo']['holder_last_name']) ? $card['cardinfo']['holder_last_name'] : '')?>"/>
						<?php if(form_error('holder_last_name')) { echo form_error('holder_last_name'); } ?>
					</td>
				</tr>
				<tr>
					<th>Card Number:</th>
					<td>
						<input type="text" name="card_num" class="input2" value="<?=set_value('card_num', isset($card['cardinfo']['card_num']) ? 'xxxx-'.substr($card['cardinfo']['card_num'], -4) : '')?>"/>
						<?php if(form_error('card_num')) { echo form_error('card_num'); } ?>
					</td>
				</tr>
				<tr>							
					<th>Billing Address:</th>
					<td><input type="text" name="billing_ad" class="input2" value="<?=isset($card['cardinfo']['billing_ad']) ? $card['cardinfo']['billing_ad'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Expiration Date:</th>
					<td>
						<input type="text" name="exp_date" class="input2" value="<?=set_value('exp_date', isset($card['cardinfo']['exp_date']) ? $card['cardinfo']['exp_date'] : '')?>"/>
						<?php if(form_error('exp_date')) { echo form_error('exp_date'); } ?>
					</td>	
				</tr>
				<tr>						
					<th>Security Code:</th>
					<td><input type="text" name="scur_code" class="input2" value="<?=isset($card['cardinfo']['scur_code']) ? $card['cardinfo']['scur_code'] : ''?>"/></td>
				</tr>
				<tr>
					<th>State:</th>
					<td><input type="text" name="state" class="input2" value="<?=isset($card['cardinfo']['holder_first_name']) ? $card['cardinfo']['holder_first_name'] : ''?>"/></td>
				</tr>
				<tr>							
					<th>Country:</th>
					<td><input type="text" name="country" class="input2" value="<?=isset($card['cardinfo']['holder_first_name']) ? $card['cardinfo']['holder_first_name'] : ''?>"/></td>
				</tr>			
				<tr>
					<th></th>
					<td>
						<input type="hidden" name="id" value="<?=$this->input->get('id')?>"/>
						<input type="submit" class="btn1" value="Continue"/>
					</td>
				</tr>															
			</table>
		</form>
	</div>
</div>		
<?php $this->load->view(THEME.'/footer');?>