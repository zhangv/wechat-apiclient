<?php

namespace zhangv\wechat\apiclient\applet;
use CURLFile;
use zhangv\wechat\WechatAppApiClient;

/**
 * Class CustomerServiceMessage
 * @package zhangv\wechat\apiclient\applet
 * @author zhangv
 * @license MIT
 */
class CustomerServiceMessage extends WechatAppApiClient {

	/**
	 * 下发客服当前输入状态给用户
	 * @param $toUser
	 * @param $command
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function setTyping($toUser,$command){
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/typing";
		$params = ['touser' => $toUser,'command' => $command];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 把媒体文件上传到微信服务器。目前仅支持图片。用于发送客服消息或被动回复用户消息。
	 * @param $mediapath
	 * @param string $type
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function uploadTempMedia($mediapath,$type = 'image'){
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload";
		$file = realpath($mediapath);
		$params['media'] = new CURLFile($file);
		return $this->post($url,$params,false,['type' => $type]);
	}

	/**
	 * 获取客服消息内的临时素材。即下载临时的多媒体文件。目前小程序仅支持下载图片文件。
	 * @param $mediaid
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getTempMedia($mediaid){
		$url = "https://api.weixin.qq.com/cgi-bin/media/get?media_id=$mediaid";
		$r=$this->get($url);
		return $r;
	}

	/**
	 * 发送文本
	 * @param $toUser
	 * @param $content
	 * @return bool|mixed|string
	 */
	public function sendText($toUser,$content){
		$params = [
			'touser' => $toUser,
			'msgtype' => 'text',
			'text' => [
				'content' => $content
			]
		];
		return $this->send($params);
	}

	/**
	 * 发送图片
	 * @param $toUser
	 * @param $mediaId
	 * @return bool|mixed|string
	 */
	public function sendImage($toUser,$mediaId){
		$params = [
			'touser' => $toUser,
			'msgtype' => 'image',
			'image' => [
				'media_id' => $mediaId
			]
		];
		return $this->send($params);
	}

	/**
	 * 发送图文链接
	 * @param $toUser
	 * @param $title
	 * @param $description
	 * @param $url
	 * @param $thumbUrl
	 * @return bool|mixed|string
	 */
	public function sendLink($toUser,$title,$description,$url,$thumbUrl){
		$params = [
			'touser' => $toUser,
			'msgtype' => 'link',
			'link' => [
				'title' => $title,
				'description' => $description,
				'url' => $url,
				'thumbUrl' => $thumbUrl,
			]
		];
		return $this->send($params);
	}

	/**
	 * 发送小程序卡片
	 * @param $toUser
	 * @param $title
	 * @param $pagePath
	 * @param $thumbMediaId
	 * @return bool|mixed|string
	 */
	public function sendMiniProgramPage($toUser,$title,$pagePath,$thumbMediaId){
		$params = [
			'touser' => $toUser,
			'msgtype' => 'miniprogrampage',
			'miniprogrampage' => [
				'title' => $title,
				'pagepath' => $pagePath,
				'thumb_media_id' => $thumbMediaId,
			]
		];
		return $this->send($params);
	}

	private function send($params){
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send";
		return $this->post($url,$params);
	}

}