<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/book.css"/>
<div class="box book_detail">
	<img src="data/books/<?=$book['cover']?>" height="200" class="cover">
	<ul class="base">
		<li class="title"><?=$book['title']?></li>
		<li class="author">Author: <a href=""><?=$book['author']?></a></li>
		<li class="star">
			<div id="star_<?=$book['score']?>"></div>
			<span>(<?=$book['scorenum']?>)</span>
		</li>
		<li><a href="" class="btn1">Read</a></li>
	</ul>

	<div class="clear10"></div>

	<div class="tab">
		<a href="<?=site_url('book/detail?do=detail&bid='.$bid)?>" <?=$do=='detail' ? 'class="active"' : ''?>>Details</a>
		<a href="<?=site_url('book/detail?do=reviews&bid='.$bid)?>" <?=$do=='reviews' ? 'class="active"' : ''?>>Reviews</a>
		<a href="<?=site_url('book/detail?do=related&bid='.$bid)?>" <?=$do=='related' ? 'class="active"' : ''?>>Related</a>
	</div>

	<?php if($do == 'detail') { ?>
	<div class="book_info">
		<h3>Information</h3>
		<table cellpadding="0" cellspacing="5" width="100%" class="detail_table">
			<tr>
				<th width="100" align="right">Publisher</th>
				<td><?=$book['author']?></td>
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
				<td>Text, Audio</td>
			</tr>			
			<tr>
				<th align="right">File Size</th>
				<td>108 MB</td>
			</tr>
		</table>

		<h3>In-Book Purchases</h3>
		<table cellpadding="0" cellspacing="5" width="100%" class="detail_table">
			<tr>
				<th width="60">1</th>
				<td>Paid Text ‘000 Words</td>
				<td width="80">$0.10</td>
			</tr>
		</table>

		<h3>Update Histories</h3>
		<h3>Author & Publisher Info</h3>
		<h3>Author & Publisher Website</h3>
		<h3>Reader Agreement</h3>
	</div>
	<?php } else if($do == 'reviews') { ?>
	<div class="book_info">
		<h3>Facebook</h3>
		<h3>Ratings</h3>
		<div class="star">
			<div id="star_<?=$book['score']?>"></div>
			<span>&nbsp;&nbsp;<?=$book['scorenum']?> Ratings</span>
		</div>

		<div class="star_list">
			<div class="star">
				<div id="star_5"></div>
				<span><div class="percentage" style="width:<?=$score5?>%"></div></span>
			</div>				
			<div class="star">
				<div id="star_4"></div>
				<span><div class="percentage" style="width:<?=$score4?>%"></div></span>
			</div>
			<div class="star">
				<div id="star_3"></div>
				<span><div class="percentage" style="width:<?=$score3?>%"></div></span>
			</div>	
			<div class="star">
				<div id="star_2"></div>
				<span><div class="percentage" style="width:<?=$score2?>%"></div></span>
			</div>	
			<div class="star">
				<div id="star_1"></div>
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

			<a href="">Write a Review</a>
		</h3>
	</div>

	<?php } else if($do == 'related') { ?>
	<?php } ?>	
</div>

<script src="common/raty/jquery.raty.js" type="text/javascript"></script>
<script type="text/javascript">
	$('#star').raty({
		score: <?=$book['score']?>,
		click: function (score, evt) {
			$.post("<?=site_url('book/score')?>", {bid:"<?=$book['id']?>", score:score}, function(data) {
				alert(data);
				if(data == "nologin") {
					//alert("Please login first!");
					return false;
				} else if(data == "error") {
					//alert("Done error");
					return false;
				} else {
					alert("评分成功！本书平均得分 "+data);
					return false;
				}
				return false;
			});
		}
	});
</script>