<?php

namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatApiClient;
class Security extends WechatApiClient {
	
	/**
	 * 文本安全内容检测接口
	 * @param $content
	 * @return mixed
	 */
	public function msgSecCheck($content){
		$url = "https://api.weixin.qq.com/wxa/msg_sec_check";
		$params = ['content' => $content];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 图片安全内容检测接口
	 * @param $imgpath
	 * @return mixed
	 */
	public function imgSecCheck($imgpath){
		$url = "https://api.weixin.qq.com/wxa/img_sec_check";
		$file = realpath($imgpath); //要上传的文件
		$params['media'] = new CURLFile($file);
		return $this->post($url,$params);
	}

	public function mediaCheckAsync($imgpath){
//TODO
	}

}