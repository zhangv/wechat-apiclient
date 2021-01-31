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

	public function set($key,$json,$expireAt = null){
		$data = $json;
		if(is_string($json)){
			$data = json_decode($json);
		}elseif(is_object($json)){
			$data = $json;
		}

		if(!$data) {
			var_dump(debug_backtrace());die;
		}else{
			$data->expires_at = $expireAt;
			$file = "{$this->cacheDir}/{$key}.json";
			if($fp = @fopen($file, "w")){
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		}
	}

	public function get($key){
		$file = "{$this->cacheDir}/{$key}.json";
		$cache = null;
		$raw = null;
		if(file_exists($file)){
			$raw = file_get_contents($file);
			$cache = json_decode($raw);
			if(!$cache) { //corrupted json format
				$this->clear($key);
			}else{
				if($cache->expires_at < time()){
					$cache = null;
					$this->clear($key);
				}
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