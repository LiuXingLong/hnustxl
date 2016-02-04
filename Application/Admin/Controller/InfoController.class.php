<?php
namespace Admin\Controller;
use Think\Controller;
class InfoController extends BaseController{
	var $count=0;
	public function index($post=null){		
		$Url=array();
		$flag=$post['username']/10000000%10;
		if($flag<5){
			// 本部
			$Url[1]="http://kdjw.hnust.cn/kdjw/verifycode.servlet";
			$Url[2]="http://kdjw.hnust.cn/kdjw/";
			$Url[3]="http://kdjw.hnust.cn/kdjw/Logon.do?method=logon";
			$Url[4]="http://kdjw.hnust.cn/kdjw/xszhxxAction.do?method=addStudentPic";
		}else{
			// 潇湘
			$Url[1]="http://xxjw.hnust.cn/xxjw/verifycode.servlet";
			$Url[2]="http://xxjw.hnust.cn/xxjw/";
			$Url[3]="http://xxjw.hnust.cn/xxjw/Logon.do?method=logon";
			$Url[4]="http://xxjw.hnust.cn/xxjw/xszhxxAction.do?method=addStudentPic";
		}
		$fg=$this->yzm($Url);
		if($fg==-1){
			return -1;
		}
		return $this->info($post,$Url);
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
		$file=new \Think\Storage\Driver\File();
		$file->put($verPic, $picContent,"jpg");
	
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
    public function info($post,$Url){
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
    	
    	if($this->count==0){
    		$user=M('user');
    		$result=$user->field('password')->where("username=%d",$post['username'])->select();   		
    		if(!empty($result)){
    			$post["password"]=$result[0]['password'];
    		}else{
    			++$this->count;
    		}
    	}
    	if($this->count==1){		
    		$post["password"]=$post["username"];   		
    	}
    	if($this->count==2){    	
    		$post["password"]=substr($post['id'],12,6);
    	}
    	if($this->count==3){   		
    		$post["password"]=substr($post['id'],6,8);
    	}
    	if($this->count==4){
    		$post["password"]=substr($post['id'],10,8);
    	}    	
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
    		if($this->count<=3){
    			++$this->count;
    			$this->index($post);
    		}else{
    			return 0;
    		}    		
    	}else{

    		curl_setopt($ch, CURLOPT_URL,$Url[4]);
    		$In = curl_exec($ch);
    		curl_close($ch);
    		
    		$pattern='/(?:(?:eaeaea;|"35)">.{0,100}<\/td>|.{0,6}：.{0,100}&nbsp;&nbsp;&nbsp;&nbsp;)/';
    		preg_match_all($pattern,$In,$info);
    		
    		$data=array();
    		$data['username']=$post['username'];
    		$data['password']=$post['password'];
    		$data['institute']=substr($info[0][0],9,strlen($info[0][0])-33);
    		$data['specialty']=substr($info[0][1],9,strlen($info[0][1])-33);
    		$data['class']=substr($info[0][3],9,strlen($info[0][3])-33);
    		$data['name']=substr($info[0][7],5,strlen($info[0][7])-10);
    		$data['sex']=substr($info[0][9],5,strlen($info[0][9])-10);
    		$data['ethnic']=substr($info[0][11],5,strlen($info[0][11])-10);
    		$data['time']=substr($info[0][13],5,strlen($info[0][13])-10);
    		$data['id']=substr($info[0][15],5,strlen($info[0][15])-10);
    		$data['Birthplace']=substr($info[0][21],5,strlen($info[0][21])-10);
    		$data['Status']=substr($info[0][23],5,strlen($info[0][23])-10);
    		$data['phone']=substr($info[0][29],5,strlen($info[0][29])-10);
    		if(!empty($data['id'])){
    			$Inf=M(info);
    			$Inf->field('username,password,institute,specialty,class,name,sex,ethnic,time,id,Birthplace,Status,phone')->create($data);
    			$Inf->add();
    		}
//     		dump($data);
    		return 1;
    	}	
	}
}