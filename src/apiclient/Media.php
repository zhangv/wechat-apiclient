<?php

namespace zhangv\wechat\apiclient;

trait Media{

	public function downloadMedia($media,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$accesstoken&media_id=$media";
		$output=$this->get($url);
		$package = $output[0];
		$httpinfo = $output[1];
		$media = array_merge(array('mediaBody' => $package), $httpinfo);
		//求出文件格式
		preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
		$fileExt = $extmatches[1];
		$filename = time().rand(100,999).".{$fileExt}";
		$dirname = TMP_PATH;
		if(!file_exists($dirname)){
			mkdir($dirname,0777,true);
		}
		file_put_contents($dirname.$filename,$media['mediaBody']);
		return $filename;
	}

	/**
	 * 新增临时素材
	 * @param $type
	 * @param $mediapath
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function uploadMedia($type,$mediapath,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$file = realpath($mediapath); //要上传的文件
		$fields['media'] = new CURLFile($file);
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$accesstoken&type=$type";
		$output=$this->post($url,$fields);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取临时素材
	 * @param $mediaid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMedia($mediaid,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$accesstoken&media_id=$mediaid";
		$output=$this->get($url);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 上传图文消息内的图片获取URL
	 * @param $mediapath
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function uploadImage($mediapath,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$file = realpath($mediapath); //要上传的文件
		$fields['media'] = new CURLFile($file);
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=$accesstoken";
		$output=$this->post($url,$fields);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 新增永久图文素材
	 * @param $articles
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function addNews($articles,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=$accesstoken";
		$output=$this->post($url,json_encode($articles,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 修改永久图文素材
	 * @param $article
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function updateNews($mediaid,$index,$article,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=$accesstoken";
		$fields = [
			'media_id' => $mediaid,
			'index' => $index,
			'articles' => json_encode($article)
		];
		$output=$this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取素材总数
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMaterialCount($accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=$accesstoken";
		$output=$this->httpGet($url);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 新增其他类型永久素材
	 * @param $type
	 * @param $mediapath
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function addMaterial($type,$mediapath,$accesstoken = null,$videotitle = null,$videointro = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$accesstoken&type=$type";
		$file = realpath($mediapath); //要上传的文件
		$fields['media'] = new CURLFile($file);
		if($type == WechatApiClient::MEDIATYPE_VIDEO){//在上传视频素材时需要POST另一个表单，id为description，包含素材的描述信息，内容格式为JSON
			$fields['description'] = json_encode(['title'=>$videotitle,'introduction'=>$videointro]);
		}
		$output=$this->post($url,$fields);
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取永久素材
	 * @param $mediaid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMaterial($mediaid,$accesstoken = null){
		if(!$accesstoken) $accesstoken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=$accesstoken";
		$fields['media_id'] = $mediaid;
		$output=$this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 删除永久素材
	 * @param $mediaid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function delMaterial($mediaid,$accesstoken = null){
		$url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=$accesstoken";
		$fields['media_id'] = $mediaid;
		$output=$this->httpPost($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}

	/**
	 * 获取素材列表
	 * @param $type
	 * @param $offset
	 * @param $count
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function batchGetMaterial($type,$offset,$count,$accesstoken = null){
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$accesstoken";
		$fields = ['type' => $type,'offset'=>$offset,'count'=>$count];
		$output=$this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
		$r = json_decode($output);
		return $r;
	}
}