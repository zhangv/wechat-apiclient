<?php

namespace zhangv\wechat\apiclient\officialaccount;

use zhangv\wechat\WechatApiClient;

class Menu extends WechatApiClient {
	/**
	 * 更新菜单
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
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get";
		return $this->get($url);
	}

}