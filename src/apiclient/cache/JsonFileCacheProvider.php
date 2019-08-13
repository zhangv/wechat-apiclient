<?php
/**
 * User: derekzhangv
 * Time: 2018/5/29 16:41
 */
namespace zhangv\wechat\apiclient\cache;
class JsonFileCacheProvider implements CacheProvider{
	private $cacheDir = null;

	public function __construct($cacheDir = null){
		if(!$cacheDir) {
			$this->cacheDir = __DIR__;
			if(!is_writable($this->cacheDir)){
				$this->cacheDir = '/tmp';
			}
		}
		else $this->cacheDir = $cacheDir;
	}

	public function set($key,$jsonobj,$expireAt = null){
		$data = $jsonobj;
		$data->expires_at = $expireAt;
		$file = "{$this->cacheDir}/{$key}.json";
		if($fp = @fopen($file, "w")){
			fwrite($fp, json_encode($data));
			fclose($fp);
		}
	}

	public function get($key){
		$file = "{$this->cacheDir}/{$key}.json";
		$cache = null;
		if(file_exists($file)){
			$cache = json_decode(file_get_contents($file));
			if(!$cache || $cache->expires_at < time()){
				$cache = null;
				$this->clear($key);
			}
		}
		return $cache;
	}

	public function clear($key){
		$file = "{$this->cacheDir}/{$key}.json";
		if (file_exists($file)) {
			unlink($file);
		}
	}
}