<?php

namespace zhangv\wechat\apiclient\officialaccount;

class Article{
	public $title,$thumb_media_id,$author,$digest,$show_cover_pic,$content,$content_source_url;
	public function __construct($title,$thumb_media_id,$show_cover_pic,$content,$content_source_url){
		$this->title = $title;
		$this->thumb_media_id = $thumb_media_id;
		$this->show_cover_pic = $show_cover_pic;
		$this->content = $content;
		$this->content_source_url = $content_source_url;
	}
}