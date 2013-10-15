<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/single.css"/>
	<div class="back_layer">
		<div class="box"><a href="<?=site_url('single')?>" class="back">返回购买首页</a></div>
	</div>
	<div class="iland"></div>
	<div class="single_top check">
		<div class="single_cover">
			<img src="<?=get_thumb($suit['cover'])?>"/>
		</div>
		<div class="single_intro">
			<h2><?=$suit['title']?></h2>
			<div class="line">出版社：<?=$suit['press']?></div>
			<div class="line">语种：<?=$suit['language']?></div>
			<div class="line">开本：<?=$suit['format']?></div>
			<div class="orig_price">市场价：￥<b><?=$suit['orig_price']?></b></div>
			<div class="nprice">￥<?=$suit['price']?><b>节省￥<?=(int)($suit['orig_price']-$suit['price'])?> (<?=$suit['discount']?>折) 免费送货</b></div>
		</div>
		<div class="clear"></div>
		<h3>收货地址</h3>
		<form action="<?=site_url('single/checkout?id='.$suit_id)?>" method="post">
		<?php if($address):?>
			<table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_addlist">
				<tr class="ttitle">
					<td width="30"></td>
					<td width="90">收货人</td>
					<td width="170">所在地区</td>
					<td>街道地址</td>
					<td width="100">电话手机</td>
					<td width="120" align="center">操作</td>
				</tr>
			<?php foreach($address as $v):?>
				<tr>
					<td align="center"><input type="radio" name="addid" <?=$v['state'] == 1 ? 'checked' :''?> value='<?=$v['id']?>'/></td>
					<td><?=$v['receiver']?></td>
					<td><?=$v['area']?></td>
					<td><?=$v['address']?></td>
					<td><?=$v['tel']?></td>
					<td align="center">
						<?php if($v['state'] == 1):?>
						<span class="default">默认地址</span>
						<?php else:?>
						<!--a href="<?=site_url('single/set_default')?>">设为默认</a> <a href="<?=site_url('single/del_addr')?>">删除</a-->
						<?php endif;?>
					</td>
				</tr>
			<?php endforeach;?>
			</table>
		<?php endif;?>
		<?php if($suit['total'] > 0):?>
			<table cellspacing="5" cellpadding="0" border="0" class="tb_address" width="100%">
				<tr>
					<th><input type="radio" name="addid" value="-1" <?php if(!$address): echo 'style="display:none" checked'; endif;?>/>收货人姓名：</th>
					<td><input type="text" name="receiver" class="input3"/></td>
				</tr>
				<tr>
					<th>所在地区：</th>
					<td>
						<select name="pro" id="province">
							<option value="0">请选择省</option>
						<?php foreach($province as $v):?>
							<option value="<?=$v['provinceid']?>"><?=$v['province']?></option>
						<?php endforeach?>
						</select>
						<select name="cit" id="city">
							<option value="0">请选择市</option>
						</select>
						<select name="are" id="area">
							<option value="0">请选择区/县</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>街道地址：</th>
					<td><textarea name="address"></textarea></td>
				</tr>
				<!--tr>
					<th>邮政编码：</th>
					<td><input type="text" name="postcode" class="input3"/></td>
				</tr-->
				<tr>
					<th>手机号码：</th>
					<td><input type="text" name="tel" class="input3"/></td>
				</tr>
			</table>

			<div class="right">
				<div class="bugnum">
					<span>&nbsp;件（库存<b><?=$suit['total']?></b>）</span>
					<span class="bo"><a href="javascript:void(0)" id="down">-</a><div class="snum">1</div><a href="javascript:void(0)" id="up">+</a></span>
					<span>购买数量：</span>
				</div>
				<div class="clear"></div>
				<div class="price">
					总价（含运费）<br>
					<span>￥<?=$suit['price']?></span><br>
				</div>
				<input type="hidden" name="province" id="sprovince" value=""/>
				<input type="hidden" name="city" id="scity" value=""/>
				<input type="hidden" name="area" id="sarea" value=""/>
				<input type="hidden" name="amount" id="snum" value="1"/>
				<input type="hidden" name="suit_id" value="<?=$suit_id?>"/>
				<input type="submit" class="gopay" value=" "/>
			</div>
		<?php else:?>
			<h1 style="color: #F00">对不起，已经没库存了</h1>
		<?php endif;?>
		</form>
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
	var snum = resnum = <?=$suit['total']?>;
	var sprice = <?=$suit['price']?>;

	$(function() {
		$('#province').change(function() {
			$('#sprovince').val($(this).find('option:selected').text());
			$.get("<?=site_url('single/getcity?id=')?>"+$(this).val(), '', function(data) {
				$('#city').html(data);
			})
		})
		$('#city').change(function() {
			$('#scity').val($(this).find('option:selected').text());
			$.get("<?=site_url('single/getarea?id=')?>"+$(this).val(), '', function(data) {
				$('#area').html(data);
			})
		})

		$('#area').change(function() {
			$('#sarea').val($(this).find('option:selected').text());
		})

		$('#up').click(function() {
			var val = parseInt($('.snum').text());
			val = val < resnum ? val+1 : resnum;
			$('.snum').text(val);
			$('#snum').val(val);
			var sval = sprice*val;
			//if(sval > 1) sval+'.00';
			$('.price span').text('￥'+sval+'.00');
			$('#price').val(sprice*val);
			return false;
		})

		$('#down').click(function() {
			var val = parseInt($('.snum').text());
			val = val <=1 ? 1 : val-1;
			$('.snum').text(val);
			$('#snum').val(val);
			var sval = sprice*val;
			//if(sval > 1) sval+'.00';
			$('.price span').text('￥'+sval+'.00');
			$('#price').val(sprice*val);
			return false;
		})
	})

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