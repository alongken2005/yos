<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
?>
<h2>书单列表<div class="operate"><a href="<?=site_url('admin/single/op')?>">添加</a></div></h2>
<table cellpadding="0" cellspacing="0" border="0" class="table2">
	<tr>
		<th width="80">订单号</th>
		<th width="80">买家</th>
		<th style="text-align: left;padding-left: 5px">收货地址</th>
		<th width="40">数量</th>
		<th width="80">总价</th>
		<th width="50">状态</th>
		<th width="110">创建时间</th>
	</tr>
<?php if($lists): foreach($lists as $v):?>
	<tr>
		<td><?=$v['id']?></td>
		<td><?=$v['username']?></td>
		<td style="text-align: left;padding-left: 5px" class="addr"><?=$v['receiver'].'，'.$v['tel'].'，'.$v['address'].'，'.$v['postcode']?></td>
		<td><?=$v['amount']?></td>
		<td><?=$v['price']?></td>
		<td><?php if($v['state'] == 1) { echo '成功';} elseif($v['state'] == 0) { echo '未支付';} else {echo '失败';}?></td>
		<td><?=date('Y-m-d H:i',$v['ctime'])?></td>
	</tr>
<?php endforeach; endif;?>
</table>
<?=$pagination?>
<script type="text/javascript">
$(function() {
	$('.del').click(function() {
		if(confirm('确认删除？')) {
			var po = $(this).parent().parent();
			$.get($(this).attr('href'), '', function(data) {
				if(data == 'ok') {
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