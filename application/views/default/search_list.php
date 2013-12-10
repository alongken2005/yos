<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/book.css"/>
<link rel="stylesheet" href="common/powerFloat/powerFloat.css" type="text/css"/>

<div class="box" style="padding-top:100px;">
	<div class="search_title">Search Result</div>
	<ul class="search_list">
	<?php foreach($lists as $v) {?>
		<li>
			<input type="hidden" value="<?=site_url('book/floatinfo?bid='.$v['id'])?>"/>
			<a href="<?=site_url('book/detail?do=detail&bid='.$v['id'])?>" rel="<?=$v['id']?>" class="bookiframe"><img src="data/books/<?=$v['cover']?>"/></a>
		</li>
	<?php }?>
	</ul>
	<div class="clear"></div>
	<div style="text-align:center;" id="list_loading">loading...</div>
	<div style="text-align:center;" id="list_nomore">Sorry, no more results</div>	
</div>

<script type="text/javascript" src="common/powerFloat/jquery-powerFloat.js"></script>
<script type="text/javascript" src="common/js/scrollpagination.js"></script>
<script type="text/javascript" src="common/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="common/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
	$(function() {
		var page = <?=intval($page)?>;
		var fancyboxOn = 2;
		var scrollOption = {
			'contentPage': '<?=site_url("search/lists?more=1&keyword=".$keyword)?>&page='+page, // the url you are fetching the results
			'contentData': {}, // these are the variables you can pass to the request, for example: children().size() to know which page you are
			'scrollTarget': $(window),
			'heightOffset': 10,
			'beforeLoad': function() {
				$('#list_loading').fadeIn();
			},
			'afterLoad': function(elementsLoaded) {
				page++;
				$('#list_loading').fadeOut();
				//var i = 0;
				$(elementsLoaded).fadeInWithDelay();
				if ($('.search_list').children().size() > 100) {
					$('#list_nomore').fadeIn();
					$('.search_list').stopScrollPagination();
				}
			}
		};

		//浮出书本基本信息
		$('.search_list li a').powerFloat({
			eventType: "hover",
			targetMode: "ajax",
			target: function() {
				return $(this).prev("input").val();
			},
			showDelay: 1000,
			offsets: {x: 50, y: -60},
		});

		//滚动分页
		$('.search_list').scrollPagination(scrollOption);

		$.fn.fadeInWithDelay = function(){
			var delay = 0;
			return this.each(function(){
				$(this).delay(delay).animate({opacity:1}, 200);
				delay += 100;
			});
		};	

		$(".bookiframe").fancybox({
			'width'				: 900,
			'height'			: '98%',
			'padding'			: 2,
			'type'				: 'iframe',
			'centerOnScroll'	: true,
			'overlayOpacity'	: 0,
			'showNavArrows'		: false,
			'onComplete'		: function() { 
				$('.search_list').stopScrollPagination(); 
			},
			'onClosed'			: function() { 
				$('.search_list').scrollPagination(scrollOption); 
			}
		});		
	})
</script>
<?php $this->load->view(THEME.'/footer');?>