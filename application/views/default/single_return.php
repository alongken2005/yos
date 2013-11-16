<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/single.css"/>
	<div class="back_layer">
		<div class="box"><a href="<?=site_url('single')?>" class="back">返回购买首页</a></div>
	</div>
	<div class="iland"></div>
	<div class="single_top single_return">
	<?php if($buy_state == 'ok'):?>
        <div class="congs">恭喜您，购买成功</div>
		我们会尽快为您寄出您所购买的商品。<br>
		感谢您使用 “ChildRoad - 儿童之路”  <br>
	<?php else:?>
        <div class="congf">您的支付未成功，请尝试重新支付</div>
		<a href="<?=site_url('single/check?id=1')?>">返回上一步</a><br>
	<?php endif;?>
		<div class="after">售后联系电话：0571-1234567</div>
		<table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_return">
			<tr>
				<td colspan="6" class="addr">收货地址：<?=$sorder['receiver'].'，'.$sorder['tel'].'，'.$sorder['address'].'，'.$sorder['postcode']?></td>
			</tr>
			<tr>
				<th colspan="2">商品</th>
				<th width="100">单价(元)</th>
				<th width="60">数量</th>
				<th width="130">优惠</th>
				<th width="100">商品总价(元)</th>
			</tr>
			<tr>
				<td height="120" width="120" align="center"><img src="<?=get_thumb($suit['cover'])?>"/></td>
				<td style="padding-left: 10px;"><?=$suit['title']?></td>
				<td align="center"><?=$suit['orig_price']?></td>
				<td align="center"><?=$sorder['amount']?></td>
				<td style="padding-left: 10px;">节省￥<?=($suit['orig_price']-$suit['price'])*$sorder['amount']?><br>免费送货</td>
				<td align="center"><?=$sorder['price']?></td>
			</tr>
		</table>
	</div>
	<div class="single_bottom2"></div>
</div>
<table cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="bottom_left" width="50%"></td>
		<td class="bottom_center">
			<div class="box" style="text-align: center; font-family: '微软雅黑'; font-size: 20px; font-weight: 600; margin-top: 30px;"><a href="<?=base_url()?>" target="_blank" style="color: #3399CC;">成为儿童之路会员，有机会参加更多活动！ </a></div>
		</td>
		<td class="bottom_right" width="50%"></td>
	</tr>
</table>
<div class="scrollable-trigger"></div>

<!--[if IE 6]>
<script type="text/javascript" src="<?=base_url('./common/js/fixpng-min.js')?>"></script>
<script type="text/javascript">
DD_belatedPNG.fix('.png, .browse');
</script>
<![endif]-->

<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-26645818-3']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
</body>
</html>