<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController{
    public function index(){
		//var_dump(defined('__ROOT__'));  检测常量
		
    	/* 用于登入刷新处理数据  */
    	$display=$_SESSION['dispaly'];
    	$status=$_SESSION['status'];
    	$flag1=$_SESSION['flag1'];
    	$flag2=$_SESSION['flag2'];
    	$flag3=$_SESSION['flag3'];
    	$flag4=$_SESSION['flag4'];
    	$flag5=$_SESSION['flag5'];
    	$flag6=$_SESSION['flag6'];
    	$flag7=$_SESSION['flag7'];
    	$table1=$_SESSION['cookieT'][1];
    	$table2=$_SESSION['cookieT'][2];
    	
		session_unset();// 清除  session 中的数据	 但不摧毁 session
		
    	$_SESSION['dispaly']=$display;
    	$_SESSION['status']=$status;
    	$_SESSION['flag1']=$flag1;
    	$_SESSION['flag2']=$flag2;
    	$_SESSION['flag3']=$flag3;
    	$_SESSION['flag4']=$flag4;
    	$_SESSION['flag5']=$flag5;
    	$_SESSION['flag6']=$flag6;
    	$_SESSION['flag7']=$flag7;
    	$_SESSION['cookieT'][1]=$table1;
    	$_SESSION['cookieT'][2]=$table2;
    	
		$this->display();// 加载模板
	}
}