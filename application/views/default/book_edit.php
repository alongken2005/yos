<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/book.css"/>


<div class="box">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th>Book Title:</th>
			<td><input type="text" name="title"/></td>
		</tr>
		<tr>
			<th>ISBN (Optional):</th>
			<td><input type="text" name="title"/></td>
		</tr>
		<tr>
			<th>Author Name:</th>
			<td><input type="text" name="title"/></td>
		</tr>
		<tr>
			<th>Publisher (Optional):</th>
			<td><input type="text" name="title"/></td>
		</tr>
		<tr>
			<th>Genre:</th>
			<td><input type="text" name="title"/></td>
		</tr>
		<tr>
			<th>Price for Paid Section Text:</br>($/1000 words)</th>
			<td><input type="text" name="title"/></td>
		</tr>
		<tr>
			<th>Define Paid Section:</th>
			<td><input type="text" name="title"/></td>
		</tr>										
	</table>
</div>


<script type="text/javascript">
	$(function() {


	})

</script>
<?php $this->load->view(THEME.'/footer');?>