<?php
//TODO
namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatAppApiClient;

class UniformMessage extends WechatAppApiClient {

	public function send($toOpenId,$mpTemplateMsg){
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/uniform_send";
		/*$mpTemplateMsg = [
			'appid' => '', //公众号appid，要求与小程序有绑定且同主体
			'template_id' => '',//公众号模板id
			'url' => '',//公众号模板消息所要跳转的url
			'miniprogram' => '',//公众号模板消息所要跳转的小程序，小程序的必须与公众号具有绑定关系
			'data' => [
				'keyword1' => ['value' => '','color'=>''],
				'keyword2' => ['value' => '','color'=>''],
			]
		];*/
		$params = ['touser' => $toOpenId, 'mp_template_msg' => $mpTemplateMsg];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

}