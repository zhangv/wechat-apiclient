<?php
/**
 * @license MIT
 * @author zhangv
 */
namespace zhangv\wechat\apiclient\util;

class HttpClient{

	private $instance = null;
	private $errNo = null;
	private $error = null;
	private $info = null;
	private $timeout = 1;

	public function __construct($timeout = 1) {
		$this->timeout = $timeout;
		$this->initInstance();
	}

	public function initInstance(){
		if(!$this->instance) {
			$this->instance = curl_init();
			curl_setopt($this->instance, CURLOPT_TIMEOUT, intval($this->timeout));
			curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->instance, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($this->instance, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($this->instance,CURLOPT_BINARYTRANSFER,true);
		}
	}

	public function get($url,$params = array(),$headers = array(),$opts = array()) {
		$this->initInstance();
		if($params && count($params) > 0) $url .= '?' . http_build_query($params);
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_HTTPGET, true);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		curl_setopt_array($this->instance,$opts);
		$result = $this->execute();
		return $result;
	}

	public function post($url, $params = array(),$headers = array(),$opts = array()) {
		$this->initInstance();
		curl_setopt($this->instance, CURLOPT_URL, $url);
		curl_setopt($this->instance, CURLOPT_POST, true);
		curl_setopt($this->instance, CURLOPT_POSTFIELDS, $params);
		curl_setopt($this->instance, CURLOPT_HTTPHEADER, $headers);
		curl_setopt_array($this->instance,$opts);
		$result = $this->execute();
		return $result;
	}

	private function execute() {
		$result = curl_exec($this->instance);
		$this->errNo = curl_errno($this->instance);
		$this->info = curl_getinfo($this->instance);
		$this->error = curl_error($this->instance);
		return $result;
	}

	public function getInfo(){
		return $this->info;
	}

	public function getError(){
		return $this->error;
	}
}