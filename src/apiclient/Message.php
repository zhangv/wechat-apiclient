<?php

namespace zhangv\wechat\apiclient;

trait Message{

	public function sendMsg($touser,$templateid,$url,$data,$topcolor = '#FF0000'){
		$params = [
			'touser' => $touser,
			'template_id' => $templateid,
			'url' => $url,
			'topcolor' => $topcolor,
			'data' => $data
		];
		return $this->post("https://api.weixin.qq.com/cgi-bin/message/template/send",json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	public function sendCustomMsgText($touser,$text){//小程序发送文本客服消息
		$params = [
			'touser' => $touser,
			'msgtype' => 'text',
			'text' => ['content'=>$text]
		];
		return $this->post("https://api.weixin.qq.com/cgi-bin/message/custom/send",json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	public function sendCustomMsgImage($touser,$mediaid){//小程序发送图片客服消息
		$params = [
			'touser' => $touser,
			'msgtype' => 'image',
			'image' => ['media_id'=>$mediaid]
		];
		return $this->post("https://api.weixin.qq.com/cgi-bin/message/custom/send",json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 预览接口
	 * @param $touser
	 * @param $message
	 * @param $msgtype
	 * @param null $towxname
	 * @return mixed
	 */
	public function massPreview($touser,$message,$msgtype,$towxname = null){
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview";
		$params = ['touser'=>$touser,'msgtype'=>$msgtype];
		if($towxname) $params['towxname'] = $towxname;
		if($msgtype == WechatApiClient::MSGTYPE_TEXT){
			$params['text'] = ['content'=>$message];
		}elseif($msgtype == WechatApiClient::MSGTYPE_WXCARD){
			$params[$msgtype] = json_encode($message);
		}else{
			$params[$msgtype] = ['media_id'=>$message];
		}
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 查询群发消息发送状态
	 * @param $msgid
	 * @return mixed
	 */
	public function getMassMessage($msgid){
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/get";
		$params = ['msg_id'=>$msgid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}
}