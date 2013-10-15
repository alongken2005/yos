<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/single.css"/>
<div class="box">
	<div class="iland" style="margin-top: 95px;"></div>
	<div class="single_top">
		<div class="single_t1"></div>
		<div class="single_intro">
			<h4 style="font-size: 18px;">《德国少年儿童百科知识全书 什么是什么 •WAS IST WAS 》<span style="color: #DE3168">精选</span></h4>
			浙江儿童阅读推广研究中心科学年推荐阅读书目<br>
			100余位科学家参与创作，图文并茂的科普经典<br>
			德国本土销量超过5000万册，版权遍及全球45个国家和地区，<br>
			CCTV2009年度获奖图书，2009年度最佳少儿百科知识图书奖<br>
			2009年度最值得一读的三十本好书奖  2009年度最有影响的十本好书奖 畅销5000万册<br>
			WAS IST WAS=逻辑思维培养+知识体系构建＝诺贝尔奖的摇篮<br>
		</div>
		<div class="clear"></div>
		<div class="right">
			<div class="price">
				<span>￥200.00</span><br>
				市场价:￥<b>300.00</b>
			</div>
			<a href="<?=site_url('single/check?id=1')?>" target="_blank" class="buy">节省￥100（6.6折）免费送货</a>
		</div>
	</div>
	<div class="single_bottom"></div>

	<div class="single_list png"></div>
	<div class="single_suit">
		<div class="corner"></div>
	<?php $count = count($lists); foreach($lists as $k=>$v) {?>
		<div class="li">
			<img src="<?=get_thumb($v['cover'])?>" class="img"/>
		</div>
		<div class="single_summary">
			<div class="word">
				<h2><?=$v['title']?></h2>
				<div class="author">
					<?php
					if($v['author1']) { echo "作者：".$v['author1'];}
					if($v['author2']) { echo "&nbsp;&nbsp;&nbsp;插图作者：".$v['author2'];}
					if($v['author3']) { echo "&nbsp;&nbsp;&nbsp;译者：".$v['author3'];}
					?>
				</div>
				<div class="intro"><?=$v['intro']?></div>
			</div>
			<div class="pic">
				<img src="<?=get_thumb($v['pic1'])?>"/>
				<img src="<?=get_thumb($v['pic2'])?>"/>
			</div>
		</div>
		<?php if(($k+1)%3 == 0 || ($k+1) == $count) {?>
		<div class="clear"></div>
		<div class="summary"></div>
	<?php }}?>
	</div>
</div>
<script type="text/javascript">

</script>
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

	$(function() {
		$('.single_suit .li').click(function() {
			$('.single_summary').slideUp('fast');
			var obj = $(this).nextAll('.summary').first();
			obj.html($(this).next('.single_summary').clone());
			var li = $(this);
			obj.find('.single_summary').slideDown('fast', function() {
				var offset = li.offset();
				$('.single_suit .corner').offset({top: offset.top+300, left: offset.left+130});
			});
		})
	})

	$(window).load(function() {
		$('.single_suit .li').first().click();
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
<?php $this->load->view(THEME.'/footer');?>