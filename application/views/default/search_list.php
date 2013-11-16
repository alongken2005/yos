<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/index.css"/>
<link rel="stylesheet" href="common/powerFloat/powerFloat.css" type="text/css"/>


<div class="box">
	<div class="slider_title">Search Result</div>
	<div class="read_his slider_box">
		<div class="slider_imgs">
			<ul class="sliderul">
			<?php foreach($lists as $v) {?>
				<li>
					<a href="<?=site_url('book/floatinfo?bid='.$v['id'])?>" rel="<?=$v['id']?>"><img src="data/pic.png"/></a>
					<div><?=$v['title']?></div>
				</li>
			<?php }?>
			</ul>
		</div>
		<div class="clear"></div>
		<div style="text-align:center" id="loading">内容加载中...</div>		
	</div>
</div>

<div class="slider_float"></div>

<script type="text/javascript" src="common/powerFloat/jquery-powerFloat.js"></script>
<script type="text/javascript" src="common/js/scrollpagination.js"></script>
<script type="text/javascript">
	$(function() {
		var page = <?=intval($page)?>
		page = page > 0 ? page : 1;

		//浮出书本基本信息
		$('.sliderul li a').powerFloat({
			eventType: "hover",
			targetMode: "ajax",
			target: "<?=site_url('book/floatinfo?bid=1')?>",
			//targetAttr: "href",
			showDelay: 1000,
			offsets: {x: 50, y: -60},
		});

		//滚动分页
		$('.sliderul').scrollPagination({
			'contentPage': '<?=site_url("search/lists?keyword=".$keyword)?>&page='+page, // the url you are fetching the results
			'contentData': {}, // these are the variables you can pass to the request, for example: children().size() to know which page you are
			'scrollTarget': $(window),
			'heightOffset': 10,
			'beforeLoad': function() {
				$('#loading').fadeIn();	
			},
			'afterLoad': function(elementsLoaded) {
				 $('#loading').fadeOut();
				 var i = 0;
				 $(elementsLoaded).fadeInWithDelay();
				 if ($('.sliderul').children().size() > 100) {
				 	$('#nomoreresults').fadeIn();
					$('.sliderul').stopScrollPagination();
				 }
			}
		});		
	})
</script>
<?php $this->load->view(THEME.'/footer');?>