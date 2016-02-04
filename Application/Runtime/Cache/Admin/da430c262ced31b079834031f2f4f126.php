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
          <div class="btn-group pull-right" style="display:<?php echo ($_SESSION['dispaly']); ?>;">
			<a class="btn" href="<?php echo U('Login/logout');?>"><i class="icon-user"></i> Logout</a>         
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid" style="font-size: 14px;"><!-- style="font-size: 14px;" -->
      <div class="row-fluid">
        <div class="span3" style="display:<?php echo ($_SESSION['dispaly']); ?>;">
          <div class="well sidebar-nav" >
            <ul class="nav nav-list">
              <li class="nav-header"><i class="icon-wrench"></i>用户管理设置</li>
              <li><a href="<?php echo U('Show/index');?>" value="<?php echo ($_SESSION['flag1']); ?>">查看后台用户人员</a></li>
              <li><a href="<?php echo U('Show/add');?>" value="<?php echo ($_SESSION['flag2']); ?>">添加后台用户人员</a></li>
              <li><a href="<?php echo U('Show/delete1');?>" value="<?php echo ($_SESSION['flag3']); ?>">删除后台用户人员</a></li>
              <li class="active"><a href="#" value="<?php echo ($_SESSION['flag4']); ?>">后台人员权限设置</a></li>
              <li><a href="<?php echo U('Show/reception');?>" value="<?php echo ($_SESSION['flag5']); ?>">前台人员权限设置</a></li>              
              <li class="nav-header"><i class="icon-user"></i>用户信息查询</li>
              <li><a href="<?php echo U('Show/table');?>">用户课表查询</a></li>
              <li><a href="<?php echo U('Show/grade');?>" value="<?php echo ($_SESSION['flag6']); ?>">用户成绩查询</a></li>
              <li><a href="/hnustxl/Weixin/English/index.php" target="_blank">四六成绩查询</a></li>
              <li><a href="<?php echo U('Show/info');?>" value="<?php echo ($_SESSION['flag7']); ?>">用户资料查询</a></li> 
            </ul>
          </div>
        </div>
       	 <div class="span9">
          <div class="well hero-unit" style="width: 56%;">
            <div>
				<h2>后台用户权限设置</h2><br/>
				<input name="flag1" id="flag1" type="checkbox" value="" />查看后台用户人员&nbsp&nbsp&nbsp&nbsp&nbsp
				<input name="flag2" id="flag2" type="checkbox" value="" />添加后台用户人员&nbsp&nbsp&nbsp&nbsp&nbsp
				<input name="flag3" id="flag3" type="checkbox" value="" />删除后台用户人员<br/><br/>
				<input name="flag4" id="flag4" type="checkbox" value="" />后台人员权限设置&nbsp&nbsp&nbsp&nbsp&nbsp
				<input name="flag5" id="flag5" type="checkbox" value="" />前台人员权限设置&nbsp&nbsp&nbsp&nbsp&nbsp
				<input name="flag6" id="flag6" type="checkbox" value="" />用户成绩查询 <br/><br/>
				<input name="flag7" id="flag7" type="checkbox" value="" />用户资料查询 <br/><br/>
				<input type="text" style="width:160px;border-radius: 3px;" name="username" id="username" placeholder="学号">	
				<button type="button" onclick="backstage()" style="margin-left: 50px;width: 61px;margin-bottom: 9px;height: 26px;">提交</button>
			</div>				
         </div>
       	</div>
       </div>
      </div>
   <script>
   $(function(){
	   $("a").click(function(){				 
			 var value=$(this).attr("value");    
			 if(value==0){
				 $(this).attr("href","#");
				 alert("抱歉，该功能尚在开发中、、、");
			 }	    	  		
		});
   });
  	function Ajax(){
  		var flag1=document.getElementById("flag1").checked;
  		var flag2=document.getElementById("flag2").checked;
  		var flag3=document.getElementById("flag3").checked;
  		var flag4=document.getElementById("flag4").checked;
  		var flag5=document.getElementById("flag5").checked;
  		var flag6=document.getElementById("flag6").checked;
  		var flag7=document.getElementById("flag7").checked;
  		var username=$("#username").val(); 		
  		//alert(username);
  		//alert(flag1+"     "+flag2+"     "+flag3+"     "+flag4+"     "+flag5+"     "+flag6+"     "+flag7);
  		if(username==""){
  			alert("学号不能为空！");
  		}else{
  			$.ajax({
  	  			type:"POST",
  	  			url:'<?php echo U('Show/backstage');?>',
  	  			data:{flag1:flag1,flag2:flag2,flag3:flag3,flag4:flag4,flag5:flag5,flag6:flag6,flag7:flag7,username:username},
  	  			success:function(data){
  	  				if(data=="1"){
  	  					alert("用户后台权限修改成功！");
  	  				}else{  	  		
  	  					alert("用户不存在！");
  	  				}
  	  			}  			
  	  		});
  		}
  	}
  	function backstage(){
			Ajax();
	}
  </script>
  </body>
</html>