<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController{
    public function index(){
    	//var_dump(defined('__ROOT__'));  检测常量   	
    	/**
    	 * 更新课表
    	 */
    	$table=new TableController();
    	$table->index();
    	$this->display();// 加载模板
	}
}