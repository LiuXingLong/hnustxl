<?php
namespace Home\Controller;
use Think\Controller;
class FromController extends BaseController{
    public function index(){
		if(IS_POST){
			$post=I('post.');
			if(empty($post['username'])){
				$this->error('用户名不能为空！');
				exit();
			}
			if(strlen($post['username'])!=10){
				$this->error('用户名有误！');
				exit();
			}				
			$username=$post['username'];			
			if($_SESSION["'{$username}'"]==1){				
				$ID=M('idcard');
				$result=$ID->field('name')->where("username=%d",$post['username'])->select();			
				$_SESSION['username1']=$post['username'];
				$_SESSION['name1']=$result[0]['name'];
				$this->redirect("Show/jwjz");
			}else{				
				$con=M('user');
				$count=$con->field('cnt,flag')->where("username=%d",$_SESSION['username'])->select();
				if($count[0]['flag']==0){					
					$flag=(int)($post['username']/100);
					$flag1=(int)($_SESSION['username']/100);
					if($flag!=$flag1){
						$this->error('抱歉，由于你的权限问题你无法查询其他班同学的成绩！可以通过绑定QQ邮箱来提升权限！');
						exit();
					}		
				}		
				if($count[0]['cnt']<=0){
					$this->error('抱歉，由于你的权限问题你一天内能够查询其他不同人的次数已满！可以通过绑定QQ邮箱来提升权限！');
				}else{									
					$from=new ScoreJZController();
                    $flag=$from->index($post);
                    if($flag==-1){                                                                    
                        $this->redirect("Show/jwjz");
                		exit();
                    }else if($flag==1){
						$data['cnt']=$count[0]['cnt']-1;
						$con->where("username=%d",$_SESSION['username'])->save($data);
						$this->redirect("Show/jwjz");
					}else{
						$this->error('抱歉，系统无该用户信息！');
					}
				}				
			}				
		}else{
			$this->error("非法请求！");
		}
	}
}