<?php

namespace zhangv\wechat\apiclient\officialaccount;

use \CURLFile;
use zhangv\wechat\WechatApiClient;

class Media extends WechatApiClient{

	public function downloadMedia($media){//not test
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?media_id=$media";
		$output=$this->get($url);
		$package = $output[0];
		$httpinfo = $output[1];
		$media = array_merge(array('mediaBody' => $package), $httpinfo);
		//求出文件格式
		preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
		$fileExt = $extmatches[1];
		$filename = time().rand(100,999).".{$fileExt}";
		$dirname = '/tmp/wechatapiclient';
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
	public function uploadMedia($type,$mediapath){
		$file = realpath($mediapath); //要上传的文件
		$fields['media'] = new CURLFile($file);
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload";
		$r=$this->post($url,$fields,false,['type' => $type]);
		return $r;
	}

	/**
	 * 获取临时素材
	 * @param $mediaid
	 * @return mixed
	 */
	public function getMedia($mediaid){
		$url = "https://api.weixin.qq.com/cgi-bin/media/get?media_id=$mediaid";
		$r=$this->get($url);
		return $r;
	}

	/**
	 * 上传图文消息内的图片获取URL
	 * @param $mediapath
	 * @return mixed
	 */
	public function uploadImage($mediapath){
		$file = realpath($mediapath); //要上传的文件
//		$fields['media'] = new CURLFile($file);
		$fields['media'] = "@$file";
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg";
		$r=$this->post($url,$fields);
		return $r;
	}

	/**
	 * 新增永久图文素材
	 * @param $articles
	 * @return mixed
	 */
	public function addNews($articles){
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_news";
		return $this->post($url,json_encode($articles,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 修改永久图文素材
	 * @param $article
	 * @return mixed
	 */
	public function updateNews($mediaid,$index,$article){
		$url = "https://api.weixin.qq.com/cgi-bin/material/update_news";
		$fields = [
			'media_id' => $mediaid,
			'index' => $index,
			'articles' => json_encode($article)
		];
		return $this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取素材总数
	 * @return mixed
	 */
	public function getMaterialCount(){
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount";
		return $this->get($url);
	}

	/**
	 * 新增其他类型永久素材
	 * @param $type
	 * @param $mediapath
	 * @return mixed
	 */
	public function addMaterial($type,$mediapath,$videotitle = null,$videointro = null){
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material";
		if(class_exists('CURLFile')){
			$data = array('media' => new CURLFile(realpath($mediapath)));
		}else{
			$data = array('media'=>'@'.$mediapath);
		}
		if($type == WechatApiClient::MEDIATYPE_VIDEO){//在上传视频素材时需要POST另一个表单，id为description，包含素材的描述信息，内容格式为JSON
			$data['description'] = json_encode(['title'=>$videotitle,'introduction'=>$videointro]);
		}
		return $this->post($url,$data,false,['type' => $type]);
	}

	/**
	 * 新增其他类型永久素材
	 * @param $type
	 * @param $mediapath
	 * @return mixed
	 */
	public function addMaterial0($type,$mediapath,$videotitle = null,$videointro = null){
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material";
		$file = realpath($mediapath); //要上传的文件
		$fields['media'] = new \CURLFile($file);
		if($type == WechatApiClient::MEDIATYPE_VIDEO){//在上传视频素材时需要POST另一个表单，id为description，包含素材的描述信息，内容格式为JSON
			$fields['description'] = json_encode(['title'=>$videotitle,'introduction'=>$videointro]);
		}
		return $this->post($url,$fields,false,['type' => $type]);
	}

	/**
	 * 获取永久素材
	 * @param $mediaid
	 * @param null $accesstoken
	 * @return mixed
	 */
	public function getMaterial($mediaid){
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_material";
		$fields['media_id'] = $mediaid;
		return $this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 删除永久素材
	 * @param $mediaid
	 * @return mixed
	 */
	public function delMaterial($mediaid){
		$url = "https://api.weixin.qq.com/cgi-bin/material/del_material";
		$fields['media_id'] = $mediaid;
		return $this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
	}

	/**
	 * 获取素材列表
	 * @param $type
	 * @param $offset
	 * @param $count
	 * @return mixed
	 */
	public function batchGetMaterial($type,$offset,$count){
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material";
		$fields = ['type' => $type,'offset'=>$offset,'count'=>$count];
		return $this->post($url,json_encode($fields,JSON_UNESCAPED_UNICODE));
	}
}