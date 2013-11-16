<!DOCTYPE html>
<html>
<head>
	<title>管理后台</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="<?=BASE_VIEW?>admin/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url('./common/ligerUI/skins/Aqua/css/ligerui-all.css')?>"/>
	<script type="text/javascript" src="<?=base_url('./common/js/jquery.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('./common/ligerUI/js/ligerui.min.js')?>" ></script>
	<script type="text/javascript" src="<?=BASE_VIEW?>admin/js/common.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#mainbox").ligerLayout({leftWidth: 190, height: '100%', heightDiff: -4});
			$(".slider").ligerAccordion({speed: 'fast'});
			$("#framecenter").ligerTab({height: '100%'});
			var tab = $("#framecenter").ligerGetTabManager();
			setTimeout(function() {
				$('.add_tab').first().click();
			}, 200);

			$(".add_tab").click(function() {
				var tid = $(this).attr('tabid');
				if(!tid) {
					tid = tab.getNewTabid();
					$(this).attr('tabid', tid);
				}
				if(tid == 'home') {
					tab.addTabItem({tabid: tid, text: $(this).text(), url: $(this).attr('href'), showClose: false});
				} else {
					tab.addTabItem({tabid: tid, text: $(this).text(), url: $(this).attr('href')});
				}
				$(this).parent().attr('class', 'active');
				$(this).parent().siblings().removeClass();
				return false;
			})
		})
	</script>
</head>
<body>
	<div id="topmenu" class="header">
		<?='欢迎你，'.get_cookie('username').'&nbsp;<a href="'.site_url('admin/login/login_out').'">退出</a>'?>
		| <a href="<?=site_url('movie')?>" class="l-link2" target="_blank">儿童之路首页</a>
	</div>
	<div id="mainbox" style="width:99.3%; margin:0 auto; margin-top:4px; ">
		<div position="left"  title="主要菜单" class="slider">
			<div title="运河电影">
				<ul>
					<li><a href="<?=site_url('admin/movie/lists')?>" class="add_tab" tabid="home">电影</a></li>
					<li><a href="<?=site_url('admin/mview/lists')?>" class="add_tab">影视欣赏</a></li>
					<li><a href="<?=site_url('admin/mclips/lists')?>" class="add_tab">电影片花</a></li>
					<li><a href="<?=site_url('admin/mimage/lists')?>" class="add_tab">电影图片</a></li>
					<li><a href="<?=site_url('admin/movie/ticketlog')?>" class="add_tab">索票记录</a></li>
				</ul>
			</div>
			<div title="千岛湖">
				<ul>
					<li><a href="<?=site_url('admin/grade/lists')?>" class="add_tab">界数管理</a></li>
					<li><a href="<?=site_url('admin/author/lists')?>" class="add_tab">作者管理</a></li>
					<li><a href="<?=site_url('admin/subject/lists')?>" class="add_tab">教案管理</a></li>
					<li><a href="<?=site_url('admin/lake_notice/lists')?>" class="add_tab">文件通知</a></li>
					<li><a href="<?=site_url('admin/lake_intro/lists')?>" class="add_tab">介绍管理</a></li>
				</ul>
			</div>
			<div title="单本购买">
				<ul>
					<li><a href="<?=site_url('admin/single/lists')?>" class="add_tab">书单管理</a></li>
					<li><a href="<?=site_url('admin/singleOrder/lists')?>" class="add_tab">订单管理</a></li>
				</ul>
			</div>
			<div title="其他">
				<ul>
					<li><a href="<?=site_url('admin/pic/lists')?>" class="add_tab">图片管理</a></li>
					<li><a href="<?=site_url('admin/attach/lists')?>" class="add_tab">附件管理</a></li>
					<li>
						<a href="<?=site_url('admin/type/lists')?>" class="add_tab left">分类管理</a>
						<a href="<?=site_url('admin/type/op')?>" class="add_tab right">分类添加</a>
					</li>
					<li><a href="<?=site_url('admin/ip/lists')?>" class="add_tab">IP访问管理</a></li>
				</ul>
			</div>
		</div>
		<div position="center" id="framecenter"></div>
	</div>
</body>
</html>

