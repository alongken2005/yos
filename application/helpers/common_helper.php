<?php
/**
 * 字符截取
 * @param $str 字符串
 * @param $len 截取长度
 * @param $char 截取后缀
 * @return string
 */
function cutstr($str, $len, $char = '...') {
	if(mb_strlen($str, 'utf-8') > $len) {
		return mb_substr($str, 0, $len, 'utf-8').$char;
	} else {
		return $str;
	}
}

/**
 * 邮箱格式验证
 * @param $value 邮箱地址
 * @return boolean
 */
function is_email($value) {
	return preg_match("/^[0-9a-zA-Z]+(?:[\_\-\.][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i", $value);
}

/**
 * 创建文件夹
 * @param type $path
 */
function createFolder($path) {
	if (!file_exists($path)) {
		createFolder(dirname($path));
		mkdir($path, 0777);
	}
 }

 /**
  * 日志
  * @param string $msg	内容
  * @param type $level	说明
  * @param type $filename  文件前缀
  * @param type $cf  是否每天生成一个文件
  */
 function write_log($msg, $filename = 'ci', $cf = true) {
	$fname = $cf == true ? $filename.date('-Y-m-d') : $filename;
	$msg = date('Y-m-d H:i:s'). ' --> '.$msg."\r\n";
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/'.$fname.'.log', $msg, FILE_APPEND);
}

/**
 * 获取缩略图
 * @param type $picUrl
 * @return type
 */
function get_thumb($picUrl, $thumb = TRUE, $baseUrl = './data/uploads/pics/', $base = TRUE) {
	if(!$picUrl) return false;
	$cover = pathinfo($picUrl);
	$thumbUrl = $baseUrl.$cover['dirname'].'/'.$cover['filename'].'_thumb.'.$cover['extension'];
	if($thumb && file_exists($thumbUrl)) {
		$picUrl = $thumbUrl;
	} else {
		$picUrl = $baseUrl.$picUrl;
	}
	return $base ? base_url($picUrl) : $picUrl;
}

/**
 * 将换行符等转为html标签
 * @param type $str
 * @return type
 */
function t2h($str) {
	$str = htmlspecialchars($str);
	$str = str_replace(" ", "&nbsp;", $str);
	$str = str_replace("\r\n", "<br>", $str);
	$str = str_replace("\r", "<br>", $str);
	$str = str_replace("\n", "<br>", $str);
	return $str;
}

/**
 * 把秒格式化为时间格式
 */
function secfmt($seconds) {
	$seconds = (int)$seconds;
	if ($seconds>3600){
		$hours = intval($seconds/3600);
		$minutes = $seconds%3600;
		$time = $hours.":".gmstrftime('%M:%S', $minutes);
	} else {
		$time = gmstrftime('%M:%S', $seconds);
	}
	return $time;
}

/**
 * 验证签名
 */
function getSign($action, $random, $sign) {
	if(!$random) output(3002, lang('field_empty'));
	if(!$sign) output(3001, lang('sign_error'));

	$signPostfix = config_item('signPostfix');
	$sign2 = strtoupper(md5($action.$random.$signPostfix));
	if($sign != $sign2) output(3003, lang('verification_failed'));
}

/**
 * 调试
 */
function debug($value, $type=1, $rr=0) {
	if(is_array($value) || is_object($value)) {
		if($rr) {
			return print_r($value, true);
		} else {
			echo "<pre>";
			print_r($value);
		}
	} else {
		echo $value;
	}

	if($type) {
		exit;
	}
}

/**
 * 接口内容输出
 */
function output($result = 0, $value='') {
	exit(json_encode(array('state'=>$result, 'msg'=>$value)));
}

/**
 * 单词统计，去除标点
 */
function wordCount($str = ""){
	$chinese_pattern = "/[\x{4e00}-\x{9fff}\x{f900}-\x{faff}]/u";
    $str = preg_replace("/[\x{ff00}-\x{ffef}\x{2000}-\x{206F}]/u", "", $str);
    return preg_match_all($chinese_pattern, $str, $match) + str_word_count(preg_replace($chinese_pattern, "", $str));
}


function getCover($v) {
	if($v['cover']) {
		return 'http://'.$_SERVER['HTTP_HOST'].'/data/books/'.$v['cover'];
	} else if($v['covered'] > 0) {
		return 'http://'.$_SERVER['HTTP_HOST'].'/data/books/cover'.$v['covered'].'.jpg';
	}
}
