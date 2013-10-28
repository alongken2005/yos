<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/index.css"/>
<link rel="stylesheet" href="common/powerFloat/powerFloat.css" type="text/css"/>


<div class="box">
	<div class="slider_title">Continue Reading for Jerry</div>
	<div class="read_his slider_box">
		<a href="" class="pre"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php for($i = 1; $i<10; $i++) {?>
				<li>
					<img src="data/pic.png"/>
					<div><?="好吧".$i?></div>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="next"></a>
	</div>

	<div class="slider_title">My List</div>
	<div class="my_list slider_box">
		<a href="" class="pre"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php for($i = 1; $i<10; $i++) {?>
				<li>
					<img src="data/pic.png"/>
					<div><?="好吧".$i?></div>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="next"></a>
	</div>

	<div class="slider_title">Top Picks for Jerry</div>
	<div class="top_picks slider_box">
		<a href="" class="pre"></a>
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php for($i = 1; $i<10; $i++) {?>
				<li>
					<img src="data/pic.png"/>
					<div><?="好吧".$i?></div>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="next"></a>
	</div>
</div>

<div class="slider_float">
	<h3>fdfdssd</h3>
	<div class="author">author:张浩</div>
	<div>Short description of the book. Short description of the book. Short description of the book. Short description of the book Short description of the book. Short description of the book.</div>
	<div></div>
</div>

<script type="text/javascript" src="common/powerFloat/jquery-powerFloat-min.js"></script>
<script type="text/javascript">
	$(function() {
	
		$('.sliderul li img').powerFloat({
			target: $(".slider_float"),
			showDelay: 1000,
			offsets: {x: 50, y: -60},
		});

		sliderMove('.read_his');
		sliderMove('.my_list');
		sliderMove('.top_picks');

	})

	function sliderMove(selected) {
		var ulwidth = $(selected+' .sliderul li').length*130;
		$(selected+' .sliderul').width(ulwidth);

		$(selected).hover(function() {
			$(selected+' .pre, '+selected+' .next').show();
		}, function() {
			$(selected+' .pre, '+selected+' .next').hide();
		});

		$(selected+' .pre').hover(function() {
			$(selected+' .sliderul').animate( { left: "0px"}, { queue: false, duration: 'slow' } );
			return false;
		}, function() {
			$(selected+' .sliderul').stop();
		})

		$(selected+' .next').hover(function() {
			var leftvar = 1000-ulwidth;
			if(leftvar < 0) {
				$(selected+' .sliderul').animate( { left: leftvar+"px"}, { queue: false, duration: 'slow' } );
			}
			return false;
		}, function() {
			$(selected+' .sliderul').stop();
		})
	}
</script>
<?php $this->load->view(THEME.'/footer');?>