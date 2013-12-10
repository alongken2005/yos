<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>YouShelf</title>
	<base id="headbase" href="<?=base_url()?>">
	<script type="text/javascript" src="common/js/jquery-1.4.2.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/style.css"/>
</head>
<body>
	<div style="padding:10px 0 10px 120px;">
	<a href="<?=site_url('api/book/getBookContents?bookId='.$bookId)?>" class="btn1">书本信息</a>
	<a href="<?=site_url('api/book/getBookContents?type=2&bookId='.$bookId)?>" class="btn1">章节列表</a>
	</div>
	<?php if($type == 1) { ?>
	<table cellpadding="0" cellspacing="5">
	<?php  foreach($book as $key=>$value) { ?>	
		<tr>
			<th width="120" align="right"><?=$key?>：</th>
			<td><?=$value?></td>
		</tr>
	<?php } ?>
	</table>
	<?php } else if($type == 2) { ?>
	
	<?php  foreach($chapters as $value) { ?>
	<table cellpadding="0" cellspacing="5" style="margin-top:20px;">
		<tr>
			<th width="120" align="right">序号：</th>
			<td><?=$value['dis']?></td>
		</tr>
		<tr>
			<th width="120" align="right">标题：</th>
			<td><?=$value['title']?> <a href="<?=site_url('api/book/getBookContents?type=3&chapterId='.$value['id'].'&bookId='.$bookId)?>" style="color:#f00;font-weight:600;">查看单页</a></td>
		</tr>
		<tr>
			<th width="120" align="right">内容：</th>
			<td><?=$value['content']?></td>
		</tr>
	</table>
	<?php } ?>	
	
	<?php } else if($type == 3) { ?>
	<?php  foreach($pages as $value) { ?>
	<table cellpadding="0" cellspacing="5" style="margin-top:20px;">
		<tr>
			<th width="120" align="right">相对页码：</th>
			<td><?=$value['num']?></td>
		</tr>
		<tr>
			<th width="120" align="right">单词数：</th>
			<td><?=$value['words']?></td>
		</tr>
		<tr>
			<th width="120" align="right">内容：</th>
			<td><?=$value['content']?></td>
		</tr>
	</table>
	<?php } ?>			
	<?php } ?>	
</body>
</html>