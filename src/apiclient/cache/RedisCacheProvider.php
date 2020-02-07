<?php
use zhangv\wechat\apiclient\cache\CacheProvider;

class RedisCacheProvider implements CacheProvider{
	/** @var Redis */
	private static $instance;
	private $host,$port,$db,$timeout = 1;

	public function __construct($host, $port = 6369, $db = 1, $timeout = 1){
		$this->host = $host;
		$this->port = $port;
		$this->db = $db;
		$this->timeout = $timeout;
		if(!self::$instance) self::$instance = $this->getInstance();
	}

	private function getInstance() {
		if(!class_exists('Redis')) throw new Exception("PHPRedis is required, please add 'ext-redis:*' in composer.json");
		$instance = new Redis();
		try {
			$instance->pconnect($this->host,$this->port, 2); //timeout by second
			$instance->select($this->db);
		} catch (Exception $exc) {
			throw $exc;
		}
		return $instance;
	}

	public function set($key,$jsonobj,$expireAt = null){
		$ttl = $expireAt - time();
		$jsonobj->expires_at = $expireAt;
		self::$instance->setex($key, $ttl, json_encode($jsonobj));
	}

	public function get($key){
		return self::$instance->get($key);
	}

	public function clear($key){
		self::$instance->delete($key);
	}
}