<?php 
$content = shell_exec('/usr/local/bin/pdftotext -layout -enc GBK /home/foo/yos/data/heloo.pdf -');
$content = mb_convert_encoding($content, 'UTF-8','GBK');
echo $content;
?>