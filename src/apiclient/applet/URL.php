<?php

namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatAppApiClient;

/**
 * Class URL
 * @package zhangv\wechat\apiclient\applet
 * @author zhangv
 * @license MIT
 * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/url-scheme/urlscheme.generate.html
 */
class URL extends WechatAppApiClient {

	public function generateScheme($jumpWxa,$isExpire = false,$expireType = 0, $expireTime = null, $expireInterval = null){
		$url = "https://api.weixin.qq.com/wxa/generatescheme";
		$params = ['jump_wxa' => $jumpWxa, 'is_expire' => $isExpire,
//			'expire_type' => $expireType,'expire_time' => $expireTime, 'expire_interval' => $expireInterval
		];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}
	public function generateLink($path,$query,$isExpire = false,$expireType = 0, $expireTime = null, $expireInterval = null){
		$url = "https://api.weixin.qq.com/wxa/generate_urllink";
		$params = ['path' => $path, 'query' => $query, 'is_expire' => $isExpire,
//			'expire_type' => $expireType,'expire_time' => $expireTime, 'expire_interval' => $expireInterval
		];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	public function generateShortLink($pageUrl,$pageTitle,$isPermanent = false){
		$url = "https://api.weixin.qq.com/wxa/genwxashortlink";
		$params = ['page_url' => $pageUrl, 'page_title' => $pageTitle, 'is_permanent' => $isPermanent];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

}