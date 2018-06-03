<?php

namespace zhangv\wechat\apiclient;

trait Message{

	public function sendMsg($touser,$templateid,$url,$data,$topcolor = '#FF0000',$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = [
			'touser' => $touser,
			'template_id' => $templateid,
			'url' => $url,
			'topcolor' => $topcolor,
			'data' => $data
		];
		$r = $this->httpPost("https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accesstoken",json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	public function sendCustomMsgText($touser,$text,$accesstoken = null){//小程序发送文本客服消息
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = [
			'touser' => $touser,
			'msgtype' => 'text',
			'text' => ['content'=>$text]
		];
		$r = $this->httpPost("https://api.weixin.qq.com/cgi-bin/message/custom/send??access_token=$accesstoken",json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	public function sendCustomMsgImage($touser,$mediaid,$accesstoken = null){//小程序发送图片客服消息
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = [
			'touser' => $touser,
			'msgtype' => 'image',
			'image' => ['media_id'=>$mediaid]
		];
		$r = $this->httpPost("https://api.weixin.qq.com/cgi-bin/message/custom/send??access_token=$accesstoken",json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 预览接口
	 * @param $touser
	 * @param $message
	 * @param $msgtype
	 * @param null $towxname
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function massPreview($touser,$message,$msgtype,$towxname = null,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=$accesstoken";
		$params = ['touser'=>$touser,'msgtype'=>$msgtype];
		if($towxname) $params['towxname'] = $towxname;
		if($msgtype == WechatApiClient::MSGTYPE_TEXT){
			$params['text'] = ['content'=>$message];
		}elseif($msgtype == WechatApiClient::MSGTYPE_WXCARD){
			$params[$msgtype] = json_encode($message);
		}else{
			$params[$msgtype] = ['media_id'=>$message];
		}
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 查询群发消息发送状态
	 * @param $msgid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMassMessage($msgid,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token=$accesstoken";
		$params = ['msg_id'=>$msgid];
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}
}