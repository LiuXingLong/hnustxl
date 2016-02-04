<?php
namespace Home\Controller;
use Think\Controller;
class PachongController extends BaseController{
    public function index(){
    	/**
    	 * 模拟登入获取用户信息类
    	 * 参数     数据库中  含有   name 表用户的登入信息表
    	 * 获取的信息都会插入   数据库中的  card 表
    	 * 获取成功的   name 表的 flag 标志改为  1     密码错误  flag 标志改为 2       默认值为0
    	 * 
    	 * 
    	 * insert into name(name,username,card)  select *from idcard where username<1401010107;
    	 * select *from name WHERE flag=1 AND username NOT IN(SELECT username FROM card WHERE flag=0);
		 * update name set flag=0 where flag=1 and username not in(select username from card where flag=0);
    	 * SELECT COUNT(*) FROM NAME WHERE flag=0;
    	 */
    	
    	die;    	
    	$name=M('name');
    	$result=$name->field('username,card')->where("flag=%d",0)->select();	
    	$count=count($result);    	
// 	  	dump($result);   	
//     	die;

    	for($i=0;$i<$count;$i++){    		
    		// 随机选着开多进程      		
    		$t=mt_rand(0,$count-1);
			$post['password']=$post['username']=$result[$t]['username'];  // 学号做密码
// 			$post['password']=substr($result[$t]['card'],12,6); // 省份证后 6 位
// 			$post['password']=substr($result[$t]['card'],10,8); // 省份证后 8位
// 			$post['password']=substr($result[$t]['card'],6,8);  // 出生日期
			
			$da['flag']=2;
			$name->where('username=%d',$post['username'])->save($da);
				
			$result=$name->field('username,card')->where("flag=%d",0)->select();
			$count=count($result);
			
    		$flag=$this->Pa($post);	
    	}
    	echo "完成！";
    }
    public function Pa($post){
    	$Url=array();
    	$flag=$post['username']/10000000%10;
    	if($flag<5){
    		// 本部
    		$Url[1]="http://kdjw.hnust.cn/kdjw/verifycode.servlet";
    		$Url[2]="http://kdjw.hnust.cn/kdjw/";
    		$Url[3]="http://kdjw.hnust.cn/kdjw/Logon.do?method=logon";
    		$Url[4]="http://kdjw.hnust.cn/kdjw/xszhxxAction.do?method=addStudentPic";
    		$Url[5]="http://kdjw.hnust.cn/kdjw/Logon.do?method=logout";
    	}else{
    		// 潇湘
    		$Url[1]="http://xxjw.hnust.cn/xxjw/verifycode.servlet";
    		$Url[2]="http://xxjw.hnust.cn/xxjw/";
    		$Url[3]="http://xxjw.hnust.cn/xxjw/Logon.do?method=logon";
    		$Url[4]="http://xxjw.hnust.cn/xxjw/xszhxxAction.do?method=addStudentPic";
    		$Url[5]="http://xxjw.hnust.cn/xxjw/Logon.do?method=logout";
    	}	
    	$this->yzm($Url);
    	return $this->jwc($post,$Url);  //  密码正确返回   1           错误返回   0
    }
    /**
     * 将登入后的          cookie 和  验证码信息                存入SESSION
     *  
     * @param unknown $Url
     */
    public function yzm($Url){	
	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$Url[1]);
		curl_setopt($ch, CURLOPT_REFERER,$Url[2]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
// 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
// 		curl_setopt($ch, CURLOPT_TIMEOUT,0);
	
		/*   设置代理    start  */
		$IP=array("202.106.16.36:3128");
		$k=array_rand($IP,1);// 获取随机 IP 的下标值
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式	
		/*   设置代理    end  */

		$output = curl_exec($ch);
		curl_close($ch);
		
		//获取cookieStr
		preg_match('/Set-Cookie:(.*);/iU',$output,$matches);
		$cookieStr = $matches[1];
			
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
		$valid = new Valite();
		$validCode=$valid->getResult($verPic); // 验证码值
		
		//设置 session
		session('cookie',$cookieStr);
		session('vercode',$validCode);
		unlink($verPic);// 删除图片    	
	}
	public function jwc($post,$Url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$Url[3]);
		curl_setopt($ch, CURLOPT_REFERER,$Url[2]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
// 		curl_setopt($ch, CURLOPT_TIMEOUT,0);
			
		/*   设置代理    start  */
		$IP=array("202.106.16.36:3128");
		$k=array_rand($IP,1);// 获取随机 IP 的下标值
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式	
		/*   设置代理    end  */
	
		curl_setopt($ch, CURLOPT_POST, 1);
		$postData = "USERNAME=".$post["username"]."&PASSWORD=".$post["password"]."&RANDOMCODE=".session('vercode');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch,CURLOPT_COOKIE,session('cookie'));
		$login = curl_exec($ch);
		
		session_unset();
		session_destroy();
			
		$name=M('name');
		if(strrpos($login,"验证码错误!")){	
			$da['flag']=3;
			$name->where('username=%d',$post['username'])->save($da);
			return 0;
		}
		if(strrpos($login,"该帐号不存在或密码错误,请联系管理员!")){						
			return 0;
		}		
		$da['flag']=1;
		$name->where('username=%d',$post['username'])->save($da);
		
		/*  得到信息    */
		curl_setopt($ch, CURLOPT_URL,$Url[4]);		
		$In = curl_exec($ch);
		
		/*  注销登入    */
		curl_setopt($ch, CURLOPT_URL,$Url[5]);
		curl_exec($ch);		
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
    	$data['birthplace']=substr($info[0][21],5,strlen($info[0][21])-10);
    	$data['status']=substr($info[0][23],5,strlen($info[0][23])-10);
    	$data['phone']=substr($info[0][29],5,strlen($info[0][29])-10);
   	
		$Inf=M(card);
    	$resul=$Inf->field('id')->where('username=%d',$post['username'])->select();
    	if(empty($resul)){
    		$Inf->field('username,password,institute,specialty,class,name,sex,ethnic,time,id,birthplace,status,phone')->create($data);
    		$Inf->add();
    	}
    	
//     	dump($data);   	
		return 1;  
	}
}


/**
 * 验证码识别类
 * @author Administrator
 *
 */
class Valite{
	private $imgPath; //图片的路径
	private $keys; //存放特征码库
	private $data = array(); //图片二值化数据
	private $result = array();
	function __construct() {
		$this->keys = array(
				'2'=>'001111000011111100111000110110000110000000110000001100000011100000111000001110000011000000111111110111111110',
				'b'=>'110000000110000000110000000110111000111111100111001110110000110110000110110000110111001110111111100110111000',
				'v'=>'000000000000000000000000000110001100110001100110001100011011000001011000011011000001110000001110000001110000',
				'n'=>'000000000000000000000000000110111100111111110111000110110000110110000110110000110110000110110000110110000110',
				'z'=>'000000000000000000000000000111111100111111100000011000000111000001110000011100000011000000111111100111111100',
				'1'=>'000110000001110000011110000110110000100110000000110000000110000000110000000110000000110000000110000000110000',
				'x'=>'000000000000000000000000000110001100111011100011001000001110000001110000001110000011011000111011100110001100',
				'3'=>'001111100011111110110000110000000110000111100000111100000001110000000110110000110111001110011111100001111000',
				'm'=>'000000000000000000000000000110111001111111111111001110110001100110001100110001100110001100110001100110001100',
				'c'=>'000000000000000000000000000001111000011111100111001100110000000110000000110000000111001100011111000001111000',
		);
	}
	//获得结果       传入参数    路径   或   url
	public function getResult($path) {
		$this->imgPath = $path;
		$this->getBitArray();
		$this->match();
		return implode($this->result);
	}
	//得到图片二值化序列
	public function getBitArray() {			
		$res = imagecreatefromjpeg($this->imgPath);
		$size = getimagesize($this->imgPath);
		for ($i=0; $i<$size[1]; $i++) {
			for ($j=0; $j<$size[0]; $j++) {
				$rgb = imagecolorat($res, $j, $i);
				$rgbArray = imagecolorsforindex($res, $rgb);
				if(($rgbArray['red']+$rgbArray['green']+$rgbArray['blue'])<300)	{
					$this->data[$i][$j] = 1;					
				}
				else {
					$this->data[$i][$j] = 0;					
				}
			}
		}
	}
	public function match(){
		$percent = 0;
		for ($i=0; $i<4; $i++) {
			$data = $this->getSingleChar($i*10+3, 4, 9, 12); //$data为一个字符提取的二值化序列
			$percentArray = array();
			foreach ($this->keys as $index=>$key) {
				similar_text($data, $key, $percent);			
				$percentArray[$percent] = $index;
			}
			ksort($percentArray);
			$this->result[] = end($percentArray);
		}
	}
	//获取单个字符的二值化序列
	public function getSingleChar($x, $y, $xInc, $yInc) {
		$endX = $x+$xInc;
		$endY = $y+$yInc;
		$temp = $x;
		$bits = '';
		for (; $y<$endY; $y++) {
			for ($x=$temp; $x<$endX; $x++) {
				$bits.=$this->data[$y][$x];
			}
		}
		return $bits;
	}
	
	//建立特征码库   即          建立 $this->keys   数组值 
	static function createCodeLib($imgPath, $x, $y, $xInc, $yInc){
		$res = imagecreatefromjpeg($imgPath);
		$endX = $x+$xInc;
		$endY = $y+$yInc;
		$temp = $x;
		for (; $y<$endY; $y++) {
			for ($x=$temp; $x<$endX; $x++) {
				$rgb = imagecolorat($res, $x, $y);
				$rgbArray = imagecolorsforindex($res, $rgb);
				if(($rgbArray['red']+$rgbArray['green']+$rgbArray['blue'])<300)
					echo 1;
				else
					echo 0;
			}
		}
	}
}