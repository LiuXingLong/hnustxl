<?php
namespace Home\Controller;
use Think\Controller;
class InfoController extends BaseController{
    public function index($post){
    	$flag=$post['username']/10000000%10;
    	if($flag<5){
    		//本部
    		$Url="http://kdjw.hnust.cn/kdjw/xszhxxAction.do?method=addStudentPic";
    	}else{
    		//潇湘
    		$Url="http://xxjw.hnust.cn/xxjw/xszhxxAction.do?method=addStudentPic";
    	}
    	$ch = curl_init();   	
    	curl_setopt($ch, CURLOPT_URL,$Url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	
    	/*   设置代理    start  */    	
    	$IP=array("202.106.16.36:3128");
    	$k=array_rand($IP,1);// 获取随机 IP 的下标值
    	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
    	curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
    	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式    	
    	/*   设置代理    end  */
    	
    	curl_setopt($ch,CURLOPT_COOKIE,session('cookie'));
    	$In = curl_exec($ch);
    	curl_close($ch);
    	
//     	$txt="./Public/temp/info.txt";
//     	$fp=fopen($txt,'w');
//     	fwrite($fp,$Info);
//     	fclose($fp);

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
    	
    	$Inf=M(info);
    	$Inf->field('username,password,institute,specialty,class,name,sex,ethnic,time,id,Birthplace,Status,phone')->create($data);
    	$Inf->add();
    	// var_dump($data);    	
	}
}