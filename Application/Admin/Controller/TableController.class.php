<?php
namespace Admin\Controller;
use Think\Controller;
class TableController extends BaseController{
	public function index($post=null){	
		$Url=array();	
		$flag=$post['username']/10000000%10;
		if($flag<5){
			// 本部
			$Url[1]="http://kdjw.hnust.cn/kdjw/verifycode.servlet";
			$Url[2]="http://kdjw.hnust.cn/kdjw/";
			$Url[3]="http://kdjw.hnust.cn/kdjw/Logon.do?method=logon";
			$Url[4]="http://kdjw.hnust.cn/kdjw/jiaowu/pkgl/llsykb/llsykb_kb.jsp";
			$cookie=$_SESSION['cookieT'][1];
		}else{
			// 潇湘
			$Url[1]="http://xxjw.hnust.cn/xxjw/verifycode.servlet";
			$Url[2]="http://xxjw.hnust.cn/xxjw/";
			$Url[3]="http://xxjw.hnust.cn/xxjw/Logon.do?method=logon";
			$Url[4]="http://xxjw.hnust.cn/xxjw/jiaowu/pkgl/llsykb/llsykb_kb.jsp";
			$cookie=$_SESSION['cookieT'][2];
		}
		$data=$this->table($post,$Url);		
		if($data==0){	
			$fg=$this->log($post,$Url);
			if($fg==-1){			
				return -1;  		// 教务网异常
			}elseif($fg==1){				
				return $this->table($post,$Url);  // 成功登入  
			}				
		}else{
			return $data;
		}	
	}
	public function log($post,$Url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$Url[1]);
		curl_setopt($ch, CURLOPT_REFERER,$Url[2]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
	
		/*   设置代理    start  */
	
		$IP=array("202.106.16.36:3128");
		$k=array_rand($IP,1);// 获取随机 IP 的下标值
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
		//curl_setopt($ch, CURLOPT_PROXYPORT, 80); //代理服务器端口
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	
		/*   设置代理    end  */
	
		$output = curl_exec($ch);

		//获取cookieStr
		$cookieStr="";
		preg_match('/Set-Cookie:(.*);/iU',$output,$matches);
		$cookieStr = $matches[1];
		if($cookieStr==""){
			curl_close($ch);
			return -1;
		}			
		//获取图片内容
		$pos = strrpos($output,"JFIF");
		$picContent = substr($output,$pos-6);
	
		//保存图片
		$picName = md5(time());
		$verPic = "./Public/temp/"."$picName".".jpg";
		$file=new \Think\Storage\Driver\File();
		$file->put($verPic, $picContent,"jpg");
	
		// 验证码识别
		$valid = new ValiteController();
		$valid->setImage($verPic);
		$validCode=$valid->getResult(); // 验证码值
		$file->unlink($verPic,"jpg");   //unlink($verPic);// 删除图片
		
		curl_setopt($ch, CURLOPT_URL,$Url[3]);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		 		 
		$flag=$post['username']/10000000%10;
		if($flag<5){
			$_SESSION['cookieT'][1]=$cookieStr;
			$user=array(1001010109,1001020115,1001040102,1002010331,1002010430,1002010431,1002010706,1002010707,1002010807,1002040211);
			$pass=array("254974","080531","088116","182773","025914","124699","152339","204113","256659","289113");
			$rand=mt_rand(0,9);
			$username=$user[$rand];
			$password=$pass[$rand];			
		}else{
			$_SESSION['cookieT'][2]=$cookieStr;
			$user=array(1052010130,1052010332,1052010513,1052010628,1052010632,1052010701,1052010830,1052011113,1053010112,1053010126);
			$pass=array("01581X","098333","190018","257219","220319","190015","13651X","283014","060715","256510");			
			$rand=mt_rand(0,9);			
			$username=$user[$rand];
			$password=$pass[$rand];	
		}
		$postData = "USERNAME=".$username."&PASSWORD=".$password."&RANDOMCODE=".$validCode;
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch,CURLOPT_COOKIE,$cookieStr);
		$login = curl_exec($ch);
		curl_close($ch);		
		if(strrpos($login,"验证码错误!")||strrpos($login,"该帐号不存在或密码错误,请联系管理员!")){			
			$this->log($post,$Url);
		}else{
			return 1;
		}
	}
    public function table($post,$Url){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$Url[4]);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //  将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	
    	/*   设置代理    start  */    	
    	$IP=array("202.106.16.36:3128");
    	$k=array_rand($IP,1);// 获取随机 IP 的下标值
    	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
    	curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
    	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式   	
    	/*   设置代理    end  */

    	$flag=$post['username']/10000000%10;
    	if($flag<5){		
    		$cookie=$_SESSION['cookieT'][1];
    	}else{
    		$cookie=$_SESSION['cookieT'][2];
    	}
    	if(empty($cookie)){
    		curl_close($ch);
    		return 0;
    	}
    	curl_setopt($ch,CURLOPT_COOKIE,$cookie);
    	curl_setopt($ch,CURLOPT_POST,1);
    	
    	$time="2015-2016-2";
    	$postData = "type=xs0101&isview=1&zc=&xnxq01id=".$time."&xs0101xm=&xs0101id=".$post['username']."&sfFD=1";
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  	
		$table = curl_exec($ch);
    	curl_close($ch);
    	
    	if(strrpos($table,"用户没有登录，请重新登录！")||strrpos($table,"您登录后过长时间没有操作或您的用户名已经在别处登录！")){
    		// cookieT  失效
    		return 0;
    	}else{
    		$pattern='/nt(?:"|" )>.{1,}</';
    		preg_match_all($pattern,$table,$out);
    		
    		// var_dump($out);
    		// $pattern='/(?:nt"|nt" |br)>.{1,80}title=\'老/';  //	课程
    		// $pattern='/\/>.{1,18}\(/';    				//	周次
    		// $pattern='/室\'>.{1,30}<\/font>/';     	            教室
    		
    		$pattern='/(?:nt"|nt" |br)>.{1,80}title=\'老|\/>.{1,18}\(|室\'>.{1,30}<\/font>/';
    		$count=count($out[0]);  
    		if($count<1) return 0;
    		$data=array();
    		$data[1][0]="星期一";
    		$data[2][0]="星期二";
    		$data[3][0]="星期三";
    		$data[4][0]="星期四";
    		$data[5][0]="星期五";
    		$data[6][0]="星期六";
    		$data[7][0]="星期日";
    		for($i=0,$k=1,$fg=1;$i<$count;$i++,$k++,$fg++){
    			preg_match_all($pattern,$out[0][$i],$out1);
    			if(!empty($out1[0])){
    				$cont=count($out1[0]);
    				for($j=0;$j<$cont;$j++){
    					$str=$out1[0][$j];
    					if($j%3==0){
    						$start=strrpos($out1[0][$j],"-");
    						if($start){
    							$start+=5;
    							$end=stripos($out1[0][$j],"f")-5;
    						}else{
    							$start=stripos($out1[0][$j],">")+1;
    							$end=stripos($out1[0][$j],"<");
    						}
    					}elseif($j%3==1){
    						$start=stripos($out1[0][$j],">")+1;
    						$end=stripos($out1[0][$j],"(");
    					}else{
    						$start=stripos($out1[0][$j],">")+1;
    						$end=stripos($out1[0][$j],"<");
    					}
    					$len=$end-$start;
    					if($j%3==1){
    						$out2[$k][]=substr($str,$start,$len)."周";
    					}else{
    						$out2[$k][]=substr($str,$start,$len);
    					}
    				}
    			}else{
    				$out2[$k]=null;
    			}
    			if($fg==7){
    				$fg=0;$i++;
    			}
    			if($k%7==0){
    				$data['7'][$k/7]=$out2[$k];
    			}else{
    				$data[$k%7][$k/7+1]=$out2[$k];
    			}
    		}
    		$pattern='/red;">&.{1,};/';
    		preg_match_all($pattern,$table,$note);
    		$data[7]['note']=substr($note[0][0],6);
    		//  横向数据 var_dump($out2);
    		return $data;
    	}    		
	}
}