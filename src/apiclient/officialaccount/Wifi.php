<?php

namespace zhangv\wechat\apiclient\officialaccount;
use zhangv\wechat\WechatApiClient;
class Wifi extends WechatApiClient {
	
	/**
	 * 获取Wi-Fi门店列表
	 * @param $pageindex
	 * @param $pagesize
	 * @return mixed
	 */
	public function listShop($pageindex = 1,$pagesize = 10){
		$url = "bizwifi/shop/list";
		$params = ['pageindex' => $pageindex,'pagesize'=> $pagesize];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 查询门店Wi-Fi信息
	 * @param $shopid
	 * @return mixed
	 */
	public function getShop($shopid){
		$url = "bizwifi/shop/get";
		$params = ['shop_id' => $shopid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 修改门店网络信息
	 * @param $shopid
	 * @return mixed
	 */
	public function updateShop($shopid,$oldssid,$ssid,$password = null){
		$url = "bizwifi/shop/update";
		$params = ['shop_id' => $shopid,'old_ssid' => $oldssid, 'ssid' => $ssid, 'password' => $password];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 清空门店网络及设备
	 * @param $shopid
	 * @return mixed
	 */
	public function cleanShop($shopid,$ssid = null){
		$url = "bizwifi/shop/clean";
		$params = ['shop_id' => $shopid, 'ssid' =>  $ssid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 添加密码型设备
	 * @param $shopid
	 * @return mixed
	 */
	public function addDevice($shopid,$ssid,$password){
		$url = "bizwifi/device/add";
		$params = ['shop_id' => $shopid, 'ssid' =>  $ssid, 'password' => $password];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 添加portal型设备
	 * @param $shopid
	 * @return mixed
	 */
	public function registerPortal($shopid,$ssid,$reset = false){
		$url = "bizwifi/apportal/register";
		$params = ['shop_id' => $shopid, 'ssid' =>  $ssid, 'reset' => $reset];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 查询设备
	 * @param $shopid
	 * @return mixed
	 */
	public function listDevice($pageindex = 1,$pagesize = 10, $shopid = null){
		$url = "bizwifi/device/list";
		$params = ['pageindex' => $pageindex,'pagesize'=> $pagesize,'shop_id' => $shopid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 删除设备
	 * @param $shopid
	 * @return mixed
	 */
	public function deleteDevice($bssid){
		$url = "bizwifi/device/delete";
		$params = ['bssid' => $bssid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取二维码
	 * @param $shopid
	 * @return mixed
	 */
	public function getQrCode($shopid,$ssid,$imgid = 0){
		$url = "bizwifi/qrcode/get";
		$params = ['shop_id' => $shopid,'ssid' => $ssid,'img_id' => $imgid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 设置商家主页
	 * @param $shopid
	 * @return mixed
	 */
	public function setHomePage($shopid,$templateid = 0,$struct = null){
		$url = "bizwifi/homepage/set";
		$params = ['shop_id' => $shopid,'template_id' => $templateid,'struct' => $struct];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 查询商家主页
	 * @param $shopid
	 * @return mixed
	 */
	public function getHomePage($shopid){
		$url = "bizwifi/homepage/get";
		$params = ['shop_id' => $shopid];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 设置微信首页欢迎语
	 * @param $shopid
	 * @return mixed
	 */
	public function setBar($shopid,$bartype = 1){
		//微信首页欢迎语的文本内容：0--欢迎光临+公众号名称；1--欢迎光临+门店名称；2--已连接+公众号名称+WiFi；3--已连接+门店名称+Wi-Fi
		$url = "bizwifi/bar/set";
		$params = ['shop_id' => $shopid,'bar_type' => $bartype];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 设置连网完成页
	 * @param $shopid
	 * @return mixed
	 */
	public function setFinishPage($shopid,$finishpageurl){
		$url = "bizwifi/finishpage/set";
		$params = ['shop_id' => $shopid,'finishpage_type' => 0,'finishpage_url' => $finishpageurl];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 设置连网完成小程序页面
	 * @param $shopid
	 * @return mixed
	 */
	public function setFinishApp($shopid,$wxa_user_name,$wxa_path){
		$url = "bizwifi/finishpage/set";
		$params = ['shop_id' => $shopid,'finishpage_type' => 1,'wxa_user_name' => $wxa_user_name, 'wxa_path' => $wxa_path];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}
}