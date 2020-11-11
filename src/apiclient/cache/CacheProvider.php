<?php
/**
 * User: derekzhangv
 * Time: 2018/5/29 16:38
 */
namespace zhangv\wechat\apiclient\cache;

interface CacheProvider{
	/**
	 * @param $key string
	 * @param $value string
	 * @param $expireAt integer|null
	 */
	function set($key,$value,$expireAt = null);

	/**
	 * @param $key string
	 * @return Object
	 */
	function get($key);

	/**
	 * @param $key
	 */
	function clear($key);
}