<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Payment Reports</div>
		<div class="clear"></div>
		
		<div class="reportTab">
			<a href="javascript:void(0)" class="tab">Payments</a>
			<div class="sel">
				<a href="<?=site_url('dashboard/payment?limit=3')?>" <?=$this->input->get('limit') == 3 ? 'class="act"' : ''?>>Three Month</a>
				<a href="<?=site_url('dashboard/payment?limit=6')?>" <?=$this->input->get('limit') == 6 ? 'class="act"' : ''?>>Six Month</a>
				<a href="<?=site_url('dashboard/payment')?>" <?=!$this->input->get('limit') ? 'class="act"' : ''?>>All</a>
			</div>
		</div>
		<div class="reportBox">
		<?php 
			if(isset($lists) && $lists) { 
				foreach($lists as $k=>$v) {
		?>
			<div class="paybox">
				<h2>Earned <?=date('F Y', $v['payeddate'])?> &nbsp;&nbsp;&nbsp;&nbsp;Paid On <?=date('F m, Y', $v['reportdate'])?><span><?=$v['payment'].'&nbsp;&nbsp;'.$v['currency']?></span></h2>
				<table cellpadding="0" cellspacing="0" class="detail" width="100%" <?php if($k==0) { echo 'style="display:block"'; }?>>
					<tr>
						<th width="60">Currency</th>
						<th width="70">Beginning Balance</th>
						<th width="50">Earned</th>
						<th width="70">Pre-Tax Subtotal</th>
						<th width="70">Withholding Tax</th>
						<th width="60">Input Tax</th>
						<th width="82">Adjustments</th>
						<th width="70">Post-Tax Subtotal</th>
						<th width="60">FX Rate</th>
						<th>Payment</th>
					</tr>					
					<tr>
						<td><?=$v['currency']?></td>
						<td><?=$v['beginBal']?></td>
						<td><?=$v['earned']?></td>
						<td><?=$v['preTax']?></td>
						<td><?=$v['withholdingTax']?></td>
						<td><?=$v['InputTax']?></td>
						<td><?=$v['adjustments']?></td>
						<td><?=$v['postTax']?></td>
						<td><?=$v['FXRate']?></td>
						<td><?=$v['payment']?></td>
					</tr>
				</table>
			</div>
		<?php }} else { echo "<div style='text-align:center;line-height:2;'>No report!</div>"; } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.paybox h2').click(function() {
		$(this).siblings('.detail').toggle();
	});
</script>
<?php $this->load->view(THEME.'/footer');?>