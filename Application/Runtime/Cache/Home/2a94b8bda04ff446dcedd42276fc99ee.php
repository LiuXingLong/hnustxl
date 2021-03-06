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
    
    <link rel="stylesheet" type="text/css" href="/hnustxl/Application/Home/View/Public/stylesheets/theme.css">
    <link rel="stylesheet" href="/hnustxl/Application/Home/View/Public/lib/font-awesome/css/font-awesome.css">

    <script src="/hnustxl/Application/Home/View/Public/lib/jquery-1.7.2.min.js" type="text/javascript"></script>

    <!-- Demo page code -->

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
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
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
        <!--<form class="search form-inline">
            <input type="text" placeholder="Search..."> 
        </form> -->      
		<a href="<?php echo U('Show/table');?>" class="nav-header"><i class="icon-table"></i>课表查询</a>		
        <a href="<?php echo U('Show/school');?>" class="nav-header"><i class="icon-calendar"></i>校历查询</a>
        <a href="<?php echo U('Show/info');?>" class="nav-header"><i class="icon-comment"></i>个人信息</a>
        <a href="#score" class="nav-header collapsed" data-toggle="collapse"><i class="icon-search"></i>成绩查询</a>
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
    <script src="/hnustxl/Application/Home/View/Public/lib/bootstrap/js/bootstrap.js"></script>    
  </body>
</html>