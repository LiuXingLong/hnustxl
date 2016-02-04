<?php
/*
    方倍工作室 http://www.cnblogs.com/txw1958/
    CopyRight 2013 www.doucube.com  All Rights Reserved

	用户名　 :  SAE_MYSQL_USER
	密　　码 :  SAE_MYSQL_PASS
	主库域名 :  SAE_MYSQL_HOST_M
	从库域名 :  SAE_MYSQL_HOST_S
	端　　口 :  SAE_MYSQL_PORT
	数据库名 :  SAE_MYSQL_DB
*/

$config['username']="root";
$config['password']="root";
$config['host']="127.0.0.1";
$config['port']="3306";
$config['dbname']="hnustxl";

define("CONFIG_HOST",$config['host']);
define("CONFIG_USERNAME",$config['username']);
define("CONFIG_PASSWORD",$config['password']);
define("CONFIG_PORT", $config['port']);
define("CONFIG_DBNAME",$config['dbname']);

define("ROOTURL","http://loveme1234567.oicp.net/hnustxl");

define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{	
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        
        /*
		 //测试用  将微信发来的所有信息存到数据库	 
		$con = mysqli_connect(CONFIG_HOST, CONFIG_USERNAME, CONFIG_PASSWORD, CONFIG_DBNAME,CONFIG_PORT);
		if (mysqli_connect_errno($con)) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		mysqli_set_charset($con, "utf8");
		mysqli_query($con,"insert into test values(0,'$postStr')");
		mysqli_close($con);			
		exit();
		*/
        
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);                                                        
            
            /*
            if(empty($_SESSION['openid'])){
           	 	$fromUsername=$postObj->FromUserName;
            	session_id($fromUsername); //  存入  session 编号
            	session_start();
            	$_SESSION['openid']="$fromUsername";              
            }
            if(empty($_SESSION['status'])){                
                $dsn="mysql:host=".CONFIG_HOST.";port=".CONFIG_PORT.";dbname=".CONFIG_DBNAME;  
				try {
    				$conn = new PDO($dsn, CONFIG_USERNAME, CONFIG_PASSWORD); //初始化一个PDO对象   			   					
				} catch (PDOException $e) {
    					die ("Error!: " . $e->getMessage() . "<br/>");
				}	
				$stmt = $conn->prepare("SELECT username FROM weixin where openid = ?");
				if ($stmt->execute(array($_SESSION['openid']))){
					$row = $stmt->fetchAll();
					$cnt=count($row);
				}
                if($cnt>0){
                    $stmt = $conn->prepare("SELECT *FROM useradmin where username = ?");
                    if ($stmt->execute(array($row[0]['username']))){
						$result = $stmt->fetchAll();										
                		$_SESSION['status']=1;
			 			$_SESSION['flag1']=$result[0]['flag1'];
			 			$_SESSION['flag2']=$result[0]['flag2'];
			 			$_SESSION['flag3']=$result[0]['flag3'];
			 			$_SESSION['flag4']=$result[0]['flag4'];
			 			$_SESSION['flag5']=$result[0]['flag5'];
			 			$_SESSION['flag6']=$result[0]['flag6'];
			 			$_SESSION['flag7']=$result[0]['flag7'];
                    }
                    $conn=$row=$result=null;
                }else{
                    $_SESSION['status']=0;
                }
            }
            */
            
            $RX_TYPE = trim($postObj->MsgType);
            $Key=$postObj->EventKey;
            if ($postObj->Event == "subscribe"){
                //关注微信号                
                 $newsArray = array();
				 $newsArray[0] = array("Title" =>"欢迎来到 hnustxl ！使用前需要登入一次。", 
                   				       "Description" =>"点击图片进入", 
                                       "PicUrl" =>ROOTURL."/Weixin/image/hnust.jpg",//  http://res.eqxiu.com/group2/M00/35/12/yq0KX1YY-P6ALtszAAAasmIRWm0529.jpg
                   				       "Url" =>ROOTURL."/Admin/Login/index?wei=1");          
                 $result=$this->transmitNews($postObj, $newsArray);                 
            }elseif($postObj->Event == "unsubscribe"){
                //取消关注  删除绑定信息  摧毁session
                                
            }else{ 
                switch($RX_TYPE)
                {
                    case "event":
                    	if($Key=="203"){                   	
                            $newsArray = array();
							$newsArray[0] = array("Title" =>"大学英语四六级成绩查询", 
                   				 			  	"Description" =>"点击图片进入", 
                   				 			  	"PicUrl" =>"http://365jia.cn/uploads/13/0301/5130c2ff93618.jpg", 
                   				 			  	"Url" =>ROOTURL."/Weixin/English/index.php?wei=1");          
           		        	$result=$this->transmitNews($postObj, $newsArray);
                    	}elseif($Key=="205"){                            
                            /*
                        	$newsArray = array();
                        	$newsArray[] = array("Title" =>"信息门户", "Description" =>"", "PicUrl" =>"", "Url" =>"http://portal.hnust.edu.cn");                                                      
                            $newsArray[] = array("Title" =>"教务网", "Description" =>"", "PicUrl" =>"", "Url" =>"http://jwc.hnust.cn");                           
                            $newsArray[] = array("Title" =>"学工在线", "Description" =>"", "PicUrl" =>"", "Url" =>"http://xg.hnust.edu.cn");
                            $newsArray[] = array("Title" =>"图书馆", "Description" =>"", "PicUrl" =>"", "Url" =>"http://lib.hnust.cn");
                            $newsArray[] = array("Title" =>"财务查询", "Description" =>"", "PicUrl" =>"", "Url" =>"http://cwc.hnust.cn");
                            $newsArray[] = array("Title" =>"网络服务", "Description" =>"", "PicUrl" =>"", "Url" =>"http://nicweb.hnust.edu.cn");
                            $newsArray[] = array("Title" =>"后勤管理", "Description" =>"", "PicUrl" =>"", "Url" =>"http://dep.hnust.cn/houqin");
                            $newsArray[] = array("Title" =>"心理健康", "Description" =>"", "PicUrl" =>"", "Url" =>"http://xlzx.hnust.edu.cn");
                            $newsArray[] = array("Title" =>"保卫处、武装部", "Description" =>"", "PicUrl" =>"", "Url" =>"http://bwc.hnust.edu.cn");                            
                        	$result=$this->transmitNews($postObj, $newsArray);
                            */                                                      
                                                                                                          
                            $content="\n		  <a href='http://www.hnust.edu.cn'>湖南科技大学官网</a>\n\n				 	部门站点\n\n<a href='http://portal.hnust.edu.cn'>1.信息门户</a>				<a href='http://jwc.hnust.cn'>2.教务网</a>\n\n<a href='http://xg.hnust.edu.cn'>3.学工在线</a>				<a href='http://lib.hnust.cn'>4.图书馆</a>\n\n<a href='http://cwc.hnust.cn'>5.财务查询</a>				<a href='http://nicweb.hnust.edu.cn'>6.网络服务</a>\n\n<a href='http://dep.hnust.cn/houqin'>7.后勤管理</a>				<a href='http://xlzx.hnust.edu.cn'>8.心理健康</a>\n\n<a href='http://bwc.hnust.edu.cn'>9.保卫处、武装部</a>\n\n\n					服务站点\n\n<a href='http://211.67.208.76'>1.教学资源</a>				<a href='http://qks.hnust.cn'>2.学术期刊</a>\n\n<a href='http://jypx.hnust.cn'>3.就业培训</a>				<a href='http://xwgk.hnust.edu.cn'>4.校务公开</a>\n\n<a href='http://tw.hnust.cn:9080/index.html'>5.团委</a>						<a href='http://dep.hnust.cn/gonghui'>6.工会</a>\n\n<a href='http://e.weibo.com/u/2126925453'>7.官方微博</a>\n\n			学院查询请发送：学院\n";                                                                             
                            $result=$this->transmitText($postObj, $content);
                            /* 
                                                                                   主动发送消息
                            $appID="wx5ff51c16c307d28c";
                            $appsecret="a611d8b27d5eb19fea4be3158b8a3017";
                            $content=$content."\n				院系站点\n\n<a href='http://power.hnust.cn:9080/index.html'>1.能源与安全工程学院</a>\n\n<a href='http://tmxy.hnust.edu.cn'>2.土木工程学院</a>\n\n<a href='http://jd.hnust.edu.cn'>3.机电工程学院</a>\n\n<a href='http://xxydq.hnust.edu.cn'>4.信息与电气工程学院</a>\n\n<a href='http://computer.hnust.edu.cn'>5.计算机科学与工程学院</a>\n\n<a href='http://dep.hnust.cn/chem'>6.化学化工学院</a>\n\n<a href='http://math.hnust.edu.cn/pub/sxxy'>7.数学与计算科学学院</a>\n\n<a href='http://wlxy.hnust.cn'>8.物理与电子科学学院</a>\n\n<a href='http://science.hnust.edu.cn'>9.生命科学学院</a>\n\n<a href='http://www.hnkdjz.cn'>10.建筑与城乡规划学院</a>\n\n<a href='http://dep2.hnust.edu.cn:9080/rwxy'>11.人文学院</a>\n\n<a href='http://wyxy.hnust.edu.cn'>12.外国语学院</a>\n\n<a href='http://marxism.hnust.edu.cn'>13.马克思主义学院</a>\n\n<a href='http://jyxy.hnust.edu.cn'>14.教育学院</a>\n\n<a href='http://sxy.hnust.edu.cn'>15.商学院</a>\n\n<a href='http://art.hnust.edu.cn'>16.艺术学院</a>\n\n<a href='http://sports.hnust.edu.cn'>17.体育学院</a>\n\n<a href='http://glxy.hnust.edu.cn'>18.管理学院</a>\n\n<a href='http://law.hnust.edu.cn'>19.法学院</a>\n\n<a href='http://xxxy.hnust.edu.cn'>20.潇湘学院</a>";
                            $data= '{
					   			"touser":"'.$postObj->FromUserName.'", 
					  			"msgtype":"text", 
					    		"text": { "content":"'.$content.'"}
					   		    }';
                            $this->main_new($appID,$appsecret,$data);
                            */
                        }                            
                    	break;
                    case "text":
                   		if (strstr($postObj->Content, "学院")){
                            $content=$content."\n				院系站点\n\n<a href='http://power.hnust.cn:9080/index.html'>1.能源与安全工程学院</a>\n\n<a href='http://tmxy.hnust.edu.cn'>2.土木工程学院</a>\n\n<a href='http://jd.hnust.edu.cn'>3.机电工程学院</a>\n\n<a href='http://xxydq.hnust.edu.cn'>4.信息与电气工程学院</a>\n\n<a href='http://computer.hnust.edu.cn'>5.计算机科学与工程学院</a>\n\n<a href='http://dep.hnust.cn/chem'>6.化学化工学院</a>\n\n<a href='http://math.hnust.edu.cn/pub/sxxy'>7.数学与计算科学学院</a>\n\n<a href='http://wlxy.hnust.cn'>8.物理与电子科学学院</a>\n\n<a href='http://science.hnust.edu.cn'>9.生命科学学院</a>\n\n<a href='http://www.hnkdjz.cn'>10.建筑与城乡规划学院</a>\n\n<a href='http://dep2.hnust.edu.cn:9080/rwxy'>11.人文学院</a>\n\n<a href='http://wyxy.hnust.edu.cn'>12.外国语学院</a>\n\n<a href='http://marxism.hnust.edu.cn'>13.马克思主义学院</a>\n\n<a href='http://jyxy.hnust.edu.cn'>14.教育学院</a>\n\n<a href='http://sxy.hnust.edu.cn'>15.商学院</a>\n\n<a href='http://art.hnust.edu.cn'>16.艺术学院</a>\n\n<a href='http://sports.hnust.edu.cn'>17.体育学院</a>\n\n<a href='http://glxy.hnust.edu.cn'>18.管理学院</a>\n\n<a href='http://law.hnust.edu.cn'>19.法学院</a>\n\n<a href='http://graduate.hnust.cn/web'>20.研究生学院</a>\n\n<a href='http://xxxy.hnust.edu.cn'>21.潇湘学院</a>\n";
                        	$result=$this->transmitText($postObj, $content);                    	
                    	}else{
                    		$result = $this->transmitText($postObj,"抱歉，该功能暂时没有开通！");
                    	}
                    	break;
                    default:
                    	$result = $this->transmitText($postObj,"抱歉，该功能暂时没有开通！");
                    	break;
                }
                
                
                /*               
            	//消息类型分离
            	switch ($RX_TYPE)
            	{
                	case "event":
                    	$result = $this->receiveEvent($postObj);
                    	break;
                	case "text":
                   		if (strstr($postObj->Content, "第三方")){
                        	$result = $this->relayPart3("http://www.fangbei.org/test.php".'?'.$_SERVER['QUERY_STRING'], $postStr);
                    	}else{
                        	$result = $this->receiveText($postObj);
                    	}
                    	break;
                	case "image":
                    	$result = $this->receiveImage($postObj);
                    	break;
                	case "location":
                    	$result = $this->receiveLocation($postObj);
                    	break;
                	case "voice":
                    	$result = $this->receiveVoice($postObj);
                    	break;
                	case "video":
                    	$result = $this->receiveVideo($postObj);
                    	break;
                	case "link":
                    	$result = $this->receiveLink($postObj);
                    	break;
                	default:
                    	$result = "unknown msg type: ".$RX_TYPE;
                    	break;
            	}                
                */                
            }     
            echo $result;         
        }else{
            echo "";
            exit;
        }
    }
    //接收事件消息
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎关注方倍工作室 ";
                $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "COMPANY":
                        $content = array();
                        $content[] = array("Title"=>"方倍工作室", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                        break;
                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "SCAN":
                $content = "扫描场景 ".$object->EventKey;
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "scancode_waitmsg":
                if ($object->ScanCodeInfo->ScanType == "qrcode"){
                    $content = "扫码带提示：类型 二维码 结果：".$object->ScanCodeInfo->ScanResult;
                }else if ($object->ScanCodeInfo->ScanType == "barcode"){
                    $codeinfo = explode(",",strval($object->ScanCodeInfo->ScanResult));
                    $codeValue = $codeinfo[1];
                    $content = "扫码带提示：类型 条形码 结果：".$codeValue;
                }else{
                    $content = "扫码带提示：类型 ".$object->ScanCodeInfo->ScanType." 结果：".$object->ScanCodeInfo->ScanResult;
                }
                break;
            case "scancode_push":
                $content = "扫码推事件";
                break;
            case "pic_sysphoto":
                $content = "系统拍照";
                break;
            case "pic_weixin":
                $content = "相册发图：数量 ".$object->SendPicsInfo->Count;
                break;
            case "pic_photo_or_album":
                $content = "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
                break;
            case "location_select":
                $content = "发送位置：标签 ".$object->SendLocationInfo->Label;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }

        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    //接收文本消息
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        //多客服人工回复模式
        if (strstr($keyword, "请问在吗") || strstr($keyword, "在线客服")){
            $result = $this->transmitService($object);
            return $result;
        }

        //自动回复模式
        if (strstr($keyword, "文本")){
            $content = "这是个文本消息";
        }else if (strstr($keyword, "表情")){
            $content = "中国：".$this->bytes_to_emoji(0x1F1E8).$this->bytes_to_emoji(0x1F1F3)."\n仙人掌：".$this->bytes_to_emoji(0x1F335);
        }else if (strstr($keyword, "单图文")){
            $content = array();
            $content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
            $content = array();
            $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            $content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            $content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        }else if (strstr($keyword, "音乐")){
            $content = array();
            $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3"); 
        }else{
            $content = date("Y-m-d H:i:s",time())."\nOpenID：".$object->FromUserName."\n技术支持 方倍工作室";
        }

        if(is_array($content)){
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，经度为：".$object->Location_Y."；纬度为：".$object->Location_X."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }
    
    
    /****************   主动发送消息给客户    start *****************/
    
    /**
     * 获取 access_token
     * 参数
     * $appID       wx730fa2ad7987400a
     * $appsecret   6cb332b8cd0158d857cf2bc9744015da
     * @return
     * 成功返回：access_token
     * 失败返回：false
     */
    private function get_Access_token($appID,$appsecret){
    	$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appID}&secret={$appsecret}";
    	$ch=curl_init();
    	curl_setopt ( $ch, CURLOPT_URL, $url);
    	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
    	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );	//设置获取的信息以文件流的形式返回，而不是直接输出。
    	$data = curl_exec($ch); //执行命令
    	curl_close($ch);  //关闭URL请求
    	$data = json_decode ( $data, true ); // 将json 数据转换为数组数据
    	if (empty ( $data ['errcode'] )) {
    		$access_token = $data ['access_token'];
    		if (! empty ( $access_token )) {
    			return $access_token;
    		}
    		return false;
    	} else {
    		return false;
    	}
    }
    /**
     * 主动发送短信
     * 参数
     * 发送  消息内容
     * $access_token
     *
     * @return 返回除了结果   errcode      42001 代表$access_token超时   0 代表请求成功
     */
    private function Send_message($data,$access_token) {
    	$ch = curl_init ();
    	curl_setopt ( $ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}" );
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
    	return $result ['errcode'];
    }
    
    /**
     * 主动给客户发送消息
     *
     * 参数
     *
     * 公众号     $appID   $appsecret
     * 发送内容   $data
     */
    private function main_new($appID,$appsecret,$data){
    	while(empty($_SESSION['access_token'])){
    		$_SESSION['access_token']=$this->get_Access_token($appID,$appsecret);
    	}
    	$flag=$this->Send_message($data,$_SESSION['access_token']);
    	if($flag==42001){
    		//  token失效
    		$_SESSION['access_token']=$this->get_Access_token($appID,$appsecret);
    		$flag=$this->Send_message($data,$_SESSION['access_token']);
    	}
    }
    
    /****************   主动发送消息给客户    end *****************/
    
    
    //回复文本消息
     private function transmitText($object, $content)
     {
         if (!isset($content) || empty($content)){
             return "";
         }
         $xmlTpl = "<xml>
     				<ToUserName><![CDATA[%s]]></ToUserName>
     				<FromUserName><![CDATA[%s]]></FromUserName>
     				<CreateTime>%s</CreateTime>
     				<MsgType><![CDATA[text]]></MsgType>
     				<Content><![CDATA[%s]]></Content>
 					</xml>";
         $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content); 
         return $result;
     }
    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "<item>
            		<Title><![CDATA[%s]]></Title>
           		    <Description><![CDATA[%s]]></Description>
            		<PicUrl><![CDATA[%s]]></PicUrl>
            		<Url><![CDATA[%s]]></Url>
        		 	</item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str.= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
    			   <ToUserName><![CDATA[%s]]></ToUserName>
    			   <FromUserName><![CDATA[%s]]></FromUserName>
    			   <CreateTime>%s</CreateTime>
    			   <MsgType><![CDATA[news]]></MsgType>
    			   <ArticleCount>%s</ArticleCount>
    			   <Articles>$item_str</Articles>
				   </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        if(!is_array($musicArray)){
            return "";
        }
        $itemTpl = "<Music>
        			<Title><![CDATA[%s]]></Title>
        			<Description><![CDATA[%s]]></Description>
        			<MusicUrl><![CDATA[%s]]></MusicUrl>
        			<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
   				    </Music>";
        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
        $xmlTpl = "<xml>
    			   <ToUserName><![CDATA[%s]]></ToUserName>
    			   <FromUserName><![CDATA[%s]]></FromUserName>
    			   <CreateTime>%s</CreateTime>
    			   <MsgType><![CDATA[music]]></MsgType>
    					$item_str
				   </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
        			<MediaId><![CDATA[%s]]></MediaId>
    				</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    			   <ToUserName><![CDATA[%s]]></ToUserName>
    			   <FromUserName><![CDATA[%s]]></FromUserName>
    			   <CreateTime>%s</CreateTime>
    			   <MsgType><![CDATA[image]]></MsgType>
    					$item_str
				   </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
        			<MediaId><![CDATA[%s]]></MediaId>
    				</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    			   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
        		   <CreateTime>%s</CreateTime>
    			   <MsgType><![CDATA[voice]]></MsgType>
        			 	$item_str
				   </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
        			<MediaId><![CDATA[%s]]></MediaId>
        			<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        			<Title><![CDATA[%s]]></Title>
        			<Description><![CDATA[%s]]></Description>
   		 			</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    			   <ToUserName><![CDATA[%s]]></ToUserName>
    			   <FromUserName><![CDATA[%s]]></FromUserName>
    			   <CreateTime>%s</CreateTime>
    			   <MsgType><![CDATA[video]]></MsgType>
    					$item_str
				   </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
    			   <ToUserName><![CDATA[%s]]></ToUserName>
    			   <FromUserName><![CDATA[%s]]></FromUserName>
    			   <CreateTime>%s</CreateTime>
    			   <MsgType><![CDATA[transfer_customer_service]]></MsgType>
				   </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复第三方接口消息
    private function relayPart3($url, $rawData)
    {
        $headers = array("Content-Type: text/xml; charset=utf-8");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //字节转Emoji表情
    function bytes_to_emoji($cp)
    {
        if ($cp > 0x10000){       # 4 bytes
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x800){   # 3 bytes
            return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x80){    # 2 bytes
            return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else{                    # 1 byte
            return chr($cp);
        }
    }

    //日志记录
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 1000000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
   
}
?>