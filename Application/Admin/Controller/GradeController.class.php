<?php
namespace Admin\Controller;
use Think\Controller;
class GradeController extends BaseController{
    public function index($post=null){
    	$ID=M('idcard');
    	$result=$ID->field('name,card')->where("username=%d",$post['username'])->select();
    	if(!empty($result)){
    		$Url=array();
    		$post['name']=$result[0]['name'];
    		$post['card']=$result[0]['card'];    		
    		$_SESSION['username1']=$post['username'];
    		$_SESSION['name1']=$post['name']; 
    		$username=$post['username'];  		
    		$_SESSION["'{$username}'"]=1; // 标记查过成绩的人
    		
    		$flag=$post['username']/10000000%10;
    		if($flag<5){
    			// 本部
    			$Url[1]="http://kdjw.hnust.cn/kdjw/verifycode.servlet";
    			$Url[2]="http://kdjw.hnust.cn/kdjw/";
    			$Url[3]="http://kdjw.hnust.cn/kdjw/xscjcx_check.jsp";
    			$Url[4]="http://kdjw.hnust.cn/kdjw/xscjcx.jsp?yzbh=";
    		}else{
    			// 潇湘
    			$Url[1]="http://xxjw.hnust.cn/xxjw/verifycode.servlet";
    			$Url[2]="http://xxjw.hnust.cn/xxjw/";
    			$Url[3]="http://xxjw.hnust.cn/xxjw/xscjcx_check.jsp";
    			$Url[4]="http://xxjw.hnust.cn/xxjw/xscjcx.jsp?yzbh=";
    		}
    		$flag=$this->yzm($Url);
    		if($flag==-1){    			
    			return -1;
    		}
    		return $this->jwc($post,$Url);
    	}
    	return 0; // 系统无改用户信息
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
		$cookieStr="";
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
		$fp = fopen($verPic, 'w');
		fwrite($fp, $picContent, strlen($picContent));
		fclose($fp);
		
		// 验证码识别
		$valid = new ValiteController();
		$valid->setImage($verPic);	
		$validCode=$valid->getResult(); // 验证码值
				
		//设置 session	
		session('cookie2',$cookieStr);
		session('vercode2',$validCode); // "1234" $validCode		
		unlink($verPic);// 删除图片
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
		$postData = "xsxm=".$post["name"]."&xssfzh=".$post["card"]."&yzm=".session('vercode2');		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["cookie"]);
		curl_setopt($ch,CURLOPT_COOKIE,session('cookie2'));
		$login = curl_exec($ch);
			
		while(strrpos($login,"yzm_cuowu")||strrpos($login,"yzm_guoqi")){			
			$this->index($post);
		}	
			
		$pattern='/[0-9a-z]{1,32}/';
		preg_match_all($pattern,$login,$str);	
		$Url[4]=$Url[4].$str[0][0];
		
		curl_setopt($ch, CURLOPT_URL,$Url[4]);
		$grad = curl_exec($ch);
		curl_close($ch);
				  
// 将网页写到文件中
// $verPic = "./Public/temp/1.txt";
// $fp = fopen($verPic, 'w');
// fwrite($fp, $grad);
// fclose($fp);
				
		// 获取科目数
		$pattern='/&nbsp;.{1,100}<\/td>/';
		preg_match_all($pattern,$grad,$out);
		$cont=count($out[0]);
		
		$Grade=M('gradejz');
		$count=$Grade->where("username='%s'",$post["username"])->count();// 查询满足条件的总记录数
		
		if($cont>$count){
			// 获取所有科目数据
			$pattern='/>.{0,}<\/td>/';
			preg_match_all($pattern,$grad,$out);
			
			$data=array();
			$cnt=count($out[0]);
			for($i=18,$j=0;$i<$cnt;$j++){
				$page=substr($out[0][$i],1,strlen($out[0][$i])-6);
				$pattern='/[0-9]{4}-[0-9]{4}-[0-9]{1}/';
				preg_match_all($pattern,$page,$time);
				if(!empty($time[0][0])){
					$data[$j]["username"]=$post['username'];
					$data[$j]["name"]=$post['name'];
					$data[$j]["time"]=$time[0][0];
					$data[$j]["subject"]=substr($out[0][$i+1],7,strlen($out[0][$i+1])-12);
					$data[$j]["score"]=substr($out[0][$i+3],1,strlen($out[0][$i+3])-6);
					$data[$j]["xuefen"]=substr($out[0][$i+2],1,strlen($out[0][$i+2])-6);
					$data[$j]["id"]=$j+1;
					if(empty($data[$j]["score"])&&$data[$j]["score"]!="0"){
						$data[$j]["score"]="缺考";
					}
					if($data[$j]["subject"][0]=="s"){
						$data[$j]["subject"]=substr($data[$j]["subject"],3,strlen($data[$j]["subject"])-3);
					}
					if($data[$j]["score"]=="不及格"||(float)$data[$j]["score"]<60){
						$data[$j]["flag"]=0;
					}
					if($data[$j]["score"]=="及格"||$data[$j]["score"]=="中"||$data[$j]["score"]=="良"||$data[$j]["score"]=="优"||(float)$data[$j]["score"]>=60){
						$data[$j]["flag"]=1;
					}
					$i+=5;
				}else{
					$data[$j]["username"]=$post['username'];
					$data[$j]["name"]=$post['name'];
					$data[$j]["time"]=$data[$j-1]["time"];
					$data[$j]["subject"]=substr($out[0][$i],7,strlen($out[0][$i])-12);
					$data[$j]["score"]=substr($out[0][$i+2],1,strlen($out[0][$i+2])-6);
					$data[$j]["xuefen"]=substr($out[0][$i+1],1,strlen($out[0][$i+1])-6);
					$data[$j]["id"]=$j+1;
					if(empty($data[$j]["score"])&&$data[$j]["score"]!="0"){						
						$data[$j]["score"]="缺考";
					}
					if($data[$j]["subject"][0]=="s"){
						$data[$j]["subject"]=substr($data[$j]["subject"],3,strlen($data[$j]["subject"])-3);
					}
					if($data[$j]["score"]=="不及格"||(float)$data[$j]["score"]<60){
						$data[$j]["flag"]=0;
					}
					if($data[$j]["score"]=="及格"||$data[$j]["score"]=="中"||$data[$j]["score"]=="良"||$data[$j]["score"]=="优"||(float)$data[$j]["score"]>=60){
						$data[$j]["flag"]=1;
					}
					$i+=4;
				}
			}
			if(!empty($data[0]["subject"])){
				$Grade->where("username='%s'",$post["username"])->delete();
				for($i=0;$i<$cont;$i++){
					$Grade->field('id,name,username,time,subject,score,xuefen,flag')->create($data[$i]);
					$Grade->add();
				}
			}	
		}	
		return 1; // 查询成功
	}
}