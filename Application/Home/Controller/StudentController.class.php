<?php
namespace Home\Controller;
use Think\Controller;
class StudentController extends BaseController{
    public function index($year=null){
    	/**该类用来抓取基本学生信息  仅包含    学号  姓名   年级   学院 专业  班级  性别 
    	 * 调用方式  通过登入一个学生账号    然后调用这个控制器的 index 方法
    	 * 
    	 * 调用时需要注意的是:
    	 * 1.需要传入要抓取年级的信息    即  $year  如  $year=2013;
    	 * 2.该类仅能查询   登入账号的     学院学生信息     学院仅仅分为两类   1 潇湘   2 本部
    	 * 3.服务器方面问题：在抓取大量数据时可能会需要很长时间    所以需要调整    Apache   服务器里面 的　　ＰＨＰ　配置信息　PHP.ini　 (最长运行时间以秒为单位     默认为30秒) max_execution_time
    	 *　4.使用时可先开启页面查看下信息确认无误时再开始抓取信息
    	 *
    	 */
    	   	
    	die; 	
    	$flag=$_SESSION['username']/10000000%10;
    	if($flag<5){
    		// 本部
    		$x=1;
    		$Url="http://kdjw.hnust.cn/kdjw/common/xs0101_select.jsp?id=xs0101id&name=xs0101xm&type=1&where=";
    	}else{
    		// 潇湘
    		$x=2;
    		$Url="http://xxjw.hnust.cn/xxjw/common/xs0101_select.jsp?id=xs0101id&name=xs0101xm&type=1&where=";
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
    	
    	$year=2015;
    	$postData = "check_object_id=&check_object_name=&Field1=ksnd&HH1=like&SValue1=".$year."&AndOr1=and&Field2=dwmc&HH2=like&SValue2=&where1=null&where2=+1%3D1++and+%28ksnd+like+%5E%25".$year."%25%5E+%29&OrderBy=&keyCode=%23%21@FnBROF9jQi9VK1dzV2UXcwBvBjRDe1Jg&isOutJoin=false&PageNum=";
    
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);	
    	$table=curl_exec($ch);

    	// 总页数
    	$pattern='/value="1\/.{0,5}>/';
		preg_match_all($pattern,$table,$page);
		$page=substr($page[0][0],9,strlen($page[0][0])-11);

		/* 页面      总页数       显示 信息*/
// 		echo $table;
// 		dump($page);
// 		die;

		$Stu=M('student');
	
		$data=array();	
		for($k=1,$j=0;$k<=$page	;$k++){
			$post=$postData.$k;			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		    $table=curl_exec($ch);		
			//  每页数据量
			$pattern='/l" >.{1,100}<\/td>/';
			preg_match_all($pattern,$table,$student);
			$count=count($student[0]);		
			
			for($i=0;$i<$count;$i+=7,$j++){
				$data[$j]['institute']=substr($student[0][$i],4,strlen($student[0][$i])-9);
				$data[$j]['specialty']=substr($student[0][$i+1],4,strlen($student[0][$i+1])-9);
				$data[$j]['year']=substr($student[0][$i+2],4,strlen($student[0][$i+2])-9);
				$data[$j]['class']=substr($student[0][$i+3],4,strlen($student[0][$i+3])-9);
				$data[$j]['username']=substr($student[0][$i+4],4,strlen($student[0][$i+4])-9);
				$data[$j]['name']=substr($student[0][$i+5],4,strlen($student[0][$i+5])-9);
				$data[$j]['sex']=substr($student[0][$i+6],4,strlen($student[0][$i+6])-9);
				$data[$j]['flag']=$x;
				
				$resul=$Stu->field('username')->where('username=%d',$data[$j]['username'])->select();
				if(empty($resul)){
					$Stu->field('institute,specialty,year,class,username,name,sex,flag')->create($data[$j]);
					$Stu->add();
				}	
			}
			$_SESSION['count']=$k;		
		}		
		// 运行完后输出读取到了的页号		
		echo "以读到了".$_SESSION['count'];			
    	curl_close($ch);
	}
}