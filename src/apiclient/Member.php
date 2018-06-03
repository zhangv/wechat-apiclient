<?php
namespace zhangv\wechat\apiclient;

trait Member{

	/**
	 * 拉取会员信息（积分查询）
	 * @param $cardid
	 * @param $code
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMember($cardid,$code,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/membercard/userinfo/get?access_token=$accesstoken";
		$params = [
			'card_id' => $cardid, 'code' => $code
		];
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取用户已领取卡券接口
	 * @param $openid
	 * @param $cardid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getCardList($openid,$cardid = null,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/user/getcardlist?access_token=$accesstoken";
		$params = ['openid' => $openid];
		if($cardid) $params['card_id'] = $cardid;
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 更新会员信息
	 * TODO: 43010 - can not use balance, need authorize hint: [qBBjQa03232947]
	 * @param $code
	 * @param $cardid
	 * @param $bonus
	 * @param $add_bonus
	 * @param $balance
	 * @param null $add_balance
	 * @param null $record_bonus
	 * @param null $record_balance
	 * @param null $custom_field_value1
	 * @param null $custom_field_value2
	 * @param null $custom_field_value3
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function updateMember($code,$cardid,$bonus,$add_bonus,$balance,
	                             $add_balance = null,$record_bonus = null,$record_balance = null,
	                             $custom_field_value1 = null,$custom_field_value2 = null,$custom_field_value3 = null,
	                             $accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token=$accesstoken";
		$params = ['card_id' => $cardid, 'code' => $code];
		if($bonus) $params['$bonus'] = $bonus;
		if($add_bonus) $params['add_bonus'] = $add_bonus;
		if($balance) $params['balance'] = $balance;
		if($add_balance) $params['add_balance'] = $add_balance;
		if($record_bonus) $params['record_bonus'] = $record_bonus;
		if($record_balance) $params['record_balance'] = $record_balance;
		if($custom_field_value1) $params['custom_field_value1'] = $custom_field_value1;
		if($custom_field_value2) $params['custom_field_value2'] = $custom_field_value2;
		if($custom_field_value3) $params['custom_field_value3'] = $custom_field_value3;
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 激活会员
	 * @param $card_id
	 * @param $membership_number
	 * @param $code
	 * @param null $activate_begin_time
	 * @param null $activate_end_time
	 * @param null $init_bonus
	 * @param null $init_balance
	 * @param null $init_custom_field_value1
	 * @param null $init_custom_field_value2
	 * @param null $init_custom_field_value3
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function activateMember($card_id,$membership_number,$code,$activate_begin_time = null,$activate_end_time = null,
	                               $init_bonus = null,$init_balance = null, $init_custom_field_value1 = null,
	                               $init_custom_field_value2 = null,$init_custom_field_value3 = null,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/membercard/activate?access_token=$accesstoken";
		$params = ['card_id'=> $card_id,'membership_number' => $membership_number,'code' => $code];
		if($activate_begin_time) $params['activate_begin_time'] = $activate_begin_time;
		if($activate_end_time) $params['activate_end_time'] = $activate_end_time;
		if($init_bonus) $params['init_bonus'] = $init_bonus;
		if($init_balance) $params['init_balance'] = $init_balance;
		if($init_custom_field_value1) $params['init_custom_field_value1'] = $init_custom_field_value1;
		if($init_custom_field_value2) $params['init_custom_field_value2'] = $init_custom_field_value2;
		if($init_custom_field_value3) $params['init_custom_field_value3'] = $init_custom_field_value3;
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 创建会员卡
	 * @param $base_info
	 * @param $prerogative
	 * @param bool $auto_activate
	 * @param bool $wx_activate
	 * @param bool $supply_bonus
	 * @param null $bonus_url
	 * @param bool $supply_balance
	 * @param null $balance_url
	 * @param array $custom_field1
	 * @param array $custom_field2
	 * @param array $custom_field3
	 * @param null $bonus_cleared
	 * @param null $bonus_rules
	 * @param null $balance_rules
	 * @param null $activate_url
	 * @param array $custom_cell1
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function createMemberCard($base_info,$prerogative,$auto_activate = false,$wx_activate = true,
	                                 $supply_bonus = false,$bonus_url = null,$supply_balance = false,
	                                 $balance_url = null,$custom_field1 = [],$custom_field2 = [],$custom_field3 = [],
	                                 $bonus_cleared = null,$bonus_rules = null,
	                                 $balance_rules = null,$activate_url = null,$custom_cell1 = [],
	                                 $accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/create?access_token=$accesstoken";
		$params = ['card_type' => 'MEMBER_CARD','base_info' => $base_info,'prerogative' => $prerogative];
		if($auto_activate) $params['auto_activate'] = $auto_activate;
		if($wx_activate) $params['wx_activate'] = $wx_activate;
		if($supply_bonus) $params['supply_bonus'] = $supply_bonus;
		if($bonus_url) $params['bonus_url'] = $bonus_url;
		if($supply_balance) $params['supply_balance'] = $supply_balance;
		if($balance_url) $params['balance_url'] = $balance_url;
		if($custom_field1) $params['custom_field1'] = $custom_field1;
		if($custom_field2) $params['custom_field2'] = $custom_field2;
		if($custom_field3) $params['custom_field3'] = $custom_field3;
		if($url) $params['url'] = $url;
		if($bonus_cleared) $params['bonus_cleared'] = $bonus_cleared;
		if($bonus_rules) $params['bonus_rules'] = $bonus_rules;
		if($balance_rules) $params['balance_rules'] = $balance_rules;
		if($activate_url) $params['activate_url'] = $activate_url;
		if($custom_cell1) $params['custom_cell1'] = $custom_cell1;
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * @param $logo_url
	 * @param $code_type
	 * @param $brand_name
	 * @param $title
	 * @param $color
	 * @param $notice
	 * @param $description
	 * @param string $sub_title
	 * @param array $sku
	 * @param array $date_info
	 * @param null $begin_timestamp
	 * @param null $end_timestamp
	 * @param null $fixed_term
	 * @param null $fixed_begin_term
	 * @param bool $bind_openid
	 * @param null $service_phone
	 * @param array $location_id_list
	 * @param null $source
	 * @param null $custom_url_name
	 * @param null $custom_url
	 * @param null $custom_url_sub_title
	 * @param null $promotion_url_name
	 * @param null $promotion_url
	 * @param null $promotion_url_sub_title
	 * @param int $get_limit
	 * @param bool $can_share
	 * @param bool $can_give_friend
	 * @param bool $need_push_on_view
	 * @return array
	 */
	public function createMemberCardBaseInfo($logo_url,$code_type,$brand_name,$title,$color,$notice,$description,
	                                         $sub_title = '',$sku = ['quantity' => 50000000],
	                                         $date_info = ['type' => "DATE_TYPE_PERMANENT"],$begin_timestamp = null,
	                                         $end_timestamp = null,$fixed_term = null,$fixed_begin_term = null,
	                                         $bind_openid = false,$service_phone = null, $location_id_list = [],
	                                         $source = null,$custom_url_name = null,$custom_url = null,
	                                         $custom_url_sub_title = null,$promotion_url_name = null, $promotion_url = null,
	                                         $promotion_url_sub_title = null,$get_limit = 1,$can_share  = true,
	                                         $can_give_friend = false,$need_push_on_view = false){
		$params = ['logo_url' => $logo_url,'code_type' => $code_type, 'brand_name' => $brand_name,
			'title' => $title,'color' => $color,'notice' => $notice,'description' => $description,
			'sub_title' => $sub_title,'sku' => $sku,'date_info' => $date_info,'begin_timestamp' => $begin_timestamp,
			'end_timestamp' => $end_timestamp,'fixed_term' => $fixed_term,'fixed_begin_term' => $fixed_begin_term,'bind_openid' => $bind_openid,
			'service_phone' => $service_phone,'location_id_list' => $location_id_list,'source' => $source,
			'custom_url_name' => $custom_url_name,'custom_url' => $custom_url,'custom_url_sub_title' => $custom_url_sub_title,
			'promotion_url_name' => $promotion_url_name,'promotion_url' => $promotion_url,'promotion_url_sub_title' => $promotion_url_sub_title,
			'get_limit' => $get_limit,'can_share' => $can_share,'can_give_friend' => $can_give_friend,
			'need_push_on_view' => $need_push_on_view];
		return $params;
	}

	/**
	 * 设置开卡表单字段
	 * @param $card_id
	 * @param array $required_form
	 * @param array $optional_form
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function setActivationForm($card_id,$required_form = null,$optional_form = null,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token=$accesstoken";
		$params = ['card_id' => $card_id];
		if(!$required_form) {
			$params['required_form'] = [
				'common_field_id_list' => [
					"USER_FORM_INFO_FLAG_MOBILE",
					"USER_FORM_INFO_FLAG_LOCATION",
					"USER_FORM_INFO_FLAG_BIRTHDAY"
				],
				"custom_field_list" => ["喜欢的食物"]
			];
		}
		if(!$optional_form) {
			$params['optional_form'] = [
				'common_field_id_list' => [
					"USER_FORM_INFO_FLAG_EMAIL"
				],
				"custom_field_list" => ["喜欢的电影"]
			];
		}
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 查看会员卡配置
	 * @param $cardid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMemberCard($cardid,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/get?access_token=$accesstoken";
		$params = ['card_id' => $cardid];
		$params = json_encode($params,JSON_UNESCAPED_UNICODE);
		$output=$this->post($url,$params);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 更新会员卡配置
	 * @param $cardid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function updateMemberCard($cardid,$memberCard,$accesstoken = null){
		$updatables = [
			'background_pic_url','bonus_cleared','bonus_rules','balance_rules','prerogative','wx_activate','auto_activate','activate_url',
			'custom_field1','custom_field2','custom_field3', 'name_type','url','custom_cell1','bonus_rule','cost_money_unit',
			'increase_bonus','max_increase_bonus','init_increase_bonus', 'cost_bonus_unit','reduce_money','least_money_to_use_bonus',
			'max_reduce_bonus','discount',
			'wx_activate_after_submit','wx_activate_after_submit_url' //这两个字段文档没写，但是是可以修改的 - 只能通过api修改
			];

		$baseinfo_updatables = [
			'logo_url','notice','description','service_phone',
			//'color', //skip it as what you get is hex value, while when submitting, it is something like 'Color010' - wtf
			'location_id_list','use_all_locations','center_title','center_sub_title',
			'center_url','custom_url_name','custom_url','custom_url_sub_title','promotion_url_name','promotion_url','promotion_url_sub_title',
			'code_type','get_limit','can_share','can_give_friend','date_info','type','begin_timestamp','end_timestamp'
		];
		$updates = [];
		foreach($updatables as $updatable){
			if(empty($memberCard->$updatable) || !$memberCard->$updatable) continue;
			$updates[$updatable] = $memberCard->$updatable;
		}
		$baseinfo_updates = [];
		foreach($baseinfo_updatables as $updatable){
			if(empty($memberCard->base_info->$updatable) || !$memberCard->base_info->$updatable) continue;
			$baseinfo_updates[$updatable] = $memberCard->base_info->$updatable;
		}
		$updates['base_info'] = $baseinfo_updates;
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/update?access_token=$accesstoken";
		$params = ['card_id' => $cardid,'member_card'=>$updates];
		$output=$this->post($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取用户提交资料
	 * @param $activateTicket
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getActivateTempInfo($activateTicket,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/membercard/activatetempinfo/get?access_token=$accesstoken";
		$params = ['activate_ticket' => $activateTicket];
		$params = json_encode($params,JSON_UNESCAPED_UNICODE);
		$output=$this->post($url,$params);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取用户提交资料
	 * @param $code
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function decryptCode($code,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/card/code/decrypt?access_token=$accesstoken";
		$params = ['encrypt_code' => $code];
		$params = json_encode($params,JSON_UNESCAPED_UNICODE);
		$output=$this->post($url,$params);
		$r = json_decode($output);
		return $r->code;
	}
}