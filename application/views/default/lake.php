<?php $this->load->view(THEME.'/lake_header');?>
<script type="text/javascript" src="<?=THEME_VIEW?>/js/slide.js"></script>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/lake.css"/>

	<div class="lake_focus">
		<div class="lake_menu">
			<a href="<?=site_url('lake/notice_list')?>">文件通知</a>
			<a href="#m2" class="godown">儿童阅读</a>
			<a href="#m2" class="godown">班级读书会</a>
			<a href="#m2" class="godown">故事妈妈</a>
			<a href="#m2" class="godown">新作文联盟</a>
			<a href="#m2" class="godown">国学经典</a>
		</div>
		<div class="focus clearfix">
			<div class="panel_box" >
				<ul>
					<?php foreach($piclist as $v):?>
					<li>
						<a href="">
							<img src="<?=get_thumb($v['filename'], false)?>" />
						</a>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
			<div class="num">
				<span></span>
				<?php foreach($piclist as $k=>$v):?>
				<a href="javascript:void(0)"><img src="<?=get_thumb($v['filename'])?>"/></a>
				<?php endforeach;?>
			</div>
		</div>

<script type="text/javascript">
    slidshow($('.focus'), true);
</script>
		<div class="intro">
			<div class="title sp_index"></div>
			<div class="content"><?=$intros['lakeIntro']?></div>
		</div>
	</div>
	<div class="single_bottom2"></div>

	<div id="lake_tab_a">
		<div class="title clearfix" id="m1">
			<span class="current"><a href="javascript:void(0);">教学设计</a></span>
			<span><a href="javascript:void(0);">名师讲堂</a></span>
		</div>
		<div class="content0 clearfix contentbox">
			<div class="scrollable-panel">
				<div class="clearfix cn">
					<div class="screen">
				<?php
					$i=1;
					foreach($desglist as $v):
						if($i%7==0) echo '</div><div class="screen">';
				?>
					<div class="li">
						<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="img" target="_blank"><img src="<?=get_thumb($v['cover'])?>"/></a>
						<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="a1" target="_blank"><?=$v['title']?></a>
						<?php if($v['gname']):?><a href="<?=site_url('lake/grade?id='.$v['grade'])?>" target="blank" class="gname">[<?=$v['gname']?>]</a><?php endif;?>
						<div class="clear"></div>
						作者：<a href="<?=site_url('lake/author?id='.$v['authorid'])?>" class="author"><?=$v['name']?></a>&nbsp;&nbsp;&nbsp;浏览量：<?=$v['hits']?>
					</div>
				<?php
					$i++;
					endforeach;
				?>
					</div>
				</div>
			</div>
			<div class="trigger-bar">
				<a href="javascript:void(0);" title="上翻" class="prev"></a>
				<div class="scrollable-trigger"></div>
				<a href="javascript:void(0);" title="下翻" class="next"></a>
			</div>
			<a href="<?=site_url('lake/search?type=lakeDesign')?>" class="more sp_index" target="_blank">更多内容 &raquo;</a>
		</div>
		<div class="content1 clearfix contentbox" style="display: none">
			<div class="scrollable-panel">
				<div class="clearfix cn">
					<div class="screen">
				<?php
					$i=1;
					foreach($toplist as $v):
						if($i%7==0) echo '</div><div class="screen">';
				?>
					<div class="li">
						<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="img" target="_blank"><img src="<?=get_thumb($v['cover'])?>"/></a>
						<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="a1" target="_blank"><?=$v['title']?></a>
						<?php if($v['gname']):?>
							<a href="<?=site_url('lake/grade?id='.$v['grade'])?>" target="blank" class="gname">[<?=$v['gname']?>]</a>
						<?php endif;?>
						<div class="clear"></div>
						作者：<a href="<?=site_url('lake/author?id='.$v['authorid'])?>" class="author"><?=$v['name']?></a>&nbsp;&nbsp;&nbsp;浏览量：<?=$v['hits']?>
					</div>
				<?php
					$i++;
					endforeach;
				?>
					</div>
				</div>
			</div>
			<div class="trigger-bar">
				<a href="javascript:void(0);" title="上翻" class="prev"></a>
				<div class="scrollable-trigger"></div>
				<a href="javascript:void(0);" title="下翻" class="next"></a>
			</div>
			<a href="<?=site_url('lake/search?type=top')?>" class="more sp_index" target="_blank">更多内容 &raquo;</a>
		</div>
		<div class="single_bottom3"></div>
	</div>

	<div id="lake_tab_b">
		<div class="tab clearfix" id="m2">
			<span class="current" rel="lakeCread"><a href="javascript:void(0)">儿童阅读</a></span>
			<span rel="lakeClass"><a href="javascript:void(0)">班级读书会</a></span>
			<span rel="lakeStory"><a href="javascript:void(0)">故事妈妈</a></span>
			<span rel="lakeContent"><a href="javascript:void(0)">新作文联盟</a></span>
			<span rel="lakeState"><a href="javascript:void(0)">国学经典</a></span>
		</div>
<?php $i=0; foreach($gradeResult as $key=>$value):?>
		<div class="content<?=$key?> clearfix contentbox" <?=$i>0?'style="display: none"':''?>>
			<?php if($intros[$key]):?>
			<div class="intros"><?=$intros[$key]?></div>
			<?php endif;?>
			<div class="scrollable-panel" style="height:270px;">
				<div class="clearfix cng">
				<?php
					foreach($value as $v):
				?>
					<div class="li">
						<a href="<?=site_url('lake/grade?id='.$v['id'])?>" class="img" target="_blank"><img src="<?=get_thumb($v['cover'])?>"/></a>
						<a href="<?=site_url('lake/grade?id='.$v['id'])?>" class="a1" target="_blank"><?=$v['name']?></a>
					</div>
				<?php
					endforeach;
				?>
				</div>
			</div>

			<div class="trigger-bar" <?php if(count($value) < 4): echo "style='display:none'"; endif;?>>
				<a href="javascript:void(0);" title="上翻" class="prev"></a>
				<div class="scrollable-trigger"></div>
				<a href="javascript:void(0);" title="下翻" class="next"></a>
			</div>
		</div>
<?php $i++; endforeach;?>

		<!--div class="content4 clearfix contentbox" style="display: none">
			<?php if($intros[8]):?>
			<div class="intros"><?=$intros[8]?></div>
			<?php endif;?>
			<div class="scrollable-panel">
				<div class="clearfix cn">
				<?php
					foreach($lakeStory as $v):
				?>
					<div class="li">
						<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="img" target="_blank"><img src="<?=get_thumb($v['cover'])?>"/></a>
						<a href="<?=site_url('lake/subject?id='.$v['id'])?>" class="a1" target="_blank"><?=$v['title']?></a>
						<?php if($v['gname']):?>
							<a href="<?=site_url('lake/grade?id='.$v['grade'])?>" target="blank" class="gname">[<?=$v['gname']?>]</a>
						<?php endif;?>
						<div class="clear"></div>
						作者：<a href="<?=site_url('lake/author?id='.$v['authorid'])?>" class="author"><?=$v['name']?></a>&nbsp;&nbsp;&nbsp;浏览量：<?=$v['hits']?>
					</div>
				<?php
					endforeach;
				?>
				</div>
			</div>
			<a href="<?=site_url('lake/search?type=lakeState')?>" class="more sp_index" target="_blank">更多内容 &raquo;</a>
		</div-->
		<div class="single_bottom3"></div>
	</div>

	<div id="lake_tab_c">
		<div class="title sp_index" id="m4"></div>
		<div class="content clearfix">
			<a href="javascript:void(0);" title="上翻" class="prev"></a>
			<div class="scrollable-panel">
				<div class="clearfix cb">
					<div class="screen">
				<?php
					$i=1;
					foreach($authorlist as $v):
						if($i%9==0) echo '</div><div class="screen">';
				?>
					<div class="li">
						<a href="<?=site_url('lake/author?id='.$v['id'])?>" class="img"><img src="<?=get_thumb($v['cover'])?>"/></a>
						<div class="name"><?=$v['name']?></div>
						<div class="zc"><?=$v['title']?></div>
					</div>
				<?php
					$i++;
					endforeach;
				?>
					</div>
				</div>
			</div>
			<a href="javascript:void(0);" title="下翻" class="next"></a>

			<div class="trigger-bar">
				<div class="scrollable-trigger"></div>
			</div>
		</div>
	</div>

<script type="text/javascript" src="<?=base_url('./common/js/jquery.switchable.js')?>"></script>
<script type="text/javascript">
$(function(){
	$("#lake_tab_a .title span").click(function() {
		$("#lake_tab_a .title span").removeClass('current');
		$(this).addClass('current');
		var tabindex = $("#lake_tab_a .title span").index($(this));
		$("#lake_tab_a .contentbox").hide();
		$("#lake_tab_a .content"+tabindex).show();
	});

	var taba0 = $("#lake_tab_a .content0 .scrollable-trigger").switchable("#lake_tab_a .content0 .scrollable-panel .screen", {
		triggerType: "click",
		effect: "scroll",
		steps: 1,
		visible: 1,
		api: true
	});
	$("#lake_tab_a .content0 .next").click(function(){
		taba0.next();
	});
	$("#lake_tab_a .content0 .prev").click(function(){
		taba0.prev();
	});
	var taba1 = $("#lake_tab_a .content1 .scrollable-trigger").switchable("#lake_tab_a .content1 .scrollable-panel .screen", {
		triggerType: "click",
		effect: "scroll",
		steps: 1,
		visible: 1,
		api: true
	});
	$("#lake_tab_a .content1 .next").click(function(){
		taba1.next();
	});
	$("#lake_tab_a .content1 .prev").click(function(){
		taba1.prev();
	});

	var tabb0 = $("#lake_tab_b .content0 .scrollable-trigger").switchable("#lake_tab_b .content0 .scrollable-panel .li", {
		triggerType: "click",
		effect: "scroll",
		steps: 3,
		visible: 3,
		api: true
	});
	$("#lake_tab_b .content0 .next").click(function(){
		tabb0.next();
	});
	$("#lake_tab_b .content0 .prev").click(function(){
		tabb0.prev();
	});

	var tabb1 = $("#lake_tab_b .content1 .scrollable-trigger").switchable("#lake_tab_b .content1 .scrollable-panel .li", {
		triggerType: "click",
		effect: "scroll",
		steps: 3,
		visible: 3,
		api: true
	});
	$("#lake_tab_b .content1 .next").click(function(){
		tabb1.next();
	});
	$("#lake_tab_b .content1 .prev").click(function(){
		tabb1.prev();
	});

	var tabc = $("#lake_tab_c .scrollable-trigger").switchable("#lake_tab_c .scrollable-panel .screen", {
		triggerType: "click",
		effect: "scroll",
		steps: 1,
		visible: 1,
		api: true
	});
	$("#lake_tab_c .next").click(function(){
		tabc.next();
	});
	$("#lake_tab_c .prev").click(function(){
		tabc.prev();
	});

	$("#lake_tab_b .tab span").click(function() {
		$("#lake_tab_b .tab span").removeClass('current');
		$(this).addClass('current');
		var tabindex = $(this).attr('rel');
		if(tabindex != '') {
			$("#lake_tab_b .content"+tabindex+" .cn").css("left","0px");
		}
		$("#lake_tab_b .contentbox").hide();
		$("#lake_tab_b .content"+tabindex).show();
	});

	$('.godown').click(function() {
		var index = $('.lake_menu a').index($(this))-1;
		$("#lake_tab_b .tab span:eq("+index+")").click();
	})
});
</script>
<?php $this->load->view(THEME.'/lake_footer');?>