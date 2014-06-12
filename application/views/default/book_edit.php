<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>css/space.css"/>

<div class="box1000">
	<?=$slider_left?>

	<div class="space_box">
		<?php $sname = array(1 => 'Basic Info', 2 => 'Content', 3 => 'Preview'); ?>
		<div class="leader">Author Dashboard > Add New Book  > <?=isset($sname[$step]) ? $sname[$step] : 'Basic Info'?></div>

		<div class="book_step">
			<a href="<?=site_url('book/edit?step=1&id='.$id)?>" <?=$step == 1 ? 'class="step"' : ''?>>Basic Info</a>
			<a href="<?=site_url('book/edit?step=2&id='.$id)?>" <?=$step == 2 ? 'class="step"' : ''?>>Content</a>
			<a href="<?=site_url('book/edit?step=3&id='.$id)?>" <?=$step == 3 ? 'class="step"' : ''?>>Preview</a>
		</div>
		<div class="book_step_bottom"></div>
	<?php if($step == 1):?>	
		<form action="<?=site_url('book/edit?step=1&id='.$id)?>" method="post" enctype="multipart/form-data" style="position:relative">
			<table cellpadding="0" cellspacing="8" class="book_edit_table">
				<tr>
					<th>Book Title:</th>
					<td><input type="text" name="title" class="input6" value="<?=isset($row['title']) ? $row['title'] : ''?>"/></td>
				</tr>
				<tr>
					<th>Book Cover:</th>
					<td>
						<?php if(isset($row['cover']) && $row['cover'] && $row['covered'] == 0):?>
						<img src="<?='data/books/'.$row['cover']?>" width="200"/><br>
						<?php endif;?>					
						<input type="file" name="cover" /><br>
						<ul class="covered">
						<?php 
						$arr = range(1,4); shuffle($arr); 
						foreach($arr as $k=>$v) { 
						?>
							<li>
								<img src="data/books/cover<?=$v?>.jpg"/><br>
								<input type="radio" name="covered" <?=$k == 0 || (isset($row['covered']) && $row['covered'] == $v)? 'checked' : ''?> value="<?=$v?>" />
							</li>
						<?php } ?>
						</ul>
					</td>
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
					<td>
						<select name="paid_section_start">
						<?php for($i=1; $i<30;$i++) { ?>
							<option value="<?=$i?>" <?php if(isset($row['paid_section_start']) && $row['paid_section_start'] == $i) echo 'selected';?>>chapter <?=$i?></option>
						<?php } ?>
						</select>
						<select name="paid_section_end">
							<option value="0">All afterwards</option>
						</select>
					</td>
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
		<table cellpadding="0" cellspacing="0" class="chapter_table">
			<tr>
				<th width="70">Sequence</th>
				<th>Name</th>
				<th width="350">Content</th>
				<th width="60"></th>
			</tr>
		<?php foreach ($chapters as $key => $value):?>	
			<tr>
				<td><?=$value['dis']?></td>
				<td><?=$value['title']?></td>
				<td><?=cutstr($value['content'], 30)?></td>
				<td><a href="<?=site_url('book/chapter_edit?bid='.$id.'&cid='.$value['id'])?>" class="editChapter" style="font-size:12px;font-weight:200">Edit</a></td>
			</tr>
		<?php endforeach;?>														
		</table>
		<div class="clear10"></div>
		<a href="<?=site_url('book/chapter_edit?bid='.$id)?>" class="editChapter">Add Chapter</a>
		<div class="clear10"></div>
		<a href="<?=site_url('book/edit?step=3&id='.$id)?>" class="btn1">Save and Go Next</a>
	<?php elseif($step == 3):?>
		<form action="<?=site_url('book/edit?step=3')?>" method="post" style="padding-top:10px;">
			<div style="overflow:hidden;zoom:1;padding: 3px 0;">
				<div style="font-size:14px;float:left;">Review Table of Content Before Publishing</div>
				<a href="<?=base_url('common/reader/PageReader.swf?bookId='.$id)?>" class="preview" target="_blank">Preview This Book</a>
			</div>
			<table cellpadding="0" cellspacing="0" class="chapter_table">
				<tr>
					<th width="70">Sequence</th>
					<th align="left">Name</th>
					<th width="60">Page#</th>
					<th width="300" align="left">Content</th>
					<th width="90"></th>
				</tr>
			<?php foreach ($chapters as $key => $value):?>	
				<tr>
					<td><?=$value['dis']?></td>
					<td><?=$value['title']?></td>
					<td><?=$value['page']+1?></td>
					<td><?=cutstr($value['content'], 30)?></td>
					<td align="center">
						<a href="<?=site_url('book/chapter_edit?bid='.$id.'&cid='.$value['id'])?>" class="editChapter" style="font-size:12px;font-weight:200">Edit</a>&nbsp;&nbsp;
						<a href="<?=base_url('common/reader/SplitWord.swf?bookId='.$value['bid'].'&chapterId='.$value['id'].'&showSave=2')?>" class="blue" target="_blank">Preview</a>
					</td>
				</tr>
			<?php endforeach;?>														
			</table>
			<div style="margin-top:10px;">
				<a href="javascrit:void(0)" class="btn1 submit">Submit</a>
			</div>
			<div class="author_des" style="display:none">Success! Your book has been submitted. You can update the book anytime following the same steps.</div>
		</form>		
	<?php endif;?>
	</div>
</div>

<script type="text/javascript" src="common/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="common/fancybox/jquery.fancybox-1.3.4.css"/>
<script type="text/javascript">
	$(function() {
		$(".book_step a").click(function() {
			if($(this).attr('href').indexOf('id=0') > 0) {
				alert('Please provide book basic info first. Thank you.');
				return false;
			}
		})
		

		$(".editChapter").fancybox({
			'height'			: 582,
			'width'				: 800,
			'padding'			: 2,
			'type'				: 'iframe',
			'centerOnScroll'	: true,
			'overlayOpacity'	: 0.5,
			'showCloseButton'	: false,
			'hideOnOverlayClick': false,
		});	

		$('.submit').click(function() {
			$('.author_des').show('fast');
		})
	})

	//上传成功后回调函数
	function callback() {
		//setTimeout(function() {
			$.fancybox.close();
			window.location.reload();
		//}, 2000);
	}

	function floatClose () {
		$.fancybox.close();
	}
</script>
<?php $this->load->view(THEME.'/footer');?>