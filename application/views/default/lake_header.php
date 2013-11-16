<?php $this->load->view(THEME.'/header');?>
<link rel="stylesheet" type="text/css" href="<?=THEME_VIEW?>/css/lake.css"/>
<?php if($this->uri->segment(2) == '' || $this->uri->segment(2) == 'main' || $this->uri->segment(2) == 'index'):?>
<div class="iland png" style="margin-top: 50px;"></div>
<?php else:?>
<div class="iland2 png" style="margin-top: 50px;"></div>
<?php endif;?>