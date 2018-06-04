<?php

namespace zhangv\wechat;
use \Exception;
use zhangv\wechat\apiclient\cache\CacheProvider;
use zhangv\wechat\apiclient\cache\JsonFileCacheProvider;
use zhangv\wechat\apiclient\util\HttpClient;

class WechatApiClient {

	use apiclient\Media;
	use apiclient\Menu;
	use apiclient\Message;
	use apiclient\QRcode;
	use apiclient\BlackList;
	use apiclient\Comment;
	use apiclient\UserInfo;
	use apiclient\Member;

	const MSGTYPE_MPNEWS = 'mpnews',MSGTYPE_TEXT = 'text',MSGTYPE_MPVIDEO = 'mpvideo',MSGTYPE_VOICE = 'voice',MSGTYPE_IMAGE = 'image',MSGTYPE_WXCARD = 'wxcard';
	const MEDIATYPE_IMAGE = 'image',MEDIATYPE_VOICE = 'voice',MEDIATYPE_VIDEO = 'video',MEDIATYPE_THUMB = 'thumb';
	const QRTYPE_TEMP = 'QR_SCENE',QRTYPE_FOREVER = 'QR_LIMIT_SCENE',QRTYPE_FOREVER_STR = 'QR_LIMIT_STR_SCENE';
	const COMMENTTYPE_ALL = 0, COMMENTTYPE_NORMAL = 1, COMMENTTYPE_ELECTED = 2;
	public static $MENUITEMTYPES = [
		'click' => '点击推事件','view' => 'url跳转','scancode_push' => '扫码推事件','scancode_waitmsg' => '扫码推事件且弹出“消息接收中”',
		'pic_sysphoto' => '弹出系统拍照发图','pic_photo_or_album' => '弹出拍照或者相册发图','pic_weixin' => '弹出微信相册发图器',
		'location_select' => '弹出地理位置选择器','media_id' => '下发多媒体消息','view_limited' => '跳转图文消息URL','miniprogram' => '小程序'
	];
	const SIGNTYPE_MD5 = 'MD5', SIGNTYPE_HMACSHA256 = 'HMAC-SHA256', SIGNTYPE_SHA1 = 'SHA1';
	const TICKETTYPE_JSAPI = 'jsapi',TICKETTYPE_WXCARD = 'wx_card';
	const CACHEKEY_ACCESSTOKEN = "wechatapiclient:access_token", CACHEKEY_TICKET = "wechatapiclient:ticket";

	public $config = [];
	/** @var  CacheProvider */
	private $cacheProvider;
	/** @var  HttpClient */
	private $httpClient;

	public function __construct($conf){
		$this->config = $conf;
		$this->cacheProvider = new JsonFileCacheProvider();
	}

	public function setHttpClient($httpClient){
		$this->httpClient = $httpClient;
	}

	public function setCacheProvider($cacheProvider){
		$this->cacheProvider = $cacheProvider;
	}

	public function getCacheProvider(){
		return $this->cacheProvider;
	}

	public function isAccessTokenExpired($act){//accesstoken是否已经过期
		$expired = false;
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$act}";
		$output=$this->get($url);
		$r = json_decode($output);
		if(!empty($r->errcode) && $r->errcode == '42001'){
			$expired = true;
		}
		return $expired;
	}

	public function getAccessToken(){
		$key = self::CACHEKEY_ACCESSTOKEN;
		$accesstoken = null;
		if($this->cacheProvider){
			$cached = $this->cacheProvider->get($key);
			if($cached && $cached = json_decode($cached)) {
				$accesstoken = $cached->access_token;
			}
		}

		if(!$accesstoken){
			$appid = $this->config['appid'];
			$appsecret = $this->config['appsecret'];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$output=$this->get($url);
			$r = json_decode($output);
			if($r){
				if(!empty($r->access_token)){
					$accesstoken = $r->access_token;
					if($this->cacheProvider) {
						$expires_at = time() + $r->expires_in;
						$this->cacheProvider->set($key, json_encode($r), $expires_at);
					}
				}else{
					throw new Exception("Access token missing in ".print_r($output,true));
				}
			}else{
				throw new Exception("Not a valid json: ".print_r($output,true));
			}
		}

		return $accesstoken;
	}

	public function getOauthAccessToken($code){
		$appid = $this->config['appid'];
		$appsecret = $this->config['appsecret'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
		$output=$this->get($url);
		$r = json_decode($output);
		return $r;
	}

	public function isOauthAccessTokenValid($accesstoken,$openid){
		$url = "https://api.weixin.qq.com/sns/auth?access_token=$accesstoken&openid=$openid";
		$output = $this->get($url);
		return json_decode($output);
	}

	/**
	 * 长链接转短链接
	 * @param $url
	 * @param $action
	 * @return mixed
	 */
	public function shortUrl($url,$action = 'long2short',$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['action' => $action,'long_url'=>$url];
		$r = $this->post("https://api.weixin.qq.com/cgi-bin/shorturl?access_token=$accesstoken",json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	private function get($url){
		return $this->httpClient->get($url);
	}
	private function post($url, $params) {
		$result = $this->httpClient->post($url,$params);
		$json = json_decode($result);

		if(!empty($json->errcode)){// invalid credential, access_token is invalid or not latest
			throw new Exception($json->errcode);
		}
		return $json;
	}

	public function sign($data,$sign_type = self::SIGNTYPE_MD5) {
		ksort($data);
		$string1 = "";
		foreach ($data as $k => $v) {
			if ($v && trim($v)!='') {
				$string1 .= "$k=$v&";
			}
		}
		$stringSignTemp = $string1 . "key=" . $this->config["api_key"];
		if($sign_type == self::SIGNTYPE_MD5){
			$sign = strtoupper(md5($stringSignTemp));
		}elseif($sign_type == self::SIGNTYPE_HMACSHA256){
			$sign = strtoupper(hash_hmac('sha256',$stringSignTemp,$this->config["api_key"]));
		}elseif($sign_type == self::SIGNTYPE_SHA1){
			$sign = sha1($stringSignTemp);
		}else throw new Exception("Not supported sign type - $sign_type");
		return $sign;
	}


	public function getTicket($type = self::TICKETTYPE_JSAPI, $accessToken = null){
		$ticket = null;
		$key = "wechatapiclient:ticket:{$type}";
		if($this->cacheProvider){
			$cached = $this->cacheProvider->get($key);
			if($cached && $cached = json_decode($cached)){
				$ticket = $cached->ticket;
			}
		}
		if(!$ticket){
			if(!$accessToken) $accessToken = $this->getAccessToken();
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type={$type}&access_token=$accessToken";
			$output=$this->get($url);
			$r = json_decode($output);
			if($r){
				if(!empty($r->ticket)){
					$ticket = $r->ticket;
					if($this->cacheProvider) {
						$expires_at = time() + $r->expires_in;
						$this->cacheProvider->set($key, json_encode($r), $expires_at);
					}
				}else{
					throw new Exception("Ticket missing in: ".print_r($output,true));
				}
			}else{
				throw new Exception("Not a valid json: ".print_r($output,true));
			}
		}
		return $ticket;
	}

}