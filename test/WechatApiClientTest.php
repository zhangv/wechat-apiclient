<?php
/**
 * User: derekzhangv
 * Time: 2018/5/31 23:17
 */

use zhangv\wechat\apiclient\WechatApiClient;
use PHPUnit\Framework\TestCase;

class WechatApiClientTest extends TestCase{
	/** @var WechatApiClient */
	private $api = null;
	public function setUp(){
		$config = [];
		$this->api = new WechatApiClient($config);
	}

	/** @test */
	public function getAccessToken(){
		$token = $this->api->getAccessToken();
	}

}
