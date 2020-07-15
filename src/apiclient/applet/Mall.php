<?php

namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatAppApiClient;

/**
 * 微信圈子物品接口
 * @package zhangv\wechat\apiclient\applet
 * @author zhangv
 * @license MIT
 * @link https://wsad.weixin.qq.com/wsad/zh_CN/htmledition/order/html/index.html
 */
class Mall extends WechatAppApiClient {

	/**
	 * 导入或更新物品信息
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function importProduct($productList){
		$url = "https://api.weixin.qq.com/mall/importproduct";
		return $this->post($url,json_encode($productList,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 查询物品信息
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function queryProduct($keyList,$type = 'batchquery'){
		$url = "https://api.weixin.qq.com/mall/queryproduct?type=$type";
		return $this->post($url,json_encode($keyList,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 导入或更新媒体信息
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function importMedia($mediaList){
		$url = "https://api.weixin.qq.com/mall/importproduct";
		return $this->post($url,json_encode($mediaList,JSON_UNESCAPED_UNICODE));
	}

}