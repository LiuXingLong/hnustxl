<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function index(){
		if(empty($_SESSION['dispaly'])){
			if($_GET['wei']==1){
				$_SESSION['dispaly']="none";
			}else{
				$_SESSION['dispaly']="block";
			}
		}
		$this->display();
	}
	public function login(){
		//  需要处理表单  然后判断  在存入session  最后跳转页面	
		$display=$_SESSION['dispaly'];
		session_unset();// 清除  session 中的数据	 但不摧毁 session
		$_SESSION['dispaly']=$display;
		if(IS_POST){
			/**
			 *  注册用户
			 *  
			 *  $data['username']=$_POST['username'];
			 * 	$data['password']=md5($_POST['password']); // md5 加密			
			 *	$data['name']="刘兴龙";
			 *	$data['time']=date('Y-m-d H:i',time());           //获取时间
			 *	$data['ip']=$_SERVER['REMOTE_ADDR']; // 获取服务器的 ip地址 ：get_client_ip();				
			 *	//添加导数据库中
			 *	$User=M('useradmin');
			 *	$User->field('username,password,name,time,ip')->create($data);
			 *	$User->add();
			 */
			
			/*  通过数据库登入   start */			
			 $post=I('post.');
			 $user=M('useradmin');
			 $result=$user->query('select *from useradmin where username=%d',$post['username']);
			 if($result[0]['password']==md5($post['password'])){
			 	$_SESSION['status']=1;
			 	$_SESSION['flag1']=$result[0]['flag1'];
			 	$_SESSION['flag2']=$result[0]['flag2'];
			 	$_SESSION['flag3']=$result[0]['flag3'];
			 	$_SESSION['flag4']=$result[0]['flag4'];
			 	$_SESSION['flag5']=$result[0]['flag5'];
			 	$_SESSION['flag6']=$result[0]['flag6'];
			 	$_SESSION['flag7']=$result[0]['flag7'];
			 	/*
			 	if(!empty($_SESSION['openid'])){
			 		//通过微信登入
			 		$weixin=M('weixin');
			 		$resul=$weixin->query("select *from weixin where openid='%s'",$_SESSION['openid']);
			 		if(empty($resul)){
			 			$data['openid']=$_SESSION['openid'];
			 			$data['username']=$result[0]['username'];
			 			$data['name']=$result[0]['name'];
			 			$data['time']=date('Y-m-d H:i',time());
			 			$data['ip']=$_SERVER['REMOTE_ADDR'];
			 			$weixin->field('openid,username,name,time,ip')->create($data);
			 			$weixin->add();			 			
			 		}
			 	}
			 	*/
			 	$this->redirect("Index/index");
			 }else{
			 	echo "Error";
			 } 			 
			/* 通过数据库登入   end  */						
		}else{
			$this->error('非法操作');
		}
	}
	public function logout(){
		session_unset();
		session_destroy();
		$this->redirect("Login/index");
	}
}
// 注意：因为 loginController 不能检测 session 值所以可以直接访问里面的方法，所以 loginController 里面只能定义 login 和 logout 两个方法