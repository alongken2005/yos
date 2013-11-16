<div class="slider">
	<div class="block">
		<p>视频教案管理</p>
		<ul>
			<li <?=$thisClass=='video'?'class="active"':''?>>
				<a href="<?=site_url('admin/stuff/lists?kind=video')?>" class="left">视频管理</a>
				<a href="<?=site_url('admin/stuff/op?kind=video')?>" class="right">添加</a>
			</li>
			<li <?=$thisClass=='stuff'?'class="active"':''?>>
				<a href="<?=site_url('admin/stuff/lists?kind=stuff')?>" class="left">教案管理</a>
				<a href="<?=site_url('admin/stuff/op?kind=stuff')?>" class="right">添加</a>
			</li>
			<li <?=$thisClass=='Author'?'class="active"':''?>>
				<a href="<?=site_url('admin/author/lists')?>" class="left">作者管理</a>
				<a href="<?=site_url('admin/author/op')?>" class="right">添加</a>
			</li>
		</ul>
	</div>

	<div class="block">
		<p>其他</p>
		<ul>
			<li <?=$thisClass=='Attach'?'class="active"':''?>>
				<a href="<?=site_url('admin/attach/lists')?>" class="left">附件管理</a>
			</li>
			<li <?=$thisClass=='Type'?'class="active"':''?>>
				<a href="<?=site_url('admin/type/lists')?>" class="left">分类管理</a>
				<a href="<?=site_url('admin/type/op')?>" class="right">添加</a>
			</li>
			<li <?=$thisClass=='Tag'?'class="active"':''?>>
				<a href="<?=site_url('admin/tag/lists')?>" class="left">标签管理</a>
				<a href="<?=site_url('admin/tag/op')?>" class="right">添加</a>
			</li>
			<li <?=$thisClass=='Adminer'?'class="active"':''?>>
				<a href="<?=site_url('admin/adminer/adminer_list')?>" class="left">后台账号管理</a>
				<a href="<?=site_url('admin/adminer/adminer_op')?>" class="right">添加</a>
			</li>
		</ul>
	</div>
</div>