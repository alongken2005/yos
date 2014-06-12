<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Contracts and Banking</div>
		<div class="clear"></div>

		<table cellpadding="0" cellspacing="0" class="sales_list_table">
			<tr>
				<th>street</th>
				<th width="370">Name</th>
				<th width="80">Bank Acct</th>
				<th width="60" style="text-align: center;">Edit</th>
			</tr>
		<?php 
			if(isset($lists) && $lists) { 
				foreach($lists as $v) { 
		?>
			<tr>
				<td><?=isset($v['cardinfo']['bank_street']) ? $v['cardinfo']['bank_street'] : ''?></td>
				<td><?=isset($v['cardinfo']['owner_name']) ? $v['cardinfo']['owner_name'] : '';?></td>
				<td><?=(isset($v['cardinfo']['bank_account']) ? '*'.substr($v['cardinfo']['bank_account'], -4) : 0)?></td>
				<td style="text-align: center;"><a href="<?=site_url('dashboard/bankingEdit?id='.$v['id'])?>">Edit</a></td>
			</tr>
		<?php } } ?>
		</table>
		<?php echo isset($pagination) ? $pagination : '';?>

		<a href="data/YouShelf Author-Publisher Terms and Conditions.pdf" target="_blank" style="display:block;padding-top:10px">Terms and Conditions for Authors</a>
	</div>


</div>
<?php $this->load->view(THEME.'/footer');?>