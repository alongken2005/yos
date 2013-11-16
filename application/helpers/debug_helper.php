<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

function output($result = 0, $value='') {
	exit(json_encode(array('state'=>$result, 'msg'=>$value)));
}
?>