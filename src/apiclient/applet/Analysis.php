<?php

namespace zhangv\wechat\apiclient\applet;
use zhangv\wechat\WechatAppApiClient;

/**
 * Class Analysis
 * @package zhangv\wechat\apiclient\applet
 * @author zhangv
 * @license MIT
 * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/data-analysis/visit-retain/analysis.getMonthlyRetain.html
 */
class Analysis extends WechatAppApiClient {

	/**
	 * 获取用户访问小程序月留存
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getMonthlyRetain($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyretaininfo";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户访问小程序周留存
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getWeeklyRetain($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappidweeklyretaininfo";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户访问小程序日留存
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getDailyRetain($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户访问小程序数据月趋势
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getMonthlyVisitTrend($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyvisittrend";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户访问小程序数据周趋势
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getWeeklyVisitTrend($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappidweeklyvisittrend";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户访问小程序数据日趋势
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getDailyVisitTrend($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取小程序新增或活跃用户的画像分布数据
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getUserPortrait($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappiduserportrait";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户小程序访问分布数据
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getVisitDistribution($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappidvisitdistribution";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取访问页面
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getVisitPage($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappidvisitpage";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取用户访问小程序数据日概况
	 * @param $beginDate
	 * @param $endDate
	 * @return bool|mixed|string
	 * @throws \Exception
	 */
	public function getDailySummary($beginDate,$endDate){
		$url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend";
		$params = ['begin_date' => $beginDate,'end_date' => $endDate];
		return $this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
	}
}