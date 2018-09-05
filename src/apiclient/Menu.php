<?php

namespace zhangv\wechat\apiclient;

trait Menu{
	/**
	 * 更新菜单
	 * //TODO 存在更新时返回空的情况
	 * @param $menu
	 * @return mixed
	 */
	public function createMenu($menu ){
		return $this->post("https://api.weixin.qq.com/cgi-bin/menu/create",json_encode($menu,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取菜单
	 * @return mixed
	 */
	public function getMenu(){
		$accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$accesstoken";
		return $this->get($url);
	}

}