<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2><span class="spantitle">Payment Report</span><!--div class="operate"><a href="<?=site_url('admin/msgs/lists')?>">帖子管理</a></div--></h2>
<form action="<?=site_url('admin/report/lists')?>" method="get" style="margin: 3px 5px;">
	<select name="year">
	<?php for($i = 2013; $i<=date('Y'); $i++) { ?>
		<option value="<?=$i?>" <?=$year==$i ? 'selected' : ''?>><?=$i?></option>
	<?php } ?>
	</select> 年 &nbsp;&nbsp;

	<select name="month">
	<?php for($m=1;$m<13;$m++) { ?>
		<option value="<?=$m?>" <?=$month==$m ? 'selected' : ''?>><?=$m?></option>
	<?php } ?>	
	</select>月
	<input type="submit" value="提交"/>
</form>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th>作者</th>
		<th width="130">总额</th>
		<th width="150">操作</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td style="text-align: left; padding-left: 10px"><?=$v['author']?></td>
		<td style="text-align: left; padding-left: 10px"><?=$v['payed']?></td>
		<td>
		<?php if(!isset($report[$v['uid'].'_'.date('Ym', $v['ctime'])])) { ?>
			<a href="<?=site_url('admin/report/op?uid='.$v['uid'].'&payed='.$v['payed'].'&time='.$stime)?>" class="deal">结算</a>&nbsp;
		<?php } else {
			echo "已结算";
		} ?>
		</td>
	</tr>
<?php endforeach; endif;?>
</table>
<?=$pagination?>
<script type="text/javascript">
$(function() {
	$('.del').click(function() {
		if(confirm('确认删除？')){
			var po = $(this).parent().parent();
			$.get($(this).attr('href'), '', function(data) {
				if(data == 'ok'){
					po.hide();
				} else {
					alert('删除失败！');
				}
			})
		}
		return false;
	})
})
</script>
<?php $this->load->view('admin/footer');?>