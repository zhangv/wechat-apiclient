<?php

namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatAppApiClient;

/**
 * Class WxaCode
 * @package zhangv\wechat\apiclient\applet
 * @author zhangv
 * @license MIT
 * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.createQRCode.html
 */
class WxaCode extends WechatAppApiClient {


	public function createQRCode($path, $width = 430){
		$url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode";
		$params = ['path' => $path,'width' => $width];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE),true);
	}

	public function getCode($path,$width = 430,$auto_color = false,$line_color = ['r'=>0,'g'=>0,'b' => 0], $is_hyaline = false){
		$url = "https://api.weixin.qq.com/wxa/getwxacode";
		$params = ['path' => $path,'width' => $width,'auto_color' => $auto_color, 'line_color' => $line_color, 'is_hyaline' => $is_hyaline];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE),true);
	}

	public function getUnlimited($scene,$page,$width = 430,$auto_color = false,$line_color =  ['r'=>0,'g'=>0,'b' => 0], $is_hyaline = false){
		$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit";
		$params = ['scene' => $scene, 'page' => $page,'width' => $width,'auto_color' => $auto_color, 'line_color' => $line_color, 'is_hyaline' => $is_hyaline];
		error_log(print_r($params,true));
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE),true);
	}

}