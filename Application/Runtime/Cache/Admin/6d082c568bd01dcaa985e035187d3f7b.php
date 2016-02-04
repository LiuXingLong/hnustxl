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
	.pagination li:first-child a {
	    border-radius: 0 0 0 0;
	}
	.pagination span {
  		background-color: #f5f5f5;
  		float: left;
	  	padding: 0 14px;
	  	line-height: 34px;
	  	text-decoration: none;
	  	border: 0px solid #ddd;  	
	  	border-right-width: 0px;
  	    border-left-width: 1px;
  	    border-top-width: 0px;
  	    border-bottom-width:0px;
	}
	.pagination a{
		border-right-width: 0px;
  	    border-left-width: 1px;
  	    border-top-width: 0px;
  	    border-bottom-width:0px;
	}			
  	.pagination ul{
  	    border: 1px solid #ddd;
  		border-radius: 3px 3px 3px 3px;
  		border-left-width: 0px;
  	}
  	#danger{
		 color:#E41A1A;
		}
  </style>
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
              <li><a href="<?php echo U('Show/table');?>">用户课表查询</a></li>
              <li class="active"><a href="#" value="<?php echo ($_SESSION['flag6']); ?>">用户成绩查询</a></li>
              <li><a href="/hnustxl/Weixin/English/index.php" target="_blank">四六成绩查询</a></li>
              <li><a href="<?php echo U('Show/info');?>" value="<?php echo ($_SESSION['flag7']); ?>">用户资料查询</a></li> 
            </ul>
          </div>
        </div>
        <div class="span9">
		  <div class="row-fluid">		 
			<div class="page-header">
			<h2>用户成绩</h2>
			</div>
				<form action="<?php echo U('Show/grade');?>" method="post" class="search form-inline" style="margin-bottom: 18px;">
		           <input type="text"   name="username" id="username" style="width:160px;border-radius: 3px;" placeholder="<?php echo ($_SESSION['name1']); ?>  学号  <?php echo ($_SESSION['username1']); ?>">
		           <input type="submit" name="button"   id="button"   style="margin-left:28px;width:74px;height:28px;margin-bottom:9px;margin-top: 8px;" value="确认"> 
		       </form> 		
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>学号</th>
		     			<th><?php echo ($_SESSION['username1']); ?></th>
		     			<th></th>
		     			<th>姓名</th>
		     			<th><?php echo ($_SESSION['name1']); ?></th>
					</tr>
					<tr>
						<th>ID</th>
		         		<th>开课时间</th>
		         		<th>科目</th>
		        	 	<th>成绩</th>
		        	 	<th>学分</th>
					</tr>
				</thead>
				<tbody>
			      <?php if(is_array($grade)): foreach($grade as $key=>$v): if($v["flag"] == 0): ?><tr class="list-grades" id="danger">
						      <td><?php echo ($v["id"]); ?></td>
						      <td><?php echo ($v["time"]); ?></td>
						      <td><?php echo ($v["subject"]); ?></td>
						      <td><?php echo ($v["score"]); ?></td>
						      <td><?php echo ($v["xuefen"]); ?></td>
						   </tr>
					<?php else: ?>
						<tr class="list-grades">
					      <td><?php echo ($v["id"]); ?></td>
					      <td><?php echo ($v["time"]); ?></td>
					      <td><?php echo ($v["subject"]); ?></td>
					      <td><?php echo ($v["score"]); ?></td>
					      <td><?php echo ($v["xuefen"]); ?></td>
						</tr><?php endif; endforeach; endif; ?>
			     </tbody>
			</table>
			<div class="pagination" align='right'>
			<ul><li><?php echo ($page); ?></li></ul>
		   </div>
		  </div>
        </div>
      </div>
     </div>
  </body>
</html>