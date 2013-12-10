<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?php $this->load->view(THEME.'/slider_left');?>

	<div class="space_box">
		<div class="book_step">
			<a href="<?=site_url('book/edit?step=1&id='.$id)?>" <?=$step == 1 ? 'class="step"' : ''?>>Basic Info</a>
			<a href="<?=site_url('book/edit?step=2&id='.$id)?>" <?=$step == 2 ? 'class="step"' : ''?>>Content</a>
			<a href="<?=site_url('book/edit?step=3&id='.$id)?>" <?=$step == 3 ? 'class="step"' : ''?>>Preview</a>
		</div>
	<?php if($step == 1):?>	
		<form action="<?=site_url('book/edit?step=1&id='.$id)?>" method="post" enctype="multipart/form-data" style="position:relative">
			<?php if(isset($row['cover']) && $row['cover']):?>
			<img src="<?='data/books/'.$row['cover']?>" width="200" style="position: absolute;right:0;top:8px;"/>
			<?php endif;?>
			<table cellpadding="0" cellspacing="8" class="book_edit_table">
				<tr>
					<th>Book Title:</th>
					<td><input type="text" name="title" class="input6" value="<?=isset($row['title']) ? $row['title'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Book Cover:</th>
					<td><input type="file" name="cover"/></td>
				</tr>		
				<tr>
					<th>ISBN (Optional):</th>
					<td><input type="text" name="isbn" class="input3" value="<?=isset($row['isbn']) ? $row['isbn'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Author Name:</th>
					<td><input type="text" name="author" class="input3" value="<?=isset($row['author']) ? $row['author'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Publisher (Optional):</th>
					<td><input type="text" name="publisher" class="input3" value="<?=isset($row['publisher']) ? $row['publisher'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Genre:</th>
					<td>
						<select name="genre" class="genre">
						<?php foreach($genre as $v):?>
							<option value="<?=$v['id']?>" <?=isset($row['genre']) && $row['genre'] == $v['id'] ? 'selected' : '' ?>><?=$v['name']?></option>
						<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<th>Price for Paid Section Text:</br>($/1000 words)</th>
					<td><input type="text" name="price" class="input4" value="<?=isset($row['text_price']) ? $row['text_price'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Define Paid Section:</th>
					<td><input type="text" name="paid_section" class="input3" value="<?=isset($row['paid_section']) ? $row['paid_section'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Description:</th>
					<td><textarea name="description"><?=isset($row['description']) ? $row['description'] : ''?></textarea></td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit" class="btn1" value="Save and Go Next"></td>
				</tr>												
			</table>
		</form>
	<?php elseif($step == 2):?>
		<iframe name='hidden_frame' width="400" height="300" style="display: none;"></iframe>
		<form action="<?=site_url('book/chapter_add?bid='.$id)?>" method="post">
			<div style="padding:10px 10px">
				Content Type:
				<input type="radio" name="type" value="1" checked="checked"/> Upload Text Chapter by Chapter&nbsp;&nbsp;
				<input type="radio" name="type" value="2"/> Upload PDF&nbsp;&nbsp;
			</div>

			<a href="javascript:void(0)" class="btn1">Add Chapter</a>	
			<table cellpadding="0" cellspacing="0" class="chapter_table">
				<tr>
					<th width="70">Sequence</th>
					<th>Name</th>
					<th width="350">Content</th>
				</tr>
			<?php foreach ($chapters as $key => $value):?>	
				<tr>
					<td><?=$value['dis']?></td>
					<td><?=$value['title']?></td>
					<td><a href="<?=site_url('book/edit?cid='.$value['id'])?>">Edit</a></td>
				</tr>
			<?php endforeach;?>	
				<tr class="addChapterBox">
					<td valign="top"><input type="text" name="dis" class="input1" id="dis"/></td>
					<td valign="top"><input type="text" name="title" class="input5" id="title"/></td>
					<td><textarea name="content" class="textarea1" id="content"></textarea>&nbsp;&nbsp;<input type="submit" id="chapterSave" value="Save" /></td>
				</tr>													
			</table>
			<div style="margin-top:10px;"><a href="<?=site_url('book/edit?step=3&id='.$id)?>" class="btn1">Save and Go Next</a></div>
		</form>
	<?php elseif($step == 3):?>
		<form action="<?=site_url('book/edit?step=3')?>" method="post" style="padding-top:10px;">
			<div style="overflow:hidden;zoom:1">
				<div style="font-size:18px;float:left;line-height:34px;">Review Table of Content Before Publishing</div>
				<a href="javascript:void(0)" class="btn1" style="float:right">Preview This Book</a>
			</div>
			<table cellpadding="0" cellspacing="0" class="chapter_table">
				<tr>
					<th width="70">Sequence</th>
					<th>Name</th>
					<th width="70">Page#</th>
					<th width="350">Content</th>
				</tr>
			<?php foreach ($chapters as $key => $value):?>	
				<tr>
					<td><?=$value['dis']?></td>
					<td><?=$value['title']?></td>
					<td><?=$value['page']+1?></td>
					<td>
						<a href="<?=site_url('book/edit?cid='.$value['id'])?>">Edit</a>&nbsp;&nbsp;
						<a href="<?=base_url('common/splitWord.swf?bookId='.$value['bid'].'&chapterId='.$value['id'].'&showSave=2')?>" target="_blank">Preview</a>
					</td>
				</tr>
			<?php endforeach;?>														
			</table>
			<div style="margin-top:10px;"><a href="<?=site_url('book/edit?step=3&id='.$id)?>" class="btn1">submit</a></div>
		</form>		
	<?php endif;?>
	</div>

</div>
<script type="text/javascript">
	$(function() {

		/*
		$('#chapterSave').click(function() {
			var ex = /^\d+$/;
			var dis = $('#dis').val();
			if(dis == '' || !ex.test(dis)) {
				alert("Sequence can't be empty or Sequence must be int");
			}

			if($('#title').val() == '') {
				alert("Name can't be empty");
			}

			if($('#content').val() == '') {
				alert("Content can't be empty");
			}

			$.post($(this).attr('href'), {dis:$('#dis').val(), title:$('#title').val(), content:$('#content').val()}, function(data) {
				alert(data.msg);
				if(data.state == 1) {
					window.location.href="<?=site_url('book/edit?step=2&id='.$id)?>";
				}
				return false;
			}, 'json');
			return false;
		})*/
	})

	//上传成功后回调函数
	function callback(str,reload) {
		
		if(reload){
			window.location.reload();
		} else{
			alert(str);
			//$('#submitbtn').show();
			//$('#loading').hide();
		}
	}
</script>
<?php $this->load->view(THEME.'/footer');?>