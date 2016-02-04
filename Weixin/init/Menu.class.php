<?php
include_once 'Token.php';
class Menu {
	private $access_token;
	public function Menu($access_token) {
		$this->access_token = $access_token;
	}
	
	/**
	 * 创建菜单
	 * 通过传入的参数创建微信菜单,返回1创建成功,返回0创建失败
	 * $data 为创建菜单的json数据
	 */
	public function createMenu($data) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->access_token );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$tmpInfo = curl_exec ( $ch );
		if (curl_errno ( $ch )) {
			return curl_error ( $ch );
		}		
		curl_close ( $ch );
		$result = json_decode ( $tmpInfo, true );
		if (strcmp ( $result ['errcode'], "0" ) == 0)
			return 1;
		else
			return 0;
	}
	
	/**
	 * 创建默认菜单
	 * 
	 * @return 1创建成功,0创建失败
	 */
	public function createDefaultMenu() {
 	$data = '{
    "button": [
        {
            "name": "用户管理", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "查看用户", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/index?wei=1", 
                    "key": "101"
                }, 
                {
                    "type": "view", 
                    "name": "添加用户", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/add?wei=1", 
                    "key": "102"
                }, 
                {
                    "type": "view", 
                    "name": "删除用户", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/delete1?wei=1", 
                    "key": "103"
                }, 
                {
                    "type": "view", 
                    "name": "后台管理", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/backstage?wei=1", 
                    "key": "104"
                }, 
                {
                    "type": "view", 
                    "name": "前台管理", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/reception?wei=1", 
                    "key": "105"
                }
            ]
        }, 
        {
            "name": "用户查询", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "课表查询", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/index?wei=1", 
                    "key": "201"
                },
                {
                    "type": "view", 
                    "name": "成绩查询", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/grade?wei=1", 
                    "key": "202"
                },
                {
                    "type": "click", 
                    "name": "四六查询",                   
                    "key": "203"
                },
                {
                    "type": "view", 
                    "name": "信息查询", 
                    "url": "http://loveme1234567.oicp.net/hnustxl/admin/show/info?wei=1", 
                    "key": "204"
                },
                {
                    "type": "click", 
                    "name": "科大之家",                   
                    "key": "205"
                }
            ]
        }
    ]
  }';
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->access_token );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$tmpInfo = curl_exec ( $ch );
		if (curl_errno ( $ch )) {
			return curl_error ( $ch );
		}		
		curl_close ( $ch );
		$result = json_decode ( $tmpInfo, true );  
		if ($result ['errcode'] == 0)
			return 1;
		else
			return 0;
	}
	// 获取菜单
	public function getMenu() {
		return file_get_contents ( "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $this->access_token );
	}
	// 删除菜单
	public function deleteMenu() {
		return file_get_contents ( "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=" . $this->access_token );
	}
}