<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>
<script type="text/javascript" src="common/datepicker/WdatePicker.js"></script>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<div class="leader">Author Dashboard > Sales and Trends</div>
		<div class="clear"></div>
		<form action="<?=site_url('dashboard/sales')?>" method="get" class="salesForm">
			<input type="text" name="stime" value="<?=$this->input->get('stime')?>" placeholder="Start date" class="Wdate" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/> 
			<b style="float:left;line-height:26px;margin:0 5px;"> - </b> 
			<input type="text" name="etime" value="<?=$this->input->get('etime')?>" placeholder="End date" class="Wdate" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/> 
			<input type="submit" value="Search" class="btn2" style="margin-left:5px;"/>
		</form>
		

		<table cellpadding="0" cellspacing="0" class="sales_list_table">
			<tr>
				<th>Title</th>
				<th width="70">'000 Words</th>
				<th width="80">Customer Price</th>
				<th width="75">Proceeds</th>
				<th width="100">Total Customer Payment</th>
				<th width="75">Total Proceeds</th>
				<th width="105">Pay Time</th>
			</tr>
		<?php 
			if(isset($lists) && $lists) { 
				$total_customer_price = $total_proceeds = $total_words = 0; 
				foreach($lists as $v) { 
					$total_customer_price += $v['total_customer_price'];
					$total_proceeds += $v['total_proceeds'];
					$total_words += $v['each'];
		?>
			<tr>
				<td><?=$v['title']?></td>
				<td><?=$v['each']?></td>
				<td><?=$v['customer_price']?></td>
				<td><?=$v['proceeds']?></td>
				<td><?=$v['total_customer_price']?></td>
				<td><?=$v['total_proceeds']?></td>
				<td><?=date('Y-m-d H:i', $v['ctime'])?></td>
			</tr>
		<?php } } ?>
		</table>

		<?php if(isset($lists) && $lists) { ?>
		<div style="margin-top:15px; border-top:1px solid #CCC;font-weight:600;padding-top:5px;font-size:14px">
			<span style="margin-left:195px;float:left;width:100px;">total:<?=$total_words?></span>
			<span style="margin-left:145px;float:left;width:105px;">total:<?=sprintf("%.2f", $total_customer_price)?></span>
			<span style="float:left;">total:<?=sprintf("%.2f", $total_proceeds)?></span>
		</div>
		<?php } ?>
		<?php echo isset($pagination) ? $pagination : '';?>
	</div>

</div>
