<?php

namespace zhangv\wechat\apiclient;

use  zhangv\wechat\WechatApiClient;

trait QRcode{

	/**
	 * 获取二维码ticket
	 * @param $type string 类型
	 * @param $sceneid integer/string 场景ID或字符串
	 * @param $expireseconds int 过期时间
	 * @return mixed
	 * @throws Exception
	 */
	public function createQRcode($type,$sceneid,$expireseconds = 1800){
		$params = ['action_name' => $type];
		if($type == WechatApiClient::QRTYPE_TEMP) {
			$params['expire_seconds'] = $expireseconds;
			$params['action_info']['scene']['scene_id'] = $sceneid;
		}elseif($type == WechatApiClient::QRTYPE_FOREVER){
			if($sceneid > 100000) throw new \Exception('sceneid cannot beyond 100000 when QR_LIMIT_SCENE');
			$params['action_info']['scene']['scene_id'] = $sceneid;
		}elseif($type == WechatApiClient::QRTYPE_FOREVER_STR){
			$params['action_info']['scene']['scene_str'] = $sceneid;
		}
		return $this->post2("https://api.weixin.qq.com/cgi-bin/qrcode/create",json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 根据ticket获取二维码图片
	 * @param $ticket string
	 * @return mixed
	 */
	public function showQRcode($ticket){
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
		return $this->get($url,true);
	}

}