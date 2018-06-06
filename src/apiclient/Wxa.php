<?php

namespace zhangv\wechat\apiclient;

trait Wxa{
	
	/**
	 * 文本安全内容检测接口
	 * @param $content
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function msgSecCheck($content,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/wxa/msg_sec_check?access_token=$accesstoken";
		$params = ['content' => $content];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 图片安全内容检测接口
	 * @param $imgpath
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function imgSecCheck($imgpath,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/wxa/img_sec_check?access_token=$accesstoken";
		$file = realpath($imgpath); //要上传的文件
		$params['media'] = new CURLFile($file);
		return $this->post($url,$params);
	}

}