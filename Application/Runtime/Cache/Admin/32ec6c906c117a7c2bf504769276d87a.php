<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>hnustxl</title>
<link href="/hnustxl/Application/Admin/View/Public/Wopop_files/style_log.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/hnustxl/Application/Admin/View/Public/Wopop_files/style.css">
<link rel="stylesheet" type="text/css" href="/hnustxl/Application/Admin/View/Public/Wopop_files/userpanel.css">
<link rel="stylesheet" type="text/css" href="/hnustxl/Application/Admin/View/Public/Wopop_files/jquery.ui.all.css">
<script src="/hnustxl/Application/Admin/View/Public/Wopop_files/google_jquery.min.js"></script>
<!-- script src="/hnustxl/Application/Admin/View/Public/Wopop_files/login.js" type="text/javascript"></script -->
<script>
// jQuery Ajax 已步登入
function Ajax(){
	var username=$("#username").val();
	var password=$("#password").val();
	if(username==""||username=="用户名"){
		alert("用户名不能为空！");
	}else if(password==""||password=="******"){
		alert("密码不能为空！");
	}else{
		$.ajax({
	        type: "POST",
	        url: '<?php echo U('Login/Login');?>',
	        data: { username:username ,password:password },
	        success: function (data) {
	        	if (data == "Error"){                
	            	alert("用户名或密码错误！");
	            }else {
	                window.location.href ="<?php echo U('Index/index');?>";
	            }
	        }
	    });	
	}
}
$(function(){
	var flag=1;
	document.onkeydown = function(e){ 
	    var ev = document.all ? window.event : e;
	    if(ev.keyCode==13&&flag==1) {
	    	Ajax();// 捕获 enter 键      注意：获鼠标点击事件后会 自动 捕获enter键
	     }
	}
	$("#button").click(function(){
		flag=0;
		Ajax();// 捕获鼠标点击事件 
	  }	
	);
})	
</script>
</head>
<body class="login" mycollectionplug="bind">
<div class="login_m">
<div class="login_logo"><h1 style="width:400px; height:40px;font-size: 36px; text-align: center;">hnuxtxl</h1></div>   
<!-- <img src="/hnustxl/Application/Admin/View/Public/Wopop_files/logo.png" width="196" height="46"> -->
<div class="login_boder"> 
<div class="login_padding" id="login_model">
  <h2>USERNAME</h2>
	  <label>
	    <input type="text" name="username" id="username" class="txt_input txt_input2" onfocus="if (value ==&#39;用户名&#39;){value =&#39;&#39;}" onblur="if (value ==&#39;&#39;){value=&#39;用户名&#39;}" value="用户名">
	  </label>
  <h2>PASSWORD</h2>
	  <label>
	    <input type="password" name="password" id="password" class="txt_input" onfocus="if (value ==&#39;******&#39;){value =&#39;&#39;}" onblur="if (value ==&#39;&#39;){value=&#39;******&#39;}" value="******">
	  </label>
  <br/><br/>  
  <div class="rem_sub">
	 <div class="rem_sub_l">
		 <input type="checkbox" name="checkbox" id="save_me">
		 <label for="checkbox">Remember me</label>
	 </div>
	 <label>
	     <input type="submit" class="sub_button" name="button" id="button" value="SIGN-IN" style="opacity: 0.7;">
	     <!-- submit 提交表单   支持enter键        button 只是一个按键 通过触发事假 完成提交  -->
	 </label>
  </div>
</div>
<!--login_padding end-->
</div>
<!--login_boder end-->
</div>
<!--login_m end-->
<br/><br/>
</body>
</html>