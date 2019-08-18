<?php

use zhangv\wechat\WechatApiClient;
use zhangv\wechat\apiclient\util\HttpClient;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase{
	/** @var WechatApiClient */
	private $api = null;
	/** @var \PHPUnit\Framework\MockObject\MockObject */
	private $httpClient = null;
	public function setUp(){
		$config = ['appid'=>'appid','appsecret'=>'secret'];
		$this->api = WechatApiClient::Menu($config);
		$this->httpClient = $this->createMock(HttpClient::class);
	}

	/** @test */
	public function createMenu(){
		$this->httpClient->method('post')->willReturn(
			'{"errcode":0,"errmsg":"ok"}');
		$this->httpClient->method('get')->willReturn(
			'{"access_token":"TOKEN","expires_in":7200}');

		$this->api->setHttpClient($this->httpClient);
		$r = $this->api->createMenu([]);
		$this->assertEquals('ok',$r->errmsg);
	}

	/**
	 * @test
	 * @expectedException Exception
	 * @expectedExceptionMessage invalid button name size
	 */
	public function createMenu_fail(){
		$this->httpClient->method('post')->willReturn(
			'{"errcode":40018,"errmsg":"invalid button name size"}');
		$this->api->setHttpClient($this->httpClient);
		$r = $this->api->createMenu([],'t');
	}

	/** @test */
	public function getMenu(){
		$this->httpClient->method('get')->willReturn(
			'{
			    "menu": {
			        "button": [
			            {
			                "type": "click", 
			                "name": "今日歌曲", 
			                "key": "V1001_TODAY_MUSIC", 
			                "sub_button": [ ]
			            }, 
			            {
			                "type": "click", 
			                "name": "歌手简介", 
			                "key": "V1001_TODAY_SINGER", 
			                "sub_button": [ ]
			            }, 
			            {
			                "name": "菜单", 
			                "sub_button": [
			                    {
			                        "type": "view", 
			                        "name": "搜索", 
			                        "url": "http://www.soso.com/", 
			                        "sub_button": [ ]
			                    }, 
			                    {
			                        "type": "view", 
			                        "name": "视频", 
			                        "url": "http://v.qq.com/", 
			                        "sub_button": [ ]
			                    }, 
			                    {
			                        "type": "click", 
			                        "name": "赞一下我们", 
			                        "key": "V1001_GOOD", 
			                        "sub_button": [ ]
			                    }
			                ]
			            }
			        ]
			    }
			}');
		$this->api->setHttpClient($this->httpClient);
		$m = $this->api->getMenu('fakeaccesstoken');
		$this->assertEquals('click',$m->menu->button[0]->type);
	}

}
