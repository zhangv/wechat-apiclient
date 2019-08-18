<?php

namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatAppApiClient;

class Auth extends WechatAppApiClient {

	/**
	 * 用户支付完成后，获取该用户的 UnionId，无需用户授权
	 * 通过微信支付订单号
	 * @param $openId
	 * @param $transactionId
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getPaidUnionId($openId,$transactionId){
		$url = "https://api.weixin.qq.com/wxa/getpaidunionid?openid={$openId}&transaction_id={$transactionId}";
		return $this->get($url);
	}

	/**
	 * 用户支付完成后，获取该用户的 UnionId，无需用户授权
	 * 通过商户订单号
	 * @param $openId
	 * @param $mchId
	 * @param $outTradeNo
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getPaidUnionIdByOutTradeNo($openId,$mchId,$outTradeNo){
		$url = "https://api.weixin.qq.com/wxa/getpaidunionid?openid={$openId}&mch_id={$mchId}&out_trade_no={$outTradeNo}";
		return $this->get($url);
	}

	/**
	 * 登录凭证校验
	 * @param $appId
	 * @param $secret
	 * @param $jsCode
	 * @param string $grantType
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function code2Session($appId,$secret,$jsCode,$grantType = 'authorization_code'){
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appId}&secret={$secret}&js_code={$jsCode}&grant_type={$grantType}";
		return $this->get($url);
	}

}