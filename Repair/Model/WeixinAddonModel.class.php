<?php
        	
namespace Addons\Repair\Model;
use Home\Model\WeixinModel;
        	
/**
 * Repair的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {	
		$param ['token'] = get_token ();
		$param ['openid'] = get_openid ();
		$url = addons_url ( 'Repair://Repair/Repair', $param );
                // 组装微信需要的图文数据，格式是固定的
		$articles [0] = array (
				'Title' => '在线留言',
				'Description' => '请点击进入填写留言内容',
				'PicUrl' => SITE_URL . '/Addons/Repair/View/default/Public/cover.png',
				'Url' => $url
		);
		
		$res = $this->replyNews ( $articles );
	}  	
}
        	