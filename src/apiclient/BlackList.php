<?php

namespace zhangv\wechat\apiclient;

trait BlackList{

	/**
	 * 获取公众号的黑名单列表
	 * @param $begin_openid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getBlacklistMembers($begin_openid,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=$accesstoken";
		$params = ['begin_openid' => $begin_openid];
		$r = $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 拉黑用户
	 * @param $openid_list
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function batchBlacklistMembers($openid_list,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=$accesstoken";
		$params = ['openid_list' => $openid_list];
		$r = $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 取消拉黑用户
	 * @param $openid_list
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function batchUnBlacklistMembers($openid_list,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=$accesstoken";
		$params = ['openid_list' => $openid_list];
		$r = $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}
}