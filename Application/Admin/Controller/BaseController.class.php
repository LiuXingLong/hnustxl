<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller{
	// 调用控制器初始方法
    public function _initialize(){
    	if(empty($_SESSION['dispaly'])){
    		if($_GET['wei']==1){
    			$_SESSION['dispaly']="none";
    		}else{
    			$_SESSION['dispaly']="block";
    		}
    	}
		if(empty($_SESSION['status'])||$_SESSION['status']==0){
			$this->redirect("Login/index");
			exit();
		}
	}
}