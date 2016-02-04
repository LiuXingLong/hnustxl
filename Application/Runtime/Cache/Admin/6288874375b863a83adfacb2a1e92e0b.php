<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>hnustxl</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin panel developed with the Bootstrap from Twitter.">
    <meta name="author" content="travis">
    <link href="/hnustxl/Application/Admin/View/Public/css/bootstrap.css" rel="stylesheet">
	<link href="/hnustxl/Application/Admin/View/Public/css/site.css" rel="stylesheet">
    <link href="/hnustxl/Application/Admin/View/Public/css/bootstrap-responsive.css" rel="stylesheet">
    <script src="/hnustxl/Application/Admin/View/Public/Wopop_files/google_jquery.min.js"></script>
  </head>
  <style type="text/css">
 	 li {
  		line-height: 25px;
		}
  </style>
  <script>
	function Ajax(){
		var username=$("#username").val();
		$.ajax({
	        type: "POST",
	        url: '<?php echo U('Show/add');?>',
	        data: {username:username},
	        success: function (data) {	        	       
	        	if(data=="Username"){
	        		alert("学号不能为空！");
	        	}else if(data==1){
	                alert("删除成功！");
	            }else{
	            	alert("用户不存在！");
	            }        	
	        }
	    });	
	}
	$(function(){
		$("#button").click(function(){
			Ajax();// 捕获鼠标点击事件 
		  }	
		);
	})	
  </script>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo U('Index/index');?>">hnustxl Administration</a>
          <div class="btn-group pull-right">
			<a class="btn" href="<?php echo U('Login/logout');?>"><i class="icon-user"></i> Logout</a>         
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid" style="font-size: 14px;">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav" >
            <ul class="nav nav-list">                          
              <li class="nav-header"><i class="icon-wrench"></i>用户管理设置</li>
              <li><a href="<?php echo U('Show/index');?>">查看后台用户人员</a></li>
              <li><a href="<?php echo U('Show/add');?>">添加后台用户人员</a></li>
              <li class="active"><a href="#">删除后台用户人员</a></li>
              <li><a href="<?php echo U('Show/backstage');?>">后台人员权限设置</a></li>
              <li><a href="<?php echo U('Show/reception');?>">前台人员权限设置</a></li>              
              <li class="nav-header"><i class="icon-user"></i>用户信息查询</li>
              <li><a href="<?php echo U('Show/grade');?>">用户成绩查询</a></li>
              <li><a href="<?php echo U('Show/info');?>">用户资料查询</a></li>               
            </ul>
          </div>
        </div>
      <div class="span9">
		  <div class="row-fluid">
			<div class="page-header">
				<h2>删除后台用户</h2>
			</div>
			<form class="form-horizontal">
				<fieldset>					
					<div class="control-group">
						<label class="control-label" for="user">学号</label>
						<div class="controls">
							<input type="text" class="input-xlarge" name="username" id="username" />
						</div>
					</div>
					<div class="form-actions">
						<input type="submit" name="button" id="button" class="btn btn-success btn-large" value="删除" />
					</div>					
				</fieldset>
			</form>
		  </div>
        </div>
      </div>
     </div>   
  </body>
</html>