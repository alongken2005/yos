<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/index.css"/>
<div class="box">
	<div class="slider_title">Continue Reading for Jerry</div>
	<div class="slider_box">
		<a href="" class="pre"></a>
		<div class="slider_imgs">
			<ul>
			<?php for($i = 1; $i<8; $i++) {?>
				<li>
					<img src="data/12.jpg"/>
					<div><?="好吧".$i?></div>
				</li>
			<?php }?>
			</ul>
		</div>
		<a href="" class="next"></a>
	</div>
</div>
<?php $this->load->view(THEME.'/footer');?>