<?php
namespace Home\Controller;
use Think\Controller;
class TableController extends BaseController{
    public function index(){
    	$leng=$_SESSION['username']/10000000%10;
    	if($leng<5){
    		// 本部
    		$Url="http://kdjw.hnust.cn/kdjw/jiaowu/pkgl/llsykb/llsykb_kb.jsp";
    	}else{
    		// 潇湘
    		$Url="http://xxjw.hnust.cn/xxjw/jiaowu/pkgl/llsykb/llsykb_kb.jsp";
    	}   	
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$Url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	
    	$IP=array("202.106.16.36:3128");
    	$k=array_rand($IP,1);// 获取随机 IP 的下标值
    	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
    	curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
    	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
    	  	
    	curl_setopt($ch,CURLOPT_POST,1);
    	curl_setopt($ch,CURLOPT_COOKIE,session('cookie'));    	   	
    	$time="2015-2016-2";   	
    	$name=urlencode(session('name'));     	
    	$postData = "type=xs0101&isview=1&zc=&xnxq01id=".$time."&xs0101xm=".$name."&xs0101id=".session('username')."&sfFD=1";
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);	
    	$table=curl_exec($ch);
    	curl_close($ch);

    	$data=array();
    	
    	$pattern='/(?:"kbcontent".{10,600}<\/div>|red;">.{1,})/';
		preg_match_all($pattern,$table,$out);
		$count=count($out[0]);
				
		$user=M('table');
		$usertable=$user->where('username=%d',$_SESSION['username'])->select();
				
		$g=0;
		if(empty($usertable[0])){
			$g=1; // 插入数据库
		}else{
			$con=0;
			for($i=1;$i<36;$i++){
				if($usertable[0]["t".$i]!="&nbsp;"&&!empty($usertable[0]["t".$i])) $con++;  //$usertable[0][$i]!="&nbsp;"
			}
			if($usertable[0]['note']!="&nbsp;"&&!empty($usertable[0]['note'])) $con++;
			if($con<$count){
				$g=2; // 更新数据库
			}elseif($con==$count){
				$g=0; // 不需要更改操作
			}else{
				$g=0;
			}
		}	
		if($g!=0){
			// 需要更新课表数据
			$pattern='/(?:"kbcontent".{3,600}<\/div>|red;">.{1,})/';
			preg_match_all($pattern,$table,$out);
			$count=count($out[0])-1;
			for($i=0,$j=1,$k=1;$i<$count;$i+=8,$j++){
				$flag[1]=strpos($out[0][$i],">")+1;
				$flag[2]=strpos($out[0][$i+1],">")+1;
				$flag[3]=strpos($out[0][$i+2],">")+1;
				$flag[4]=strpos($out[0][$i+3],">")+1;
				$flag[5]=strpos($out[0][$i+4],">")+1;
				$flag[6]=strpos($out[0][$i+5],">")+1;
				$flag[7]=strpos($out[0][$i+6],">")+1;				
				$da["t".$k++]=$data[$j][1]=substr($out[0][$i],$flag[1],strlen($out[0][$i])-$flag[1]-6);
				$da["t".$k++]=$data[$j][2]=substr($out[0][$i+1],$flag[2],strlen($out[0][$i+1])-$flag[2]-6);
				$da["t".$k++]=$data[$j][3]=substr($out[0][$i+2],$flag[3],strlen($out[0][$i+2])-$flag[3]-6);
				$da["t".$k++]=$data[$j][4]=substr($out[0][$i+3],$flag[4],strlen($out[0][$i+3])-$flag[4]-6);
				$da["t".$k++]=$data[$j][5]=substr($out[0][$i+4],$flag[5],strlen($out[0][$i+4])-$flag[5]-6);
				$da["t".$k++]=$data[$j][6]=substr($out[0][$i+5],$flag[6],strlen($out[0][$i+5])-$flag[6]-6);
				$da["t".$k++]=$data[$j][7]=substr($out[0][$i+6],$flag[7],strlen($out[0][$i+6])-$flag[7]-6);				
				if($j==5){
					$flag[8]=strpos($out[0][$count],">")+1;
					$da['note']=$data['note']=substr($out[0][$count],$flag[8]);
				}
			}
			if($g==1){			
				$da['username']=$_SESSION['username'];
				$user->create($da);
				$user->add();
// 				echo "插入数据";
			} 
			if($g==2){
				$user->where('username=%d',$_SESSION['username'])->save($da);
// 				echo "更新数据";
			} 				
// 			dump($da);
		} 	
	}
}