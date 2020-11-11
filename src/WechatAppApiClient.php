<?php

namespace zhangv\wechat;
use \Exception;
use zhangv\wechat\apiclient\applet\PluginManager;
use zhangv\wechat\apiclient\cache\CacheProvider;
use zhangv\wechat\apiclient\cache\JsonFileCacheProvider;
use zhangv\wechat\apiclient\util\Crypto;
use zhangv\wechat\apiclient\util\HttpClient;

/**
 * Class WechatAppApiClient
 * @package zhangv\wechat
 * @author zhangv
 * @license MIT
 * @method static apiclient\applet\Analysis     Analysis(array $config)
 * @method static apiclient\applet\Auth      Auth(array $config)
 * @method static apiclient\applet\CustomerServiceMessage    CustomerServiceMessage(array $config)
 * @method static apiclient\applet\NearbyPOI     NearbyPOI(array $config)
 * @method static apiclient\applet\OCR    OCR(array $config)
 * @method static apiclient\applet\PluginManager   PluginManager(array $config)
 * @method static apiclient\applet\Security   Security(array $config)
 * @method static apiclient\applet\Soter   Soter(array $config)
 * @method static apiclient\applet\TemplateMessage   TemplateMessage(array $config)
 * @method static apiclient\applet\UniformMessage   UniformMessage(array $config)
 * @method static apiclient\applet\UpdatableMessage   UpdatableMessage(array $config)
 * @method static apiclient\applet\WxaCode   WxaCode(array $config)
 */
class WechatAppApiClient {
	private $sessionKey;
	const CACHEKEY_ACCESSTOKEN = "wechatappapiclient:access_token";
	public $config = [];
	/** @var  CacheProvider */
	private $cacheProvider;
	/** @var  HttpClient */
	private $httpClient;
	private $https = false;

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
		$service = __NAMESPACE__ . "\\apiclient\\applet\\{$name}";
		return new $service($config);
	}


	public function __construct($conf){
		$this->config = $conf;
		$this->httpClient = new HttpClient(2);
		$this->cacheProvider = new JsonFileCacheProvider();
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
			if($cached && $cached = json_decode($cached)) {
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
		if(!empty($json->errcode) &&
			($json->errcode === 40001 || $json->errcode == '40001')){//try again and update the cached accesstoken
			$atnew = $this->getAccessToken(true);
			$querydata['access_token'] = $atnew;
			$url2 = $url . "?".http_build_query($querydata);
			$result = $this->httpClient->get($url2);
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
		if(!$json){
			return $result;
		}else{
			if(!empty($json->errcode)){
				if($json->errcode === 40001){//try again and update the cached accesstoken
					$atnew = $this->getAccessToken(true);
					$querydata['access_token'] = $atnew;
					$url2 = $url . "?".http_build_query($querydata);
					$result = $this->httpClient->post($url2,$params);
				}else{
					throw new Exception($json->errmsg);
				}
			}

			if($raw === true) return $result;
		}


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

	public function decryptData($sessionKey, $encryptedData, $iv){
		return (new Crypto())->decryptData($sessionKey,$encryptedData,$iv);
	}
	public function decryptData0($sessionKey, $encryptedData, $iv){
		$this->sessionKey = $sessionKey;
		$aesKey=base64_decode($this->sessionKey);


		$aesIV=base64_decode($iv);
		try {
			$decrypted = openssl_decrypt(base64_decode($encryptedData), 'aes-128-cbc', $aesKey, OPENSSL_RAW_DATA, $aesIV);
			error_log('&&&$decrypted');
			error_log($decrypted);
			error_log('&&&');
		} catch (Exception $e) {
			throw $e;
		}
		try {
			//去除补位字符
			$result = $this->decode($decrypted);
			error_log('&&&$result');
			error_log($result);
			error_log('&&&');
		} catch (Exception $e) {
			throw $e;
		}
		return $result;
	}

	/**
	 * 对需要加密的明文进行填充补位
	 * @param $text 需要进行填充补位操作的明文
	 * @return 补齐明文字符串
	 */
	function encode( $text ) {
		$block_size = 16;
		$text_length = strlen( $text );
		//计算需要填充的位数
		$amount_to_pad = $block_size - ( $text_length % $block_size);
		if ( $amount_to_pad == 0 ) {
			$amount_to_pad = $block_size;
		}
		//获得补位所用的字符
		$pad_chr = chr( $amount_to_pad );
		$tmp = "";
		for ( $index = 0; $index < $amount_to_pad; $index++ ) {
			$tmp .= $pad_chr;
		}
		return $text . $tmp;
	}

	/**
	 * 对解密后的明文进行补位删除
	 * @param decrypted 解密后的明文
	 * @return 删除填充补位后的明文
	 */
	function decode($text) {
		$pad = ord(substr($text, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		return substr($text, 0, (strlen($text) - $pad));
	}
}