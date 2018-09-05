<?php

namespace zhangv\wechat\apiclient;

trait BlackList{

	/**
	 * 获取公众号的黑名单列表
	 * @param $begin_openid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getBlacklistMembers($begin_openid){
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist";
		$params = ['begin_openid' => $begin_openid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 拉黑用户
	 * @param $openid_list
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function batchBlacklistMembers($openid_list){
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist";
		$params = ['openid_list' => $openid_list];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 取消拉黑用户
	 * @param $openid_list
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function batchUnBlacklistMembers($openid_list){
		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist";
		$params = ['openid_list' => $openid_list];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}
}