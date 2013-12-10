<?php foreach($lists as $v) {?>
	<li>
		<a href="<?=site_url('book/floatinfo?bid='.$v['id'])?>" rel="<?=$v['id']?>"><img src="data/books/<?=$v['cover']?>"/></a>
	</li>
<?php }?>