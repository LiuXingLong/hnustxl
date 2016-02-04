<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>hnustXl</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
   <link rel="stylesheet" type="text/css" href="/hnustxl/Application/Home/View/Public/lib/bootstrap/css/bootstrap.css">
   <!--  
   <link rel="stylesheet" type="text/css" href="/hnustxl/Application/Home/View/Public/lib/bootstrap/css/bootstrap.min.css">
 
  	<link rel="stylesheet" href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css">
     -->
  	
    <link rel="stylesheet" type="text/css" href="/hnustxl/Application/Home/View/Public/stylesheets/theme.css">   
    <link rel="stylesheet" href="/hnustxl/Application/Home/View/Public/lib/font-awesome/css/font-awesome.css">

    <script src="/hnustxl/Application/Home/View/Public/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="/hnustxl/Application/Home/View/Public/lib/bootstrap/js/bootstrap.js"></script>
	<script src="/hnustxl/Application/Home/View/Public/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- Demo page code--> 
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .brand { font-family: georgia, serif; }
        .brand .first {
            color: #ccc;
            font-style: italic;
        }
        .brand .second {
            color: #fff;
            font-weight: bold;
        }
		.pagination a{	 
		  float: left;
		  padding: 0 14px;
		  line-height: 38px;
		  text-decoration: none;
		  background-color: #ffffff;
		  border: 1px solid #dddddd;
		  border-left-width: 0;
		}
		.pagination a:hover{
 		 background-color: #f5f5f5;
 		}
 		.pagination span{	 
		  float: left;
		  padding: 0 14px;
		  line-height: 38px;
		  text-decoration: none;
		  background-color: #ffffff;
		  border: 1px solid #dddddd;		 
		}
		.pagination span:hover{
 		 background-color: #f5f5f5;
 		}
 		.pagination .prev{
		  border-left-width: 1px;
		  -webkit-border-radius: 3px 0 0 3px;
		  -moz-border-radius: 3px 0 0 3px;
		  border-radius: 3px 0 0 3px;
		}
		.pagination .next{
		  border-right-width: 1px;
		  -webkit-border-radius: 0 3px 3px 0;
		  -moz-border-radius: 0 3px 3px 0;
		  border-radius: 0 3px 3px 0;
		}
		.table>tbody>.danger{
		 background-color: #f2dede;
		}
		.table>thead>.danger{
		  background-color: #BAF9AE;
		  color: #200EF5;		  
		}
		.content {
		 /* min-width: 400px;*/
		  width:1120px;
		  min-height: 510px;
		  margin-right: 6px;		 
		}
		.well{
			/*width:1079px;*/
			 width:1075px;
			border-right-width:0px;		
		}		
		tr{
			height: 60px;
			color: hsla(150, 6%, 30%, 0.85);
		}	
		.table th{
			padding-bottom: 18px;
		}
    </style>
  </head>
  <body class=""> 
  <!--<![endif]-->
    
    <div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                    <li id="fat-menu" class="dropdown">
                        <a href="<?php echo U('Login/logout');?>" >
                          <i class="icon-user"></i>
							Logout                    
                        </a>                    
                    </li>         
                </ul>
                <a class="brand" href="<?php echo U('Index/index');?>"><span class="second"><?php echo ($_SESSION['name']); ?></span><span class="first"> welcome to </span><span class="second">hnustXl ！</span></a>
        </div>
    </div>
    <div class="sidebar-nav">
       <!-- <form class="search form-inline">
            <input type="text" placeholder="Search..."> 
        </form> -->
		 <a href="<?php echo U('Show/table');?>" class="nav-header"><i class="icon-table"></i>课表查询</a>		
        <a href="<?php echo U('Show/school');?>" class="nav-header"><i class="icon-calendar"></i>校历查询</a>
        <a href="#" class="nav-header"><i class="icon-comment"></i>个人信息</a>
        <a href="#score" class="nav-header collapsed" data-toggle="collapse"><i class="icon-search"></i>成绩查询 </a>
        <ul id="score" class="nav nav-list collapse">
            <li ><a href="<?php echo U('Show/index');?>">个人成绩查询</a></li>
            <li ><a href="<?php echo U('Show/jwjz');?>">他人成绩查询</a></li>
            <li ><a href="#">四六级成绩查询</a></li>
        </ul>       		
		<a href="#error-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-plus-sign"></i>权限提升 </a>
        <ul id="error-menu" class="nav nav-list collapse">
            <li ><a href="#">绑定QQ邮箱</a></li>
        </ul>       
    </div>
	
	<!-- 表格  -->
    <div class="content"> 
    <div class="well">
    <table class="table">	
      <thead> 
      	<tr>     		
      		<th style="color: hsl(168, 86%, 41%);">学号</th>
      		<th><?php echo ($inf[0]["username"]); ?></th>
      		<th style="color: hsl(168, 86%, 41%);">姓名</th>
      		<th><?php echo ($inf[0]["name"]); ?></th>
      	</tr>    	 
        <tr>
          <th style="color: hsl(168, 86%, 41%);">院系</th>
          <th><?php echo ($inf[0]["institute"]); ?></th>
          <th style="color: hsl(168, 86%, 41%);">专业</th>
          <th><?php echo ($inf[0]["specialty"]); ?></th>              
        </tr>
        <tr>
          <th style="color: hsl(168, 86%, 41%);">班级</th>
          <th><?php echo ($inf[0]["class"]); ?></th>
          <th style="color: hsl(168, 86%, 41%);">性别</th>
          <th><?php echo ($inf[0]["sex"]); ?></th>              
        </tr>  
        <tr>
          <th style="color: hsl(168, 86%, 41%);">名族</th>
          <th><?php echo ($inf[0]["ethnic"]); ?></th>
          <th style="color: hsl(168, 86%, 41%);">出生日期</th>
          <th><?php echo ($inf[0]["time"]); ?></th>               
        </tr>  
        <tr>
          <th style="color: hsl(168, 86%, 41%);">身份证号</th>
          <th><?php echo ($inf[0]["id"]); ?></th>
          <th style="color: hsl(168, 86%, 41%);">籍贯</th>
          <th><?php echo ($inf[0]["birthplace"]); ?></th>           
        </tr>  
        <tr>
          <th style="color: hsl(168, 86%, 41%);">政治面貌</th>
          <th><?php echo ($inf[0]["status"]); ?></th>
          <th style="color: hsl(168, 86%, 41%);">电话</th>
          <th><?php echo ($inf[0]["phone"]); ?></th>             
        </tr>        
      </thead>      	  
    </table>  
	</div>
	</div>
	<script src="/hnustxl/Application/Home/View/Public/lib/bootstrap/js/bootstrap.js"></script>
  </body>
</html>