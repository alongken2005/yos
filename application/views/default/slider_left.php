<ul class="slider_left">
	<li <?=(isset($active) && $active == 'userinfo') ? "class='active'" : ''?>>
		<a href="<?=site_url('user/info')?>" class="first_level">My Account Info</a>
	</li>
	<li>
		<a href="" class="sec_level">Payment Tool</a>
	</li>
	<li>
		<a href="" class="sec_level">Reading Deposit</a>
	</li>
	<li>
		<a href="" class="sec_level">Login Info</a>
	</li>
	<li>
		<a href="" class="first_level">Author Dashboard</a>
	</li>
	<li>
		<a href="" class="sec_level">Notification</a>
	</li>
	<li>
		<a href="" class="sec_level">Sales and Trends</a>
	</li>
	<li <?=(isset($active) && $active == 'edit') ? "class='active'" : ''?>>
		<a href="<?=site_url('book/lists')?>" class="sec_level">Manage Books</a>
		<a href="<?=site_url('book/edit')?>" class="right">Add</a>
	</li>
	<li>
		<a href="" class="sec_level">Promote Books</a>
	</li>
	<li>
		<a href="" class="sec_level">Contracts and Banking</a>
	</li>
	<li>
		<a href="" class="sec_level">Payment Reports</a>
	</li>
	<li>
		<a href="" class="sec_level">Contact Us</a>
	</li>			
</ul>