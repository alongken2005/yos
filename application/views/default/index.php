<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/index.css"/>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/book.css"/>

<div class="box" style="padding-top:100px;">
	<?php if(isset($this->member) && $this->member) { ?>
	<?php if(isset($cread) && $cread) { ?>
	<div class="slider_title" id="cread" style="margin-top: 0px;"><a href="<?=site_url('search/clists?type=cread')?>">Continue Reading for You</a></div>
	<div class="read_his slider_box">
		<a href="" class="pre"></a>
		<a href="" class="preicon"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach ($cread as $v) {?>
				<li>
					<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
					<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" class="bookiframe"><img src="<?=getCover($v)?>"/></a>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="nexticon"></a>
		<a href="" class="next"></a>
	</div>
	<?php } ?>
	<?php if(isset($mylist) && $mylist) { ?>
	<div class="slider_title" id="mylist"><a href="<?=site_url('search/clists?type=mylist')?>">My List</a></div>
	<div class="my_list slider_box">
		<a href="" class="pre"></a>
		<a href="" class="preicon"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach ($mylist as $v) { ?>
				<li>
					<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
					<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" class="bookiframe"><img src="<?=getCover($v)?>"/></a>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="nexticon"></a>
		<a href="" class="next"></a>
	</div>
	<?php } ?>
	<div class="slider_title" id="tops"><a href="<?=site_url('search/clists?type=tops')?>">Top Picks for You</a></div>
	<div class="top_picks slider_box">
		<a href="" class="pre"></a>
		<a href="" class="preicon"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach ($tops as $v) {?>
				<li>
					<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
					<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" class="bookiframe"><img src="<?=getCover($v)?>"/></a>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="nexticon"></a>
		<a href="" class="next"></a>
	</div>
	<?php } ?>


	<div class="slider_title" id="popular" <?php if(!(isset($this->member) && $this->member)) { ?>style="margin-top: 0px;"<?php } ?>><a href="<?=site_url('search/clists?type=popular')?>">Popular on YouShelf</a></div>
	<div class="popular slider_box">
		<a href="" class="pre"></a>
		<a href="" class="preicon"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach ($popular as $v) {?>
				<li>
					<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
					<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" class="bookiframe"><img src="<?=getCover($v)?>"/></a>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="nexticon"></a>
		<a href="" class="next"></a>
	</div>
	
	<div class="slider_title" id="rated"><a href="<?=site_url('search/clists?type=bestRated')?>">Best Rated</a></div>
	<div class="rated slider_box">
		<a href="" class="pre"></a>
		<a href="" class="preicon"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach ($bestRated as $v) {?>
				<li>
					<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
					<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" class="bookiframe"><img src="<?=getCover($v)?>"/></a>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="nexticon"></a>
		<a href="" class="next"></a>
	</div>	
<?php foreach($lists as $k=>$value) {?>
	<div class="slider_title"><a href="<?=site_url('search/clists?genre='.$k)?>"><?=$value['name']?></a></div>
	<div class="genre<?=$k?> slider_box">
		<a href="" class="pre"></a>
		<a href="" class="preicon"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach ($value['list'] as $v) {?>
				<li>
					<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
					<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" class="bookiframe"><img src="<?=getCover($v)?>"/></a>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="nexticon"></a>
		<a href="" class="next"></a>
	</div>	
<?php } ?>	
</div>

<div class="slider_float"></div>

<script type="text/javascript" src="common/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="common/fancybox/jquery.fancybox-1.3.4.css"/>
<script type="text/javascript">
	$(function() {
		
		$('.pre, .preicon, .nexticon, .next').click(function() {
			return false;
		})
		$('.sliderul li a').powerFloat({
			eventType: "hover",
			targetMode: "ajax",
			target: function() {
				return $(this).prev("input").val();
			},
			showDelay: 1000,
			offsets: {x: 50, y: -60},
		});			

		sliderMove('.read_his');
		sliderMove('.my_list');
		sliderMove('.top_picks');
		sliderMove('.popular');
		sliderMove('.rated');
		<?php foreach($lists as $k=>$value) {?>
			sliderMove('.genre<?=$k?>');
		<?php } ?>

		$(".bookiframe").fancybox({
			'width'				: 885,
			'height'			: '98%',
			'padding'			: 2,
			'type'				: 'iframe',
			'centerOnScroll'	: true,
			'overlayOpacity'	: 0
		});

	})

	function sliderMove(selected) {
		var boxwidth = $('.slider_imgs').width();
		var ulwidth = $(selected+' .sliderul li').length*203;
		$(selected+' .sliderul').width(ulwidth);

		$(selected).hover(function() {
			$(selected+' .pre, '+selected+' .next, '+selected+' .preicon, '+selected+' .nexticon').show();
		}, function() {
			$(selected+' .pre, '+selected+' .next, '+selected+' .preicon, '+selected+' .nexticon').hide();
		});

		var timer;
		$(selected+' .pre, '+selected+' .preicon').hover(function() {
			timer = setInterval(function(){
				var obj = $(selected+' .sliderul');
				var i = obj.offset().left;
				if(i<=0) {
					obj.offset({left:i+30})
				}
			},30);
			return false;
		}, function() {
			clearInterval(timer);
		})

		$(selected+' .next, '+selected+' .nexticon').hover(function() {
			var leftvar = boxwidth - ulwidth+45;
			timer = setInterval(function(){
				var obj = $(selected+' .sliderul');
				var i = obj.offset().left;
				if(i>=leftvar) {
					obj.offset({left:i-30})
				}
			},30);			
			// if(leftvar < 0) {
			// 	$(selected+' .sliderul').animate( { left: leftvar+"px"}, { queue: false, duration: 'slow' } );
			// }
			return false;
		}, function() {
			clearInterval(timer);
			//$(selected+' .sliderul').stop();
		})
	}
</script>