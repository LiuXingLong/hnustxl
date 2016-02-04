<?php
namespace Admin\Controller;
use Think\Controller;
class ShowController extends BaseController{
	public function index(){
		if($_SESSION['flag1']==0){
			$this->error("抱歉，该功能尚在开发中、、、");
			//$this->redirect("Index/index");
			die;				
		}		
		$User=M('useradmin');
		$count=$User->count();
		$page=new \Think\Page($count,10);			
		$limit=$page->firstRow.','.$page->listRows;
		$Name=$User->limit($limit)->select();
		$this->name=$Name;
		$this->ID=0;
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$this->page=$page->show();// 分页显示输出
		$this->display();
	}
	public function add(){
		if($_SESSION['flag2']==0){
			$this->error("抱歉，该功能尚在开发中、、、");
			die;
		}
		if(IS_POST){		
			$data['name']=$_POST['name'];
			$data['username']=$_POST['username'];
			$data['password']=md5($_POST['password']); // md5 加密
			$User=M('useradmin');
			$result=$User->query('select *from useradmin where username=%d',$data['username']);
			if(empty($result)){
				echo 1; // 返回给  Ajax
				$data['time']=date('Y-m-d H:i',time());           //获取时间
				$data['ip']=$_SERVER['REMOTE_ADDR']; // 获取服务器的 ip地址 ：get_client_ip();				
				$User->field('username,password,name,time,ip')->create($data);//添加导数据库中
				$User->add();				
			}else{
				$User->where('username=%d',$data['username'])->save($data);
			}
			die;
		}
		$this->display(add);
	}
	public function backstage(){
		if($_SESSION['flag4']==0){
			$this->error("抱歉，该功能尚在开发中、、、");
			die;
		}
		if(IS_POST){			
			$data['username']=$_POST['username'];
			$data['flag1']=$data['flag2']=$data['flag3']=0;
			$data['flag4']=$data['flag5']=$data['flag6']=$data['flag7']=0;
			if($_POST['flag1']=="true"){$data['flag1']=1;}
			if($_POST['flag2']=="true"){$data['flag2']=1;}
			if($_POST['flag3']=="true"){$data['flag3']=1;}
			if($_POST['flag4']=="true"){$data['flag4']=1;}
			if($_POST['flag5']=="true"){$data['flag5']=1;}
			if($_POST['flag6']=="true"){$data['flag6']=1;}
			if($_POST['flag7']=="true"){$data['flag7']=1;}
			$User=M('useradmin');
			$result=$User->query('select *from useradmin where username=%d',$data['username']);
			if(!empty($result)){
				echo 1;
				$User->where('username=%d',$data['username'])->save($data);
			}
			die;
		}		
		$this->display(backstage);
	}
	public function delete1(){
		if($_SESSION['flag3']==0){
			$this->error("抱歉，该功能尚在开发中、、、");
			die;
		}
		if(IS_POST){
			$User=M('useradmin');
			$result=$User->query('select *from useradmin where username=%d',$_POST['username']);
			if(!empty($result)){
				echo 1;
				$User->where("username='%s'",$_POST['username'])->delete();
			}
			die;
		}
		$this->display(delete1);
	}
	public function grade(){
		if($_SESSION['flag6']==0){
			$this->error("抱歉，该功能尚在开发中、、、");
			die;
		}
		if(IS_POST){			
			$username=$_POST['username'];	
			if($_SESSION["'{$username}'"]!=1){		
				$grad=new GradeController();
				$flag=$grad->index($_POST);
				if($flag==0){
					// 没有找到该用户的信息无法查询该用户的成绩
				}else if($flag==-1){
					// 教务网异常
				}
			}else{
				$ID=M('idcard');
				$result=$ID->field('name')->where("username=%d",$_POST['username'])->select();
				$_SESSION['username1']=$_POST['username'];
				$_SESSION['name1']=$result[0]['name'];
			}						
		}
		$map['username']=$_SESSION['username1'];
		$Grade=M('gradejz');
		$count=$Grade->where($map)->count();// 查询满足条件的总记录数
		$page=new \Think\Page($count,10);//  实例化分页类 传入总记录数和每页显示的记录数
		$limit=$page->firstRow.','.$page->listRows;
		$Score=$Grade->where($map)->limit($limit)->select();
		$this->grade=$Score;
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$this->page=$page->show();// 分页显示输出
		$this->display(grade);
	}
	public function info(){		
		if($_SESSION['flag7']==0){					
			$this->error("抱歉，该功能尚在开发中、、、");
			die;
		}
		if(IS_POST){
			$user=M('idcard');
			$gg=0;
			$result=$user->query("select *from idcard where username=%d",$_POST['username']);			
			if(empty($result)){
				$gg=1;
				$result=$user->query("select *from idcard where name='%s'",$_POST['username']);
			}	
			$cont=count($result);
			for($i=0;$i<$cont;$i++){
				$post['username']=$result[$i]['username'];
				$post['name']=$result[$i]['name'];
				$post['id']=$result[$i]['card'];
				$username=(int)$post['username']*100;
				if($_SESSION["'{$username}'"]!=1){
					$Info=M('info');
					$resul=$Info->query('select *from info where username=%d',$post['username']);
					if(empty($resul)){
						/*
						$flag=new InfoController();
						$fg=$flag->index($post);
						if($fg==1){
							$_SESSION["'{$username}'"]=1;
						}elseif($fg==0){
							// 没有找到该用户的信息
						}else{
							// 教务网异常
						}
						*/						
					}else{
						$_SESSION["'{$username}'"]=1;
					}
				}				
			}
			$_SESSION['name2']=$post['name'];
			$_SESSION['username2']=$post['username'];
			$_SESSION['gg']=$gg;		
		}			
		if($_SESSION['gg']==1){
			$map['name']=$_SESSION['name2'];
		}else{
			$map['username']=$_SESSION['username2'];
		}	
		$Info=M('info');
		$Xin=$Info->where($map)->select();
		$this->assign('inf',$Xin);
		$this->display(info);
	}
	public function reception(){
		if($_SESSION['flag5']==0){
			$this->error("抱歉，该功能尚在开发中、、、");
			die;
		}
		if(IS_POST){
			if($_POST['flag']=="true"){
				$data['flag']=1;
			}else{
				$data['flag']=0;
			}			
			$data['count']=$_POST['count'];
			$data['username']=$_POST['username'];
			$User=M('user');
			$result=$User->query('select *from user where username=%d',$data['username']);
			if(!empty($result)){
				echo 1;
				$User->where('username=%d',$data['username'])->save($data);
			}
			die;
		}
		$this->display(reception);
	}
	public function table(){
		if(!empty($_SESSION['table'])){
			$data=$_SESSION['table'];
		}
		if(IS_POST){	
			$table=new TableController();
			$data=$table->index($_POST);
			$data[7]['username']=$_POST['username'];
			if($data==-1){
					// 教务网异常
			}else{
				for($i=1;$i<8;$i++){
					for($j=0;$j<6;$j++){
						if($data[$i][$j]==null){
							$data[$i][$j][0]="<br/>";
						}else{
							$count=count($data[$i][$j]);
							for($k=0;$k<$count;$k++){
								if($k!=$count-1&&($k+1)%3==0){
									$data[$i][$j][$k]=$data[$i][$j][$k]."<br/><br/>";
								}else{
									$data[$i][$j][$k]=$data[$i][$j][$k]."<br/>";
								}
							}
						}
				
					}
				}
				$_SESSION['table']=$data;	
			}	
		}
		//dump($data); 
		$this->data=$data;
		$this->display(table);
	}
}