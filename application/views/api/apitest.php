<html style="background: url(<?=THEME_VIEW?>/images/bg.jpg);">
<head>
	<meta charset="utf-8">
	<title>apitest</title>
</head>
<body>
	<?php 
	$timestamp = time();
	function gsign($action, $timestamp) {
		$signPostfix = config_item('signPostfix');
		return strtoupper(md5($action.$timestamp.$signPostfix));
	}
	?>
	<form action="<?=site_url('api/book/getDirectory')?>" method="get" id="book_getDirectory">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书本目录列表</b></td>
			</tr>			
			<tr>
				<th align="right">bookId:</th>
				<td><input type="text" name="bookId" value="7" /></td>
			</tr>			
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getDirectory', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>

	<form action="<?=site_url('api/book/getChapter')?>" method="get" id="book_getChapter">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取章节内容</b></td>
			</tr>			
			<tr>
				<th align="right">bookId:</th>
				<td><input type="text" name="bookId" value="7" /></td>
			</tr>
			<tr>
				<th align="right">chapterId:</th>
				<td><input type="text" name="chapterId" value="4" /></td>
			</tr>					
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getChapter', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>

	<form action="<?=site_url('api/book/getGenre')?>" method="get" id="book_getGenre">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书本分类</b></td>
			</tr>								
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getGenre', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/getBooks')?>" method="get" id="book_getBooks">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书本列表</b></td>
			</tr>	
			<tr>
				<th align="right">分类id:</th>
				<td><input type="text" name="genreId" value="2" /></td>
			</tr>				
			<tr>
				<th align="right">个性话题:</th>
				<td><input type="text" name="type" value="" /></td>
			</tr>				
			<tr>
				<th align="right">pageSize:</th>
				<td><input type="text" name="pageSize" value="20" /></td>
			</tr>
			<tr>
				<th align="right">page:</th>
				<td><input type="text" name="page" value="1" /></td>
			</tr>										
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getBooks', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/getBookInfo')?>" method="get" id="book_getBookInfo">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书本信息</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="2" /></td>
			</tr>									
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getBookInfo', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/inputPages')?>" method="post" id="book_inputPages">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>接收段落的单页内容</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="9" /></td>
			</tr>
			<tr>
				<th align="right">章节id:</th>
				<td><input type="text" name="chapterId" value="5" /></td>
			</tr>			
			<tr>
				<th align="right">单页json:</th>
				<td><textarea name="pages" style="width:400px; height:150px"><?=json_encode(array("发撒旦飞撒发生发生发生发生发生发撒旦", "Some popular black-and-white media of the past include:
Movies and animated cartoons. While some color film processes (including hand coloring) were experimented with and in limited use from the earliest days of the motion picture, the switch from most films being in black-and-white to most being in color was gradual, taking place from the 1930s to the 1960s. Even when most studios had the capability to make color films they were not heavily utilized as tinting techniques and the Technicolor process were expensive and difficult. For years color films were not capable of rendering realistic hues, thus mostly historical films or musicals were made in color and many directors preferred to use black-and-white stock. For the years 1940–1966 a separate Academy Award for Best Art Direction was given for black-and-white movies along with one for color.
Photography was black-and-white or shades of sepia. Color photography was originally rare and expensive and again often less than true to life. Color photography became more common in the middle of the 20th century and has become even more common since. Nowadays black-and-white has turned into a niche market for photographers who use the medium for artistic purposes. This can take the form of black-and-white film or digital conversion to grayscale, with optional digital image editing manipulation to enhance the results. For amateur use certain companies such as Kodak manufactured black-and-white disposable cameras until 2009. Also, certain films are produced today which give black-and-white images using the ubiquitous C41 color process.
Television programming was first broadcast in black-and-white. Some color broadcasts in the USA began in the 1950s, with color becoming common in western industrialized nations during the late 1960s and then standard in the 1970s. In the United States, the Federal Communications Commission (FCC) settled on a color NTSC standard in 1953, and the NBC network began broadcasting a limited color television schedule in January 1954. Color television became more widespread in the U.S. between 1963 and 1967, when the CBS and ABC networks joined NBC in broadcasting full color schedules. Canada began airing color television in 1966 while the United Kingdom established an entirely different color system in July 1967 known as PAL. New Zealand began color broadcasting in 1973, and Australia kept airing black-and-white broadcasts until 1975. In 1969 Japanese electronics manufacturers standardized the first format for industrial/non-broadcast videotape recorders (VTRs) called EIAJ-1, which initially offered only black and white video recording and playback. While no longer used professionally, many consumer camcorders have the ability to record in black-and-white.
Most newspapers were black-and-white until the late 1970s; The New York Times and The Washington Post remained in black-and-white until the 1990s. Some claim that USA Today was the major impetus for the change to color. In the UK, color was only slowly introduced from the mid-1980s. Even today, many newspapers restrict color photographs to the front and other prominent pages since mass-producing photographs in black-and-white is considerably less expensive than color. Similarly, daily comic strips in newspapers were traditionally black-and-white with color reserved for Sunday strips.
Color printing has traditionally been more expensive. Sometimes color is reserved for the cover. Magazines such as Jet magazine were either all or mostly black-and-white until the end of the 20th century when it became all-color. Manga (Japanese or Japanese-influenced comics) are typically published in black-and-white although now it is part of its image. Many School yearbooks are still entirely or mostly in black-and-white.
Since the advent of color, black-and-white mass media often connotes something nostalgic, historic or anachronistic. For example, the 1998 Woody Allen film Celebrity was shot entirely in black-and-white and Allen has often made use of the practice since Manhattan in 1979. Other films, such as The Wizard of Oz (1939), American History X, Pleasantville and The Phantom of the Opera (2004), play with the concept of the black-and-white anachronism, using it to selectively portray scenes and characters who are either more or less outdated or duller than the characters and scenes shot in full-color. This manipulation of color appears in the film Sin City and the occasional television commercial. Wim Wenders' 1987 film Wings of Desire uses sepia-tone black-and-white for the scenes shot from the angels' perspective. When Damiel, the angel (the film's main character), becomes a human the film changes to color, emphasising his new real life view of the world.
Since the late 1960s, few mainstream films have been shot entirely in black-and-white. The reasons are frequently commercial, as it is difficult to sell a film for television broadcasting if the film is not in color. Monochrome film stock is rarely used at the time of shooting, even if the films are intended to be presented theatrically in black-and-white. Movies such as John Boorman's The General and Joel Coen's The Man Who Wasn't There were obliged to be filmed in color by their respective producers despite being presented in black-and-white for artistic reasons. Clerks is one of the few well-known recent films shot in black-and-white for no artistic purpose; because of the extremely low out-of-pocket budget the production team could not afford the added costs of shooting in color (though the difference in film stock price would be slight, the store's fluorescent lights could not be used to light for color; by shooting in black and white, the film-makers did not have to rent lighting equipment.)
Some modern film directors will occasionally shoot movies in black and white as an artistic choice, though it is much less common for a major Hollywood production. This is also true of black-and-white photography, where many photographers choose to shoot in solely black and white since the stark contrasts enhance the subject matter. For example, the movie π is filmed in entirely black and white, with a grainy effect until the end.
Some formal photo portraits still use black-and-white. Many visual-art photographers use black-and-white in their work."))?></textarea></td>
			</tr>															
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('inputPages', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/writeRviews')?>" method="post" id="book_writeRviews">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>发表评论</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="2" /></td>
			</tr>
			<tr>
				<th align="right">分数:</th>
				<td><input type="text" name="score" value="2" /></td>
			</tr>		
			<tr>
				<th align="right">标题:</th>
				<td><input type="text" name="title" value="" /></td>
			</tr>	
			<tr>
				<th align="right">评论内容:</th>
				<td><input type="text" name="content" value="" /></td>
			</tr>															
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('writeRviews', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/getReviews')?>" method="get" id="book_getReviews">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书本评论</b></td>
			</tr>	
			<tr>
				<th align="right">bookId:</th>
				<td><input type="text" name="bookId" value="11" /></td>
			</tr>								
			<tr>
				<th align="right">pageSize:</th>
				<td><input type="text" name="pageSize" value="20" /></td>
			</tr>
			<tr>
				<th align="right">page:</th>
				<td><input type="text" name="page" value="1" /></td>
			</tr>										
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getReviews', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>					

	<form action="<?=site_url('api/book/getScoreInfo')?>" method="post" id="book_getScoreInfo">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书本评分情况</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="2" /></td>
			</tr>														
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getScoreInfo', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/getPageContent')?>" method="post" id="book_getPageContent">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取单页内容</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="9" /></td>
			</tr>														
			<tr>
				<th align="right">章节id:</th>
				<td><input type="text" name="chapterId" value="5" /></td>
			</tr>														
			<tr>
				<th align="right">页码:</th>
				<td><input type="text" name="page" value="1" /></td>
			</tr>														
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getPageContent', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>		

	<form action="<?=site_url('api/book/addFav')?>" method="post" id="book_addFav">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>收藏书本</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="9" /></td>
			</tr>																												
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('addFav', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/delFav')?>" method="post" id="book_delFav">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>取消收藏</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="9" /></td>
			</tr>																												
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('delFav', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>		


	<form action="<?=site_url('api/book/getMyReview')?>" method="post" id="book_getMyReview">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取我对书本的评论</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="9" /></td>
			</tr>																												
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getMyReview', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	


	<form action="<?=site_url('api/book/addNote')?>" method="post" id="book_addNote">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>添加笔记</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																												
			<tr>
				<th align="right">章节id:</th>
				<td><input type="text" name="chapterId" value="10" /></td>
			</tr>																												
			<tr>
				<th align="right">页码:</th>
				<td><input type="text" name="page" value="9" /></td>
			</tr>																												
			<tr>
				<th align="right">笔记针对的文字:</th>
				<td><input type="text" name="charContent" value="笔记针对的文字" /></td>
			</tr>																																																								
			<tr>
				<th align="right">笔记文字在页码的起始字符数:</th>
				<td><input type="text" name="charBegin" value="笔记文字在页码的起始字符数" /></td>
			</tr>																												
			<tr>
				<th align="right">笔记的内容:</th>
				<td><input type="text" name="noteContent" value="笔记的内容" /></td>
			</tr>																												
			<tr>
				<th align="right">书签页20个字:</th>
				<td><input type="text" name="content" value="书签页20个字" /></td>
			</tr>																												
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('addNote', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/editNote')?>" method="post" id="book_editNote">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>修改笔记</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																												
			<tr>
				<th align="right">笔记id:</th>
				<td><input type="text" name="noteId" value="11" /></td>
			</tr>																												
			<tr>
				<th align="right">书签页20个字:</th>
				<td><input type="text" name="content" value="书签页20个字" /></td>
			</tr>																												
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('editNote', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/delNote')?>" method="post" id="book_delNote">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>删除笔记</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																												
			<tr>
				<th align="right">笔记id:</th>
				<td><input type="text" name="noteId" value="11" /></td>
			</tr>																																																								
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('delNote', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>		

	<form action="<?=site_url('api/book/getNote')?>" method="post" id="book_getNote">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取笔记列表</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																																																							
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getNote', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/addBookmark')?>" method="post" id="book_addBookmark">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>添加书签</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																																																						
			<tr>
				<th align="right">章节id:</th>
				<td><input type="text" name="chapterId" value="10" /></td>
			</tr>																																																						
			<tr>
				<th align="right">页码:</th>
				<td><input type="text" name="page" value="9" /></td>
			</tr>																																																																																																															
			<tr>
				<th align="right">书签内容:</th>
				<td><input type="text" name="content" value="书签页20个字" /></td>
			</tr>																												
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('addBookmark', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/delBookmark')?>" method="post" id="book_delBookmark">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>删除书签</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																																																						
			<tr>
				<th align="right">书签id:</th>
				<td><input type="text" name="bookmarkId" value="9" /></td>
			</tr>																																																																																																																																											
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>	
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('delBookmark', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>			

	<form action="<?=site_url('api/book/isPayPage')?>" method="post" id="book_isPayPage">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>判断此页是否需要付费</b></td>
			</tr>	
			<tr>
				<th align="right">bookId:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																																																							
			<tr>
				<th align="right">chapterId:</th>
				<td><input type="text" name="chapterId" value="12" /></td>
			</tr>																																																							
			<tr>
				<th align="right">page:</th>
				<td><input type="text" name="page" value="1" /></td>
			</tr>																																																							
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('isPayPage', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/payPage')?>" method="post" id="book_payPage">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>支付单页</b></td>
			</tr>	
			<tr>
				<th align="right">bookId:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																																																							
			<tr>
				<th align="right">chapterId:</th>
				<td><input type="text" name="chapterId" value="12" /></td>
			</tr>																																																							
			<tr>
				<th align="right">page:</th>
				<td><input type="text" name="page" value="1" /></td>
			</tr>																																																							
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('payPage', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/book/getBookmark')?>" method="post" id="book_getBookmark">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>获取书签列表</b></td>
			</tr>	
			<tr>
				<th align="right">书本id:</th>
				<td><input type="text" name="bookId" value="12" /></td>
			</tr>																																																							
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>			
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('getBookmark', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/user/login')?>" method="post" id="user_login">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>登录</b></td>
			</tr>			
			<tr>
				<th align="right">email:</th>
				<td><input type="text" name="email" value="33@qq.com" /></td>
			</tr>			
			<tr>
				<th align="right">password:</th>
				<td><input type="text" name="password" value="dddddd" /></td>
			</tr>
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>						
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('login', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>

	<form action="<?=site_url('api/user/loginOut')?>" method="post" id="user_loginOut">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>退出登录</b></td>
			</tr>
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>						
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('loginOut', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>

	<form action="<?=site_url('api/user/reg')?>" method="post" id="user_reg">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>注册</b></td>
			</tr>	
			<tr>
				<th align="right">username:</th>
				<td><input type="text" name="username" value="zhanghao" /></td>
			</tr>					
			<tr>
				<th align="right">email:</th>
				<td><input type="text" name="email" value="33@qq.com" /></td>
			</tr>			
			<tr>
				<th align="right">password:</th>
				<td><input type="text" name="password" value="dddddd" /></td>
			</tr>
			<tr>
				<th align="right">confrim password:</th>
				<td><input type="text" name="password2" value="dddddd" /></td>
			</tr>			
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>						
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('reg', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	

	<form action="<?=site_url('api/user/editInfo')?>" method="post" id="user_editInfo">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>修改用户信息</b></td>
			</tr>	
			<tr>
				<th align="right">uid:</th>
				<td><input type="text" name="uid" value="10" /></td>
			</tr>			<tr>
				<th align="right">username:</th>
				<td><input type="text" name="username" value="zhanghao" /></td>
			</tr>								
			<tr>
				<th align="right">oldPassword:</th>
				<td><input type="text" name="oldPassword" value="dddddd" /></td>
			</tr>			<tr>
				<th align="right">newPassword:</th>
				<td><input type="text" name="newPassword" value="dddddd" /></td>
			</tr>
			<tr>
				<th align="right">confirmNewPassword:</th>
				<td><input type="text" name="confirmNewPassword" value="dddddd" /></td>
			</tr>			
			<tr>
				<th align="right">random:</th>
				<td><input type="text" name="random" value="<?=$timestamp?>" /></td>
			</tr>						
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo gsign('editInfo', $timestamp)?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>

	<?php $sign = strtoupper(md5('facebookLogin12222232320zhanghaoalongken2005@qq.com!qaa$%^&*zxsw234edc$%^'))?>
	<form action="<?=site_url('api/user/facebookLogin')?>" method="post" id="user_facebookLogin">
		<table cellspacing="5" cellpadding="0">
			<tr>
				<th width="200"></th>
				<td><b>facebook登陆</b></td>
			</tr>	
			<tr>
				<th align="right">facebookId:</th>
				<td><input type="text" name="facebookId" value="12222232320" /></td>
			</tr>	
			<tr>
				<th align="right">username:</th>
				<td><input type="text" name="username" value="zhanghao" /></td>
			</tr>					
			<tr>
				<th align="right">email:</th>
				<td><input type="text" name="email" value="alongken2005@qq.com" /></td>
			</tr>															
			<tr>
				<th align="right">sign:</th>
				<td><input type="text" name="sign" value="<?php echo $sign?>" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="提交" /></td>
			</tr>
		</table>
	</form>	
</body>
</html>