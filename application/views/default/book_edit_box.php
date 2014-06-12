<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div id="chapterForm">
	<h2>Add chapter</h2>
	<form action="<?=site_url('book/chapter_edit?bid='.$bid)?>" method="post">
		<table cellpadding="0" cellspacing="5">
			<tr class="addChapterBox">
				<th width="80">Sequence:</th>
				<td><input type="text" name="dis" class="input1" id="dis" value="<?=isset($chapter) ? $chapter['dis'] : ''?>" /></td>
			</tr>
			<tr>
				<th>Title:</th>
				<td><input type="text" name="title" class="input6" id="title" value="<?=isset($chapter) ? $chapter['title'] : ''?>"/></td>
			</tr>
			<tr>
				<th>Content:</th>
				<td><textarea name="content" class="textarea1" id="content" style="width:640px;height:390px;padding:5px;"><?=isset($chapter) ? $chapter['content'] : ''?></textarea></td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="hidden" name="cid" value="<?=isset($chapter) ? $chapter['id'] : ''?>"/>
					<input type="submit" id="chapterSave" class="btn1" value="Save"/>
					<input type="button" class="btn1 cancel" style="margin-left:20px" value="Cancel"/>
				</td>
			</tr>
		</table>	
	</form>
</div>
<script type="text/javascript">
	$('.cancel').click(function() {
		parent.floatClose();
	})
</script>