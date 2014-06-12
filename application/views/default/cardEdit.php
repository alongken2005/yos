<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Contracts and Banking > Edit</div>
		<form method="post" action="<?=site_url('dashboard/bankingEdit')?>">
			<table cellpadding="0" cellspacing="8" class="book_edit_table">
				<tr>
					<th>Bank Name:</th>
					<td><input type="text" class="input5" name="bank_name" value="<?=isset($card['cardinfo']['bank_name']) ? $card['cardinfo']['bank_name'] : ''?>"/></td>
				</tr>		
				<tr>
					<th>Account Owner Name:</th>
					<td><input type="text" class="input5" name="owner_name" value="<?=isset($card['cardinfo']['owner_name']) ? $card['cardinfo']['owner_name'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Bank Account #:</th>
					<td><input type="text" class="input5" name="bank_account" value="<?=isset($card['cardinfo']['bank_account']) ? $card['cardinfo']['bank_account'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Bank Routing #:</th>
					<td><input type="text" class="input5" name="bank_routing" value="<?=isset($card['cardinfo']['bank_routing']) ? $card['cardinfo']['bank_routing'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Bank Street Address:</th>
					<td><input type="text" class="input5" name="bank_street" value="<?=isset($card['cardinfo']['bank_street']) ? $card['cardinfo']['bank_street'] : ''?>"/></td>
				</tr>
				<tr>
					<th>City:</th>
					<td><input type="text" class="input5" name="city" value="<?=isset($card['cardinfo']['city']) ? $card['cardinfo']['city'] : ''?>"/></td>
				</tr>
				<tr>
					<th>State:</th>
					<td><input type="text" class="input5" name="state" value="<?=isset($card['cardinfo']['state']) ? $card['cardinfo']['state'] : ''?>"/></td>
				</tr>			<tr>
					<th>Zip Code:</th>
					<td><input type="text" class="input5" name="zipcode" value="<?=isset($card['cardinfo']['zipcode']) ? $card['cardinfo']['zipcode'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Country:</th>
					<td><input type="text" class="input5" name="country" value="<?=isset($card['cardinfo']['country']) ? $card['cardinfo']['country'] : ''?>"/></td>
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