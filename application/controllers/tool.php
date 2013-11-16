<?php

/**
 * @deprecated 工具类
 * @see Tool
 * @version 1.0.0 (Thu Feb 23 13:49:18 GMT 2012)
 * @author ZhangHao
 */
class Tool extends CI_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->model('base_mdl', 'base');
    }

    /* xhEditor编辑器的图片上传组件 */
    function upload() {
		if ($_FILES['imgFile']['size'] > 0) {
			$fileType = array('image', 'flash', 'media', 'file');
			$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
			$dir_name = 'data/uploads/editor/'.$dir_name.'/'.date('Y/m/');
			createFolder($dir_name);

			$config_pic['upload_path'] = $dir_name;
			$config_pic['allowed_types'] = 'png|jpg|bmp|gif|jpeg|swf|flv|doc|docx|xls|xlsx|ppt|txt|zip|rar|gz|bz2|pdf';
			$config_pic['max_size'] = '10240';
			$config_pic['encrypt_name'] = TRUE;
			$config_pic['overwrite'] = FALSE;

			$this->load->library('upload', $config_pic);

			if (!$this->upload->do_upload('imgFile')) {
				write_log($this->upload->display_errors());
				exit(json_encode(array('error' => 1, 'message' => $this->upload->display_errors())));
			}
			$pic_data = $this->upload->data();
			exit(json_encode(array('error' => 0, 'url' => base_url($dir_name.$pic_data['file_name']))));
		} else {
			exit(json_encode(array('error' => 1, 'message' => '文件大小不能为0')));
		}
    }

    /* 支持html5的xhEditor编辑器的图片上传组件 */
    function xhPicUpload5() {
		$filePath = './data/uploads/editor';
		$upExt = 'jpg|jpeg|gif|png';      //上传扩展名
		$maxAttachSize = 2097152;       //最大上传大小，默认是2M
		$err = $url = $localname = '';
		$tempPath = $filePath . '/' . date("YmdHis") . mt_rand(10000, 99999) . '.tmp';

		if (isset($_SERVER['HTTP_CONTENT_DISPOSITION']) && preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i', $_SERVER['HTTP_CONTENT_DISPOSITION'], $info)) {
			//HTML5上传
			file_put_contents($tempPath, file_get_contents("php://input"));
			$localName = $info[2];
		} else {
			//标准表单式上传
			$upfile = $_FILES['filedata'];

			if (!isset($upfile)) {
			$err = '文件域名称错误';
			} elseif (!empty($upfile['error'])) {
			switch ($upfile['error']) {
				case '1':
				$err = '文件大小超过了服务端的最大值';
				break;
				case '2':
				$err = '文件大小超过了客户端的最大值';
				break;
				case '3':
				$err = '文件上传不完全';
				break;
				case '4':
				$err = '无文件上传';
				break;
				case '6':
				$err = '缺少临时文件夹';
				break;
				case '7':
				$err = '写文件失败';
				break;
				case '8':
				$err = '上传被其它扩展中断';
				break;
				case '999':
				default:
				$err = '未知错误';
			}
			} elseif (empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none') {
			$err = '无文件上传';
			} else {
			move_uploaded_file($upfile['tmp_name'], $tempPath);
			$localName = $upfile['name'];
			}
		}

		if ($err == '') {
			$fileInfo = pathinfo($localName);
			$extension = $fileInfo['extension'];
			if (preg_match('/' . $upExt . '/i', $extension)) {
			$bytes = filesize($tempPath);
			if ($bytes > $maxAttachSize) {
				$err = '请不要上传大小超过' . formatBytes($maxAttachSize) . '的文件';
			} else {
				PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);
				$newFilename = date("YmdHis") . mt_rand(1000, 9999) . '.' . $extension;
				$targetPath = $filePath . '/' . $newFilename;

				rename($tempPath, $targetPath);
				@chmod($targetPath, 0755);
			}
			} else {
			$err = '上传文件扩展名必需为：' . $upExt;
			}

			@unlink($tempPath);
		}
		$resultArr = array(
			'err' => $err,
			'msg' => '!' . BASEURL . '/' . $targetPath
		);
		echo json_encode($resultArr);
    }

    function formatBytes($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
		} elseif ($bytes >= 1048576) {
			$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
		} elseif ($bytes >= 1024) {
			$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
		} else {
			$bytes = $bytes . 'Bytes';
		}
		return $bytes;
    }
}