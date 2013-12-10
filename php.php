<?php 
echo phpinfo();exit;
// exit("nologin");
// $content = shell_exec('/usr/local/bin/pdftotext -layout -enc GBK /home/foo/yos/data/heloo.pdf -');
// $content = mb_convert_encoding($content, 'UTF-8','GBK');
// echo $content;

file_get_contents("http://yos.childroad.cn/common/SplitWord.swf?bookId=7&chapterId=3");
exit;

$ch = curl_init(); //初始化curl
curl_setopt($ch, CURLOPT_URL, 'http://yos.childroad.cn/common/SplitWord.swf?bookId=7&chapterId=3');//设置链接
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
$response = curl_exec($ch);//接收返回信息
print_r($response);
if(curl_errno($ch)){//出错则显示错误信息
	print curl_error($ch);
}
curl_close($ch); //关闭curl链接
?>