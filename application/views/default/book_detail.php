<!DOCTYPE html>
<html style="background:#F2F2F2">
<head>
	<meta charset="utf-8">
	<meta name="title" content="<?=$book['title']?>" />
	<meta name="description" content="<?=cutstr($book['description'], 500)?>" />
	<title>YouShelf</title>
	<base id="headbase" href="<?=base_url()?>">
	<script type="text/javascript" src="common/js/jquery-1.4.2.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/book.css"/>
	<link rel="image_src" href="<?=getCover($book)?>" />

</head>
<body>

<div class="book_detail">
	<img src="<?=getCover($book)?>" height="200" class="cover">
	<ul class="base">
		<li class="homebtn">
			<a href="<?=site_url()?>" class="btn1">YouShelf</a>
		</li>
		<li class="bookShare">
			<div >
				<a class="fbook" target="_blank" href="https://www.facebook.com/sharer.php?src=bm&u=<?=urlencode(site_url('book/detail?do=detail&bid='.$bid))?>"></a>
				<a class="twitter" target="_blank" href="http://twitter.com/home?status=<?=urlencode(cutstr($book['description'], 105)."  ".site_url('book/detail?do=detail&bid='.$bid))?>"></a>
			</div>
			<input type="text" class="shareUrl input5" value="<?=site_url('book/detail?do=detail&bid='.$bid)?>"/>
		</li>
		<li class="title"><?=$book['title']?></li>
		<li class="author">Author: <a href=""><?=$book['author']?></a></li>
		<li class="star">
			<div class="star_<?=$book['score']?>"></div>
			<span>(<?=$book['scorenum']?>)</span>
		</li>
		<li class="fav">
			<a href="<?=site_url('book/addFav?bid='.$book['id'])?>" class="addfav <?=$addFav?>">Add to My List</a>
			<a href="<?=site_url('book/delFav?bid='.$book['id'])?>" class="delfav <?=$removeFav?>">Remove from My List</a>
		</li>
		<li><a href="<?=site_url('book/reading?bid='.$bid)?>" class="btn1" target="_parent">Read</a></li>
	</ul>

	<div class="clear10"></div>

	<div class="tab">
		<a href="<?=site_url('book/detail?do=detail&bid='.$bid)?>" <?=$do=='detail' ? 'class="active"' : ''?> style="margin-left:330px;">Details</a>
		<a href="<?=site_url('book/detail?do=reviews&bid='.$bid)?>" <?=$do=='reviews' ? 'class="active"' : ''?>>Reviews</a>
		<!--a href="<?=site_url('book/detail?do=related&bid='.$bid)?>" <?=$do=='related' ? 'class="active"' : ''?>>Related</a-->
	</div>

	<?php if($do == 'detail') { ?>
	<div class="book_info">
		<h3>Description</h3>
		<div class="description">
			<?php if(isset($more) && $more === true) { ?>
			<span class="desall" style="display:none"><?=$book['description']?></span>
			<span class="des500"><?=cutstr($book['description'], 500)?></span>
			<a href="" class="moreDes">&nbsp;&nbsp;>> more</a>
			<?php } else { ?>
			<span class="desall"><?=$book['description']?></span>
			<?php } ?>
		</div>

		<h3>Information</h3>
		<table cellpadding="0" cellspacing="5" width="100%" class="detail_table">
			<tr>
				<th width="100" align="right">Publisher</th>
				<td><?=$book['publisher']?></td>
			</tr>			
			<tr>
				<th align="right">Genre</th>
				<td><?=$genre['name']?></td>
			</tr>			
			<tr>
				<th align="right">Updated</th>
				<td><?=date('Y-m-d', $book['mtime'])?></td>
			</tr>
			<tr>
				<th align="right">Format</th>
				<td>Text</td>
			</tr>			
			<!--tr>
				<th align="right">File Size</th>
				<td>108 MB</td>
			</tr-->
		</table>

		<?php if($book['text_price'] > 0 || $book['audio_price'] > 0) { ?>
		<h3>In-Book Purchases</h3>
		<table cellpadding="0" cellspacing="5" width="100%" class="detail_table">
			<?php if($book['text_price'] > 0) { ?>
			<tr>
				<td>1. Paid Text 1000 Words</td>
				<td width="80">$<?=$book['text_price']?></td>
			</tr>
			<?php } if($book['audio_price'] > 0) { ?>
			<tr>
				<td>2. Paid Audio 1000 Words</td>
				<td width="80">$<?=$book['audio_price']?></td>
			</tr>
			<?php } ?>			
		</table>
		<?php } ?>
		<!--h3>Update Histories</h3>
		<h3>Author & Publisher Info</h3>
		<h3>Author & Publisher Website</h3>
		<h3>Reader Agreement</h3-->
	</div>
	<?php } else if($do == 'reviews') { ?>
	<div class="book_info">
		<!--h3>Facebook</h3-->
		<h3>Ratings</h3>
		<div class="star">
			<div class="star_<?=$book['score']?>"></div>
			<span>&nbsp;&nbsp;<?=$book['scorenum']?> Ratings</span>
		</div>

		<div class="star_list">
			<div class="star">
				<div class="star_5"></div>
				<span><div class="percentage" style="width:<?=$score5?>%"></div></span>
			</div>				
			<div class="star">
				<div class="star_4"></div>
				<span><div class="percentage" style="width:<?=$score4?>%"></div></span>
			</div>
			<div class="star">
				<div class="star_3"></div>
				<span><div class="percentage" style="width:<?=$score3?>%"></div></span>
			</div>	
			<div class="star">
				<div class="star_2"></div>
				<span><div class="percentage" style="width:<?=$score2?>%"></div></span>
			</div>	
			<div class="star">
				<div class="star_1"></div>
				<span><div class="percentage" style="width:<?=$score1?>%"></div></span>
			</div>											
		</div>

		<div class="writeReview">
			<div>Rate This Book:&nbsp;&nbsp;</div>
			<span id="star"></span>
		</div>

		<div class="clear"></div>
		<h3>
			Reviews
			<select name="reviewType">
				<option value="helpful">Most Helpful</option>	
				<option value="recent">Most Recent</option>	
				<option value="favorable">Most Favorable</option>	
				<option value="critical">Most Critical</option>	
			</select>

			<a href="javascript:void(0)" id="writeReview">Write a Review</a>
		</h3>

		<form method="post" action="<?=site_url('book/writeReview')?>" id="writeForm" style="display:none">
			<table cellspacing="5" cellpadding="0" class="reviewTable">
				<tr>
					<th>Title:</th>
					<td><input type="text" class="input5" name="title"/></td>
				</tr>
				<tr>
					<th>Rate:</th>
					<td id="ratebook"></td>
				</tr>
				<tr>
					<th>Content:</th>
					<td>
						<textarea name="content" class="textarea1"></textarea>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="hidden" name="bid" value="<?=$bid?>"/>
						<input type="hidden" name="score" id="reviewScore"/>
						<input type="submit" value="Submit" class="btn1" id="postReview"/>
						<input type="button" value="Cancel" class="btn1" id="cancelReview"/>
					</td>
				</tr>						
			</table>
		</form>

		<ul class="reviewList">
		<?php foreach($reviewsList as $v) { ?>
			<li>
				<h4><?=$v['title']?></h4><a href="<?=site_url('book/helpful?bid='.$bid.'&rid='.$v['id'])?>" class="zan <?=$v['hnum'] > 0 ? 'helpful' : 'unhelpful'?>"><?=$v['hnum']?></a> 
				<div class="clear"></div>
				<div class="star">
					<div class="star_<?=$v['score']?>"></div>
					<span>&nbsp;&nbsp;by <?=$v['username']?></span>
				</div>
				<div class="reviewContent"><?=$v['content']?></div>	
			</li>
		<?php } ?>
		</ul>

		<?=$pagination?>
	</div>

	<?php } else if($do == 'related') { ?>
	<?php } ?>	
</div>
</body>
</html>
<script src="common/raty/jquery.raty.js" type="text/javascript"></script>
<script type="text/javascript">

	var btnVal = '>> pack up';
	var btnValm = '';
	var desall = $('.desall').text();
	var desallm = '';

	$('.moreDes').click(function() {
		
		btnValm = $(this).text();
		$(this).text(btnVal);
		btnVal = btnValm;

		desallm = $('.des500').text();
		$('.des500').text(desall);
		desall = desallm;

		return false;
	})

	$('#star').raty({
		score: <?=$book['score']?>,
		click: function (score, evt) {
			$.post("<?=site_url('book/score')?>", {bid:"<?=$book['id']?>", score:score}, function(data) {
				if(data == "nologin") {
					alert("Please login first!");
					return false;
				} else if(data == "error") {
					alert("Done error");
					return false;
				} else {
					alert("评分成功！本书平均得分 "+data);
					return false;
				}
				return false;
			});
		}
	});

	$('#ratebook').raty({
		click: function (score, evt) {
			$('#reviewScore').val(score);
		}
	});	

	$('.zan').click(function() {
		$.post($(this).attr('href'), '', function(data) {
			alert('fdfd');
			return false;
		});
		return false;
	});

	$('#writeReview').click(function() {
		$('#writeForm').show('fast');
	})

	$('#cancelReview').click(function() {
		$('#writeForm').hide('fast');
	})

	$('#postReview').click(function() {
		$.post($('#writeForm').attr('action'), $('#writeForm').serialize(), function(data) {
			if(data.state != 1) {
				alert(data.msg);
			} else {
				alert('Success!');
				window.location.reload();
			}
			return false;
		}, 'json');
		return false;
	})


	$('.addfav').click(function() {
		$.post($('.addfav').attr('href'), '', function(data) {
			if(data.state == 1) {
				$('.addfav').hide();
				$('.delfav').show();
			}
			alert(data.msg);
			return false;
		}, 'json');
		return false;
	})

	$('.delfav').click(function() {
		$.post($('.delfav').attr('href'), '', function(data) {
			if(data.state == 1) {
				$('.delfav').hide();
				$('.addfav').show();
			}			
			alert(data.msg);
			return false;
		}, 'json');
		return false;
	})	

	$('.shareUrl').select();

</script>
<!--script>
	window._bd_share_config = {
		common : {
			bdText : "<?=cutstr($book['description'], 140)?>",	
			//bdUrl : "<?=site_url('book/detail?do=detail&bid='.$bid)?>", 	
			bdPic : "<?=getCover($book)?>",
			onBeforeClick : function(cmd, config){
				if(cmd == 'twi') {
					return {bdText: "<?=cutstr($book['description'], 120)?>"};
				}
			},
		},
		share : [{
			"bdSize" : 24
		}]
	}
	with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script-->