<?php

use zhangv\wechat\WechatApiClient;
use zhangv\wechat\apiclient\util\HttpClient;
use zhangv\wechat\apiclient\cache\JsonFileCacheProvider;
use PHPUnit\Framework\TestCase;

class WechatApiClientTest extends TestCase{
	/** @var WechatApiClient */
	private $api = null;
	/** @var \PHPUnit\Framework\MockObject\MockObject */
	private $httpClient = null;
	public function setUp(){
		$config = ['appid'=>'appid','appsecret'=>'secret'];
		$this->api = new WechatApiClient($config);
		$this->httpClient = $this->createMock(HttpClient::class);
		$cacheProvider = new JsonFileCacheProvider(__DIR__);
		$this->api->setCacheProvider($cacheProvider);
	}

	/** @test */
	public function getAccessToken(){
		$this->httpClient->method('get')->willReturn(
			'{"access_token":"TOKEN","expires_in":7200}');
		$this->api->setHttpClient($this->httpClient);
		$token = $this->api->getAccessToken();
		$this->assertEquals('TOKEN',$token);

		//remock to overwrite the previous method mocking setup
		$this->httpClient = $this->createMock(HttpClient::class);
		$this->httpClient
			->method('get')
			->willReturn(
			'{"access_token":"TOKEN-2","expires_in":7200}');
		$this->api->setHttpClient($this->httpClient);
		$token = $this->api->getAccessToken();
		$this->assertEquals('TOKEN',$token);
		//clear cache
		$this->api->getCacheProvider()->clear(WechatApiClient::CACHEKEY_ACCESSTOKEN);

		$token = $this->api->getAccessToken();
		$this->assertEquals('TOKEN-2',$token);

		$this->api->getCacheProvider()->clear(WechatApiClient::CACHEKEY_ACCESSTOKEN);
	}

	/** @test */
	public function getTicket(){
		$this->httpClient->method('get')->willReturn(
			'{
			 "errcode":0, 
			 "errmsg":"ok", 
			 "ticket":"TICKET", 
			 "expires_in":7200 
			}');
		$this->api->setHttpClient($this->httpClient);
		$token = $this->api->getTicket(WechatApiClient::TICKETTYPE_JSAPI,'fakeaccesstoken');
		$this->assertEquals('TICKET',$token);

		//remock to overwrite the previous method mocking setup
		$this->httpClient = $this->createMock(HttpClient::class);
		$this->httpClient
			->method('get')
			->willReturn(
				'{
				 "errcode":0, 
				 "errmsg":"ok", 
				 "ticket":"TICKET2", 
				 "expires_in":7200 
				}');
		$this->api->setHttpClient($this->httpClient);
		$token = $this->api->getTicket(WechatApiClient::TICKETTYPE_JSAPI,'fakeaccesstoken');
		$this->assertEquals('TICKET',$token);
		//clear cache
		$this->api->getCacheProvider()->clear(WechatApiClient::CACHEKEY_TICKET.':jsapi');

		$token = $this->api->getTicket(WechatApiClient::TICKETTYPE_JSAPI,'fakeaccesstoken');
		$this->assertEquals('TICKET2',$token);

		$this->api->getCacheProvider()->clear(WechatApiClient::CACHEKEY_TICKET.':jsapi');
	}

}
