<?php
/**
 * User: derekzhangv
 * Time: 2018/5/29 16:38
 */
namespace zhangv\wechat\apiclient\cache;

interface CacheProvider{
	function set($key,$value,$expireAt = null);
	function get($key);
	function clear($key);
}