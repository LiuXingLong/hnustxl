<?php
namespace Home\Controller;
use Think\Controller;
use Think\Storage\Driver\File;
class ScoreController extends Controller{
    public function index($post=null){
    	$Url=array();
    	$flag=$post['username']/10000000%10;
    	if($flag<5){
    		// 本部
    		$Url[1]="http://kdjw.hnust.cn/kdjw/verifycode.servlet";
    		$Url[2]="http://kdjw.hnust.cn/kdjw/";
    		$Url[3]="http://kdjw.hnust.cn/kdjw/Logon.do?method=logon";
    		$Url[4]="http://kdjw.hnust.cn/kdjw/xszqcjglAction.do?method=queryxscj";    			
    	}else{
    		// 潇湘
    		$Url[1]="http://xxjw.hnust.cn/xxjw/verifycode.servlet";
    		$Url[2]="http://xxjw.hnust.cn/xxjw/";
    		$Url[3]="http://xxjw.hnust.cn/xxjw/Logon.do?method=logon";
    		$Url[4]="http://xxjw.hnust.cn/xxjw/xszqcjglAction.do?method=queryxscj";
    	}
    	$flag=$this->yzm($Url);
        if($flag==-1){
            return -1;
        }
    	return $this->jwc($post,$Url);
	}
	public function yzm($Url){
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
		
		/*
		 生成临时文件来保存cookie
		 $ckFile = tempnam('temp','cookie');
		 curl_setopt($ch, CURLOPT_COOKIEJAR, $ckFile);
		*/
		$output = curl_exec($ch);
		curl_close($ch);
	
		//获取cookieStr
        $cookieStr=="";
		preg_match('/Set-Cookie:(.*);/iU',$output,$matches);
		$cookieStr = $matches[1];
        if($cookieStr==""){
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
		
		
		
// 		$fp = fopen($verPic, 'w');
// 		fwrite($fp, $picContent, strlen($picContent));
// 		fclose($fp);	
		
		// 验证码识别
		$valid = new ValiteController();
		$valid->setImage($verPic);	
		$validCode=$valid->getResult(); // 验证码值
				
		//设置 session	
		session('cookie',$cookieStr);
		session('vercode',$validCode); // "1234" $validCode
		
		$file->unlink($verPic,"jpg");
		//unlink($verPic);// 删除图片
	}	
	public function jwc($post,$Url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$Url[3]);
		curl_setopt($ch, CURLOPT_REFERER,$Url[2]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		/*   设置代理    start  */
		
		$IP=array("202.106.16.36:3128");
		$k=array_rand($IP,1);// 获取随机 IP 的下标值
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
		//curl_setopt($ch, CURLOPT_PROXYPORT, 80); //代理服务器端口
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
		
		/*   设置代理    end  */
				
		curl_setopt($ch, CURLOPT_POST, 1);	
		$postData = "USERNAME=".$post["username"]."&PASSWORD=".$post["password"]."&RANDOMCODE=".session('vercode');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["cookie"]);
		curl_setopt($ch,CURLOPT_COOKIE,session('cookie'));
		$login = curl_exec($ch);
		
		while(strrpos($login,"验证码错误!")){			
			$this->index($post);
		}	
		if(strrpos($login,"该帐号不存在或密码错误,请联系管理员!")){			
			return 0;		
		}else{
			curl_setopt($ch, CURLOPT_URL,$Url[4]);
			$grad = curl_exec($ch);
			
			$pattern='/color="#FF0000">.{0,10}<\//';
			preg_match_all($pattern,$grad,$cnt);
			$cnt=substr($cnt[0][0],16,strlen($cnt[0][0])-18);
			//var_dump($cnt);
				
			$pattern='/value="1\/.{2}>/';
			preg_match_all($pattern,$grad,$page);
			$page=substr($page[0][0],9,strlen($page[0][0])-11);
			//var_dump($page);
			
			if(empty($page)){
				$page=1;
			}
			
			$Grade=M('grade');
			$count=$Grade->where("username='%s'",$post["username"])->count();// 查询满足条件的总记录数
			
			if($cnt>$count){
				$data=array();
				for($k=1,$j=0;$k<=$page;$k++){
					curl_setopt($ch, CURLOPT_URL,$Url[4]);
					$postData = "PageNum=".$k;
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
					$grad=curl_exec($ch);
			
					$pattern='/(?:"| )>.{1,86}<\/td>/';
					preg_match_all($pattern,$grad,$out);
			
					$len=count($out[0]);
					$len=$len-($len-2)%13-2;
			
					for($i=2;$i<$len;$i+=13,$j++){
						$data[$j]["username"]=substr($out[0][$i+1],2,strlen($out[0][$i+1])-7);
						$data[$j]["name"]=substr($out[0][$i+2],2,strlen($out[0][$i+2])-7);
						$data[$j]["time"]=substr($out[0][$i+3],2,strlen($out[0][$i+3])-7);
						$data[$j]["subject"]=substr($out[0][$i+4],2,strlen($out[0][$i+4])-7);
						$data[$j]["score"]=substr($out[0][$i+5],2,strlen($out[0][$i+5])-7);
						$data[$j]["xuefen"]=substr($out[0][$i+10],2,strlen($out[0][$i+10])-7);
						$data[$j]["id"]=$j+1;
							
						if($data[$j]["score"]=="&nbsp;"){
							$data[$j]["score"]="缺考";
						}
						if($data[$j]["score"]=="不及格"||(float)$data[$j]["score"]<60){
							$data[$j]["flag"]=0;
						}
						if($data[$j]["score"]=="及格"||$data[$j]["score"]=="中"||$data[$j]["score"]=="良"||$data[$j]["score"]=="优"||(float)$data[$j]["score"]>=60){
							$data[$j]["flag"]=1;
						}
					}
				}
				curl_close($ch);
				if(!empty($data[0]["subject"])){               
	                 $Grade->where("username='%s'",$post["username"])->delete();
	                 for($i=0;$i<$cnt;$i++){               
	                    $Grade->field('id,name,username,time,subject,score,xuefen,flag')->create($data[$i]);
	                    $Grade->add();              
					 }               
	            }
				$_SESSION['name']=$data[0]['name'];
				$_SESSION['name1']=$data[0]['name'];
			}else{
				$name=$Grade->field('name')->where("username='%s' and id=%d",$post["username"],1)->select();
				$_SESSION['name']=$name[0]['name'];
				$_SESSION['name1']=$name[0]['name'];
			}
			//var_dump($data);
			return 1;
		}	
	}
}