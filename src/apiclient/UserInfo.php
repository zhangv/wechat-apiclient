<?php

namespace zhangv\wechat\apiclient;

trait UserInfo{

	/**
	 * 获取用户授权信息，即使用户没有关注，也可以获得用户信息（因为oauth授权）
	 * @param $openid
	 * @param null $oauthaccesstoken 用户授权后得到的token，不同于普通的accesstoken
	 * @return mixed
	 */
	public function getUserInfo($openid,$oauthaccesstoken){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=$oauthaccesstoken&openid=$openid&lang=zh_CN";
		$output=$this->get($url);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取用户基本信息，只能获取已关注用户的信息
	 * @param $openid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getUserBaseInfo($openid,$accesstoken = null){//获取用户基本信息
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accesstoken&openid=$openid&lang=zh_CN";
		$output=$this->get($url);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 批量获取用户信息
	 * @param $openids array 最多100条
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function batchGetUserInfo($openids,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=$accesstoken";
		$userlist = [];
		foreach($openids as $openid){
			$userlist[] = ['openid'=>$openid,'lang'=>'zh_CN'];
		}
		$params = ['user_list' => $userlist];
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}


	/**
	 * 获取用户列表
	 * @param $next_openid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getUser($next_openid,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$accesstoken";
		if($next_openid) $url .= "&next_openid=$next_openid";
		$output=$this->get($url);
		$r = json_decode($output);
		return $r;
	}

}