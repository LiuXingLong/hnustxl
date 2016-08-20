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
	.table th,.table td{
		text-align:center;
		vertical-align:middle;
	}
  </style>
  <script>
  var year = new Date().getFullYear();
  $(function(){
	  $("a").click(function(){				 
		 var value=$(this).attr("value");    
		 if(value==0){
			 $(this).attr("href","#");
			 alert("抱歉，该功能尚在开发中、、、");
		 }	    	  		
	  }); 
	  $("#button").click(function(){
		  var time = $('select option:selected').text();
		  setCookie("time",time[time.length-1]);   
	  });
	  if(getCookie("time")=="2"){
		  $("select").html("<option >"+year+"-"+(year+1)+"-1</option><option selected='selected'>"+(year-1)+"-"+year+"-2</option>");
	  }else{
		  $("select").html("<option >"+year+"-"+(year+1)+"-1</option><option>"+(year-1)+"-"+year+"-2</option>"); 
	  } 
  });
  function setCookie(name,value){ 
      var Days = 30; 
      var exp = new Date(); 
      exp.setTime(exp.getTime() + Days*24*60*60*1000); 
      document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
  } 
  function getCookie(name){ 
      var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
      if(arr=document.cookie.match(reg))
          return unescape(arr[2]); 
      else 
          return null; 
  }
  function delCookie(name){ 
      var exp = new Date(); 
      exp.setTime(exp.getTime() - 1); 
      var cval=getCookie(name); 
      if(cval!=null) 
          document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
  }
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
          <div class="btn-group pull-right" style="display:<?php echo ($_SESSION['dispaly']); ?>;">
			<a class="btn" href="<?php echo U('Login/logout');?>"><i class="icon-user"></i> Logout</a>         
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid" style="font-size: 14px;">
      <div class="row-fluid">
        <div class="span3" style="display:<?php echo ($_SESSION['dispaly']); ?>;">
          <div class="well sidebar-nav" >
            <ul class="nav nav-list">
              <li class="nav-header"><i class="icon-wrench"></i>用户管理设置</li>
              <li><a href="<?php echo U('Show/index');?>" value="<?php echo ($_SESSION['flag1']); ?>">查看后台用户人员</a></li>
              <li><a href="<?php echo U('Show/add');?>" value="<?php echo ($_SESSION['flag2']); ?>">添加后台用户人员</a></li>
              <li><a href="<?php echo U('Show/delete1');?>" value="<?php echo ($_SESSION['flag3']); ?>">删除后台用户人员</a></li>
              <li><a href="<?php echo U('Show/backstage');?>" value="<?php echo ($_SESSION['flag4']); ?>">后台人员权限设置</a></li>
              <li><a href="<?php echo U('Show/reception');?>" value="<?php echo ($_SESSION['flag5']); ?>">前台人员权限设置</a></li>              
              <li class="nav-header"><i class="icon-user"></i>用户信息查询</li>
              <li class="active"><a href="#">用户课表查询</a></li>                       
              <li><a href="<?php echo U('Show/grade');?>" value="<?php echo ($_SESSION['flag6']); ?>">用户成绩查询</a></li>
              <li><a href="/hnustxl/Weixin/English/index.php" target="_blank">四六成绩查询</a></li>
              <li><a href="<?php echo U('Show/info');?>" value="<?php echo ($_SESSION['flag7']); ?>">用户资料查询</a></li> 
            </ul>
          </div>
        </div>
        <div class="span9">
		  <div class="row-fluid">		 
			<div class="page-header">
			<h2>用户课表</h2>
			</div>
				<form action="<?php echo U('Show/table');?>" method="post" class="search form-inline" style="margin-bottom: 18px;">
		           <input type="text"   name="username" id="username" style="width:160px;border-radius: 3px;" placeholder="学号	<?php echo ($data[7]['username']); ?>">		     
		           <select name="time" id="time" style="width:106px;"></select> 
		           <input type="submit" name="button"   id="button"   style="margin-left:28px;width:74px;height:28px;margin-bottom:9px;margin-top: 8px;" value="确认"> 
		       </form> 		
			<table class="table table-striped table-bordered table-condensed">
				<thead>				
					<tr>
						<th></th>
		         		<th>第一二节</th>
		         		<th>第三四节</th>
		        	 	<th>第五六节</th>
		        	 	<th>第七八节</th>
		        	 	<th>第九十节</th>
					</tr>
				</thead>
				<tbody>
			      <?php if(is_array($data)): foreach($data as $key=>$v): ?><tr class="list-table">
					    <td><?php echo ($v["0"]); ?></td>
					    <td>	
					    	<?php if(is_array($v[1])): foreach($v[1] as $key=>$s): echo ($s); endforeach; endif; ?>
					    </td>
					    <td>
					    	<?php if(is_array($v[2])): foreach($v[2] as $key=>$s): echo ($s); endforeach; endif; ?>
					    </td>
					    <td>
					    	<?php if(is_array($v[3])): foreach($v[3] as $key=>$s): echo ($s); endforeach; endif; ?>
					    </td>
					    <td>
					    	<?php if(is_array($v[4])): foreach($v[4] as $key=>$s): echo ($s); endforeach; endif; ?>
					    </td>
					    <td>
					    	<?php if(is_array($v[5])): foreach($v[5] as $key=>$s): echo ($s); endforeach; endif; ?>
					    </td>
					 </tr><?php endforeach; endif; ?>
			     </tbody>
			</table>			
			<div align='center'>
			<?php echo ($data[7]['note']); ?>
		    </div> 		   
		  </div>
        </div>
      </div>
     </div>
  </body>
</html>