<?php
namespace Home\Controller;
use Think\Controller;
class ShowController extends BaseController{
	// 传入学号 查询成绩        未传入默认查询自己的
    public function index(){
    	$_SESSION['username1']= $_SESSION['username'];
    	$_SESSION['name1']= $_SESSION['name'];
    	$map['username']=session('username');
    	$Grade=M('grade');    	
    	$count=$Grade->where($map)->count();// 查询满足条件的总记录数
    	$page=new \Think\Page($count,9);//  实例化分页类 传入总记录数和每页显示的记录数
    	$limit=$page->firstRow.','.$page->listRows;   
    	$Score=$Grade->where($map)->limit($limit)->select();  
    	$this->grade=$Score;
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');    	
    	$this->page=$page->show();// 分页显示输出  
		//var_dump(defined('__ROOT__'));  检测常量
		$this->display();// 加载模板
	}
	public function jwjz(){						
		$map['username']=session('username1');
		$Grade=M('gradejz');
		$count=$Grade->where($map)->count();// 查询满足条件的总记录数
		$page=new \Think\Page($count,8);//  实例化分页类 传入总记录数和每页显示的记录数
		$limit=$page->firstRow.','.$page->listRows;
		$Score=$Grade->where($map)->limit($limit)->select();
		$this->grade=$Score;
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$this->page=$page->show();// 分页显示输出
		//var_dump(defined('__ROOT__'));  检测常量		
		$this->display(jwjz);
	}
	public function info(){
		$_SESSION['username1']= $_SESSION['username'];
    	$_SESSION['name1']= $_SESSION['name'];    	    	
    	$map['username']=session('username');
    	$Info=M('info');    	   
    	$Xin=$Info->where($map)->select();
    	$this->assign('inf',$Xin);
		$this->display(info);// 加载模板
	}
	public function table(){
		$table=M('table');
		$da=$table->where('username=%d',$_SESSION['username'])->select();	
    	$time=array('第一二节','第三四节','第五六节','第七八节','第九十节');
    	$data[1][0]=$time[0];
    	$data[2][0]=$time[1];
    	$data[3][0]=$time[2];
    	$data[4][0]=$time[3];
    	$data[5][0]=$time[4];		   
		for($i=1,$j=0;$i<6;$i++){
			$data[$i][1]=$da[0]["t".++$j];
			$data[$i][2]=$da[0]["t".++$j];
			$data[$i][3]=$da[0]["t".++$j];
			$data[$i][4]=$da[0]["t".++$j];
			$data[$i][5]=$da[0]["t".++$j];
			$data[$i][6]=$da[0]["t".++$j];
			$data[$i][7]=$da[0]["t".++$j];
		}
		$data['note']=$da[0]['note'];
		$this->data=$data;
		$this->display(table);
	}
	public function school(){
		$this->display(school);
	}
}