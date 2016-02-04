<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function index(){
		$this->display();
	}
	public function login(){
		//  需要处理表单  然后判断  在存入session  最后跳转页面				
		session_unset();// 清除  session 中的数据	 但不摧毁 session
		if(IS_POST){
            /* 普通的 post 获取 			
			$_POST['username'];
			md5($_POST['password']); // md5 加密
			
			I('post.'); // I 方法对输入数据过滤  获取怎个 post 数据
			I('post.username'); // 仅获取 username
			
			$Model->where("id=%d and username='%s' and xx='%f'",$id,$username,$xx)->select();  //查询防注入
			$model->query('select * from user where id=%d and status=%d',$id,$status); //查询防注入
				
			$data['time']=time();           //获取时间
			$data['ip']=get_client_ip();    //获取IP
			
			//添加导数据库中
			$User=M('user');
			$User->field('username,password,time,ip')->create($data);
			$User->add();
												
			*/
			
			$post=I('post.');
					
			/*  通过数据库登入   start  
		    $user=M('user');	    
			$result=$user->query('select password from user where username=%d',$post['username']);		
			if(!empty($result)){
				if($result[0]['password']==md5($post['password'])){
					$_SESSION['username']= $post['username'];	
					$this->redirect("Index/index");//$this->success('',U('Index/index'),'0');
				}else{
					$this->error('用户名或密码错误！');
				}				
			}else{
				$this->error('用户名或密码错误！');
			}
			       通过数据库登入   end  */
			
			//检测提交数据
			if(empty($post['username'])||empty($post[password])){
				$this->error('用户名或密码不能为空！');
				exit();
			}
			if(strlen($post['username'])!=10){
				$this->error('用户名有误！');
				exit();
			}
			$login=new ScoreController();            
            $flag=$login->index($post);            
            if($flag==-1){              
                session_unset();
				session_destroy();
				$this->error('抱歉网站或教务网异常,请稍后再试！');
                exit();              
            }else if($flag==1){ 
				// 添加用户名  和 密码 信息
				$User=M('user');
				$result=$User->query('select *from user where username=%d',$post['username']);				
				if(empty($result)){
					$data['username']=$post['username'];
					$data['password']=$post['password'];				
					$data['time']=date('Y-m-d',time()); 					
					$data['ip']=$_SERVER['REMOTE_ADDR']; // 获取服务器的 ip地址 ：get_client_ip();
					$data['flag']=0;
					$data['cnt']=2;
					$data['count']=2;
					$User->field('username,password,time,ip,flag,cnt,count')->create($data);
					$User->add();
				}elseif($post['password']!=$result[0]['password']){
					/**
					 * 修改密码后进行密码修改
					 */			
					//删除在更新
// 					$User->where("username='%s'",$post["username"])->delete();
// 					$data['username']=$post['username'];
// 					$data['password']=$post['password'];
// 					$data['time']=date('Y-m-d',time());
// 					$data['ip']=get_client_ip();
// 					$User->field('username,password,time,ip')->create($data);
// 					$User->add();											
					//直接根据条件更新					
					$data['password']=$post['password'];
					$data['time']=date('Y-m-d',time());	
					$data['ip']=$_SERVER['REMOTE_ADDR']; //获取客户端IP  更新 ip						
					$User->where('username=%d',$post['username'])->save($data);					
				}else{
					/* 不同天登入恢复查询其他人次数    */
					$time=date('Y-m-d',time());
					if($result[0]['time']!=$time){
						$data1['cnt']=$result[0]['count'];
						$data1['time']=$time;
						$User->where('username=%d',$post['username'])->save($data1);
					}	
				}				
				$_SESSION['username']= $post['username'];
				$_SESSION['username1']= $post['username'];
				
				// 添加用户资料信息
				$Info=M('info');
				$resul=$Info->query('select password from info where username=%d',$post['username']);
				if(empty($resul)){
					$Info=new InfoController();
					$Info->index($post);
				}elseif($post['password']!=$resul[0]['password']){
					/**
					 * 修改密码后进行用户信息密码修改
					 */
					$da['password']=$post['password'];
					$Info->where('username=%d',$post['username'])->save($da);
				}
				$this->redirect("Index/index");				
			}else{
				session_unset();
				session_destroy();
				$this->error('用户名或密码错误！');
			}					
		}else{
			$this->error('非法操作');
		}	
	}
	public function logout(){
		$flag=$_SESSION['username']/10000000%10;
		if($flag<5){
			// 本部
			$Url="http://kdjw.hnust.cn/kdjw/Logon.do?method=logout";
		}else{
			$Url="http://xxjw.hnust.cn/xxjw/Logon.do?method=logout";
		}
		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL,$Url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$IP=array("202.106.16.36:3128");
		$k=array_rand($IP,1);// 获取随机 IP 的下标值
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址	
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式	
		//   无需带  cookie   curl_setopt($ch,CURLOPT_COOKIE,session('cookie'));		
		curl_exec($ch);
		curl_close($ch);
	
		session_unset();
		session_destroy();
		$this->redirect("Login/index");
	}
}
// 注意：因为 loginController 不能检测 session 值所以可以直接访问里面的方法，所以 loginController 里面只能定义 login 和 logout 两个方法
?>