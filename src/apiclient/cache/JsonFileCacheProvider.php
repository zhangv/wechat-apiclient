<?php
/**
 * User: derekzhangv
 * Time: 2018/5/29 16:41
 */
namespace zhangv\wechat\apiclient\cache;
class JsonFileCacheProvider implements CacheProvider{
	private $cacheDir = null;

	public function __construct($cacheDir = null){
		if(!$cacheDir) $this->cacheDir = __DIR__;
		else $this->cacheDir = $cacheDir;
	}

	public function set($key,$json,$expireAt = null){
		$data = json_decode($json);
		$data->expires_at = $expireAt;
		$file = "{$this->cacheDir}/{$key}.json";
		$fp = fopen($file, "w");
		fwrite($fp, json_encode($data));
		if ($fp) fclose($fp);
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
		return $cache?json_encode($cache):null;
	}

	public function clear($key){
		$file = "{$this->cacheDir}/{$key}.json";
		if (file_exists($file)) {
			unlink($file);
		}
	}
}