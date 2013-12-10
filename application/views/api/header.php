<!DOCTYPE html>
<html>
<head>
	<title>You Shelf API文档</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="<?=BASE_VIEW?>api/css/style.css" type="text/css" />
</head>
<body>
<div class="api_header">
	<div class="api_box">You Shelf API文档</div>
</div>
<div class="api_box">
	<ul class="menu">
		<li <?php echo $v == 'book' ? 'class="active"' : ''?>>
			<a href="<?=site_url('api/book/views')?>#book_getDirectory">书本相关接口</a>
			<div class="childbox">
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getDirectory">获取目录列表</a>
				</div>
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getChapter">获取章节内容</a>
				</div>	
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getGenre">获取书本分类</a>
				</div>	
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getBooks">获取书本列表</a>
				</div>	
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getBookInfo">获取书本信息</a>
				</div>	
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_inputPages">接收段落的单页内容</a>
				</div>															
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getBookContents">查看书本章节单页等内容</a>
				</div>															
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_writeRviews">发表评论</a>
				</div>		
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getScoreInfo">获取书本评分情况</a>
				</div>																	
				<div class="childend">
					<a href="<?=site_url('api/book/views')?>#book_getPageContent">获取单页内容</a>
				</div>																	
			</div>
		</li>
		<li <?php echo $v == 'mobile' ? 'class="active"' : ''?>>
			<a href="<?=site_url('api/user/views')?>#user_login">用户相关接口</a>
			<div class="childbox">
				<div class="childend">
					<a href="<?=site_url('api/user/views')?>#user_login">登录</a>
				</div>				
				<div class="childend">
					<a href="<?=site_url('api/user/views')?>#user_reg">注册</a>
				</div>
			</div>
		</li>		
	</ul>