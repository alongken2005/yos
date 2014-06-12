<div class="slider_left">
	<li class="first_level">
		My Account Info
	</li>
	<li <?=(isset($active) && $active == 'pay') ? "class='active'" : ''?>>
		<a href="<?=site_url('pay')?>" class="sec_level">Payment Tool</a>
	</li>
	<li <?=(isset($active) && $active == 'userinfo') ? "class='active'" : ''?>>
		<a href="<?=site_url('user/info')?>" class="sec_level" >Login Info</a>
	</li>
	<?php if($userInfo['is_author'] == 1) {?>
	<li class="first_level">
		Author Dashboard
	</li>
	<!--li>
		<a href="" class="sec_level">Notification</a>
	</li-->
	<li <?=(isset($active) && $active == 'sales') ? "class='active'" : ''?>>
		<a href="<?=site_url('dashboard/sales')?>" class="sec_level">Sales and Trends</a>
	</li>
	<li <?=(isset($active) && $active == 'lists') ? "class='active'" : ''?>>
		<a href="<?=site_url('book/lists')?>" class="sec_level">Manage Books</a>
	</li>	
	<li <?=(isset($active) && $active == 'edit') ? "class='active'" : ''?>>
		<a href="<?=site_url('book/edit')?>" class="sec_level" style="padding-left:20px">» Add New Book</a>
	</li>
	<li <?=(isset($active) && $active == 'promote') ? "class='active'" : ''?>>
		<a href="<?=site_url('dashboard/promote')?>" class="sec_level">Promote Books</a>
	</li>
	<li <?=(isset($active) && $active == 'banking') ? "class='active'" : ''?>>
		<a href="<?=site_url('dashboard/banking')?>" class="sec_level">Contracts and Banking</a>
	</li>
	<li <?=(isset($active) && $active == 'payment') ? "class='active'" : ''?>>
		<a href="<?=site_url('dashboard/payment?limit=3')?>" class="sec_level">Payment Reports</a>
	</li>
	<li <?=(isset($active) && $active == 'contact') ? "class='active'" : ''?>>
		<a href="<?=site_url('dashboard/contact')?>" class="sec_level">Contact Us</a>
	</li>
	<?php } ?>		
</div>