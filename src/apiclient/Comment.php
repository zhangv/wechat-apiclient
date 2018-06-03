<?php

namespace zhangv\wechat\apiclient;

trait Comment{
	/**
	 * 打开已群发文章评论
	 * @param $msg_data_id
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function openComment($msg_data_id,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index];
		$r = $this->httpPost("https://api.weixin.qq.com/cgi-bin/comment/open?access_token=$accesstoken",json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 关闭已群发文章评论
	 * @param $msg_data_id
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function closeComment($msg_data_id,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/close?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 查看指定文章的评论数据
	 * @param $msg_data_id
	 * @param $begin
	 * @param $count
	 * @param int $type
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function listComment($msg_data_id,$begin,$count,$type = WechatApiClient::COMMENTTYPE_ALL,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index,'begin' => $begin,'count' => $count,'type' => $type];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/list?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 将评论标记精选
	 * @param $msg_data_id
	 * @param $user_comment_id
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function markElectComment($msg_data_id,$user_comment_id,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index,'user_comment_id' => $user_comment_id];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/markelect?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 将评论取消精选
	 * @param $msg_data_id
	 * @param $user_comment_id
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function unmarkElectComment($msg_data_id,$user_comment_id,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index,'user_comment_id' => $user_comment_id];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/unmarkelect?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 删除评论
	 * @param $msg_data_id
	 * @param $user_comment_id
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function deleteComment($msg_data_id,$user_comment_id,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index,'user_comment_id' => $user_comment_id];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/delete?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 回复评论
	 * @param $msg_data_id
	 * @param $user_comment_id
	 * @param $content
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function addCommentReply($msg_data_id,$user_comment_id,$content,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index,'user_comment_id' => $user_comment_id,'content'=>$content];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/reply/add?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}

	/**
	 * 删除回复
	 * @param $msg_data_id
	 * @param $user_comment_id
	 * @param $content
	 * @param int $index
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function deleteCommentReply($msg_data_id,$user_comment_id,$content,$index = 0,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$params = ['msg_data_id' => $msg_data_id,'index' => $index,'user_comment_id' => $user_comment_id];
		$url = "https://api.weixin.qq.com/cgi-bin/comment/reply/delete?access_token=$accesstoken";
		$r = $this->httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
		$r = json_decode($r);
		return $r;
	}
}