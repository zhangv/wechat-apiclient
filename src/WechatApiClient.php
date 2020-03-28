<?php

namespace zhangv\wechat;
use \Exception;
use zhangv\wechat\apiclient\cache\CacheProvider;
use zhangv\wechat\apiclient\cache\JsonFileCacheProvider;
use zhangv\wechat\apiclient\util\HttpClient;


/**
 * Class WechatApiClient
 * @package zhangv\wechat
 * @author zhangv
 * @license MIT
 *
 * @method static apiclient\officialaccount\Media       Media(array $config)
 * @method static apiclient\officialaccount\Menu     Menu(array $config)
 * @method static apiclient\officialaccount\Message     Message(array $config)
 * @method static apiclient\officialaccount\QRcode      QRcode(array $config)
 * @method static apiclient\officialaccount\BlackList    BlackList(array $config)
 * @method static apiclient\officialaccount\Comment     Comment(array $config)
 * @method static apiclient\officialaccount\Member    Member(array $config)
 * @method static apiclient\officialaccount\UserInfo   UserInfo(array $config)
 */
class WechatApiClient {

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
	const DEFAULT_TIMEOUT = 4;

	private $https = false;

	public function __construct($conf){
		$this->config = $conf;
		$timeout = (empty($conf['timeout']))?self::DEFAULT_TIMEOUT:$conf['timeout'];
		$this->httpClient = new HttpClient($timeout);
		//the menu create require more than 1 second
		//https://api.weixin.qq.com/card/membercard/activatetempinfo/get not get info within 3-4 seconds
		$this->cacheProvider = new JsonFileCacheProvider();
	}

	/**
	 * @param string $name
	 * @param array  $config
	 *
	 * @return mixed
	 */
	public static function __callStatic($name, $config) {
		return self::load($name, ...$config);
	}

	/**
	 * @param string $name
	 * @param string $config
	 * @return mixed
	 */
	private static function load($name, $config) {
		$service = __NAMESPACE__ . "\\apiclient\\officialaccount\\{$name}";
		return new $service($config);
	}

	public function setHttpClient($httpClient){
		$this->httpClient = $httpClient;
	}

	public function setCacheProvider($cacheProvider){
		$this->cacheProvider = $cacheProvider;
	}

	public function setHttps($https){
		$this->https = $https;
	}

	public function getCacheProvider(){
		return $this->cacheProvider;
	}

	public function getAccessToken($refresh = false){
		$key = self::CACHEKEY_ACCESSTOKEN;
		$accesstoken = null;
		if($this->cacheProvider){
			$cached = $this->cacheProvider->get($key);
			if($cached) {
				$accesstoken = $cached->access_token;
			}
		}

		if(!$accesstoken || $refresh === true){
			$appid = $this->config['appid'];
			$appsecret = $this->config['appsecret'];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$r =$this->get($url,false,false);
			if($r){
				if(!empty($r->access_token)){
					$accesstoken = $r->access_token;
					if($this->cacheProvider) {
						$expires_at = time() + $r->expires_in;
						$this->cacheProvider->set($key, $r, $expires_at);
					}
				}else{
					throw new Exception("Access token missing in ".print_r(json_encode($r),true));
				}
			}else{
				throw new Exception("Not a valid json: ".print_r(json_encode($r),true));
			}
		}

		return $accesstoken;
	}

	public function getOauthAccessToken($code){
		$appid = $this->config['appid'];
		$appsecret = $this->config['appsecret'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
		return $this->get($url);
	}

	public function isOauthAccessTokenValid($accesstoken,$openid){
		$url = "https://api.weixin.qq.com/sns/auth?access_token=$accesstoken&openid=$openid";
		return $this->get($url);
	}

	/**
	 * 长链接转短链接
	 * @param $url
	 * @param $action
	 * @return mixed
	 */
	public function shortUrl($url,$action = 'long2short'){
		$params = ['action' => $action,'long_url'=>$url];
		return $this->post("https://api.weixin.qq.com/cgi-bin/shorturl",json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	protected function get($url,$raw = false,$tokenrequired = true){
		if($tokenrequired === true){
			$components = parse_url($url);
			$arr = [];
			if(!empty($components['query'])){
				parse_str($components['query'],$arr);
			}
			if(empty($arr['access_token'])){
				$at = $this->getAccessToken();
				$arr['access_token'] = $at;
			}
			$url = $components['scheme'].'://'.$components['host'].$components['path']."?".http_build_query($arr);
		}
		$result = $this->httpClient->get($url);
		if(!$result){
			if($this->httpClient->getError() !== ''){
				throw new Exception($this->httpClient->getError());
			}else
				throw new Exception("Null result, with URL:[$url]");
		}

		$json = json_decode($result);
		if(!empty($json->errcode) && $json->errcode === 40001){//try again and update the cached accesstoken
			$atnew = $this->getAccessToken(true);
			$querydata['access_token'] = $atnew;
			$result = $this->httpClient->get($url);
		}

		if($raw === true){
			return $result;
		}

		try{
			$json = $this->processResult($result);
		}catch (Exception $e){
			error_log($url);
			error_log(print_r($e,true));
			throw $e;
		}
		return $json;
	}

	protected function post($url, $params, $raw = false, $querydata = []) {
		$at = $this->getAccessToken();
		$querydata['access_token'] = $at;
		$url2 = $url . "?".http_build_query($querydata);
		$result = $this->httpClient->post($url2,$params);
		if(!$result){
			if($this->httpClient->getError() !== ''){
				throw new Exception($this->httpClient->getError());
			}else{
				$paramstr = print_r($params,true);
				throw new Exception("Null result, with URL:[$url2],POSTFIELDS = [$paramstr]");
			}
		}

		$json = json_decode($result);
		if(!empty($json->errcode) && $json->errcode === 40001){//try again and update the cached accesstoken
			$atnew = $this->getAccessToken(true);
			$querydata['access_token'] = $atnew;
			$url2 = $url . "?".http_build_query($querydata);
			$result = $this->httpClient->post($url2,$params);
		}
		if($raw === true) return $result;
		$json = $this->processResult($result);
		return $json;
	}

	private function processResult($result){
		$json = json_decode($result);

		if($json === null){
			throw new Exception("Bad formatted JSON - {$result}");
		}
		if(!empty($json->errcode) && $json->errcode !== 0){// invalid credential, access_token is invalid or not latest
			throw new Exception("[{$json->errcode}]{$json->errmsg}");
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
			if($cached){
				$ticket = $cached->ticket;
			}
		}
		if(!$ticket){
			if(!$accessToken) $accessToken = $this->getAccessToken();
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type={$type}&access_token=$accessToken";
			$r=$this->get($url);
			if($r){
				if(!empty($r->ticket)){
					$ticket = $r->ticket;
					if($this->cacheProvider) {
						$expires_at = time() + $r->expires_in;
						$this->cacheProvider->set($key, $r, $expires_at);
					}
				}else{
					throw new Exception("Ticket missing in: ".print_r(json_encode($r),true));
				}
			}else{
				throw new Exception("Not a valid json: ".print_r(json_encode($r),true));
			}
		}
		return $ticket;
	}

	public function getSignPackageByURL($url = null){
		$jsapiTicket = $this->getTicket();
		if(!$url){
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}
		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId" => $this->config['appid'],
			"nonceStr" => $nonceStr,
			"timestamp" => $timestamp,
			"url" => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}

	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

}