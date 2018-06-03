<?php

namespace zhangv\wechat\apiclient;

trait Menu{
	/**
	 * 更新菜单
	 * @param $menu
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function createMenu($menu , $accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$r = $this->post("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accesstoken",json_encode($menu,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 获取菜单
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMenu( $accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$accesstoken";
		$output=$this->get($url);
		$r = json_decode($output);
		return $r;
	}

}