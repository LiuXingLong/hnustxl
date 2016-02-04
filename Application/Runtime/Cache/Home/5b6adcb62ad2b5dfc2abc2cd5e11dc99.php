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
                </ul>
                 <a class="brand" href="index.html"><span class="first">welcome to</span> <span class="second">hnustXl ！</span></a>
        </div>
    </div>
    
        <div class="row-fluid" style="margin-top:36px;">
    <div class="dialog" style="width: 360px;">
        <div class="block" style="border-radius: 3px;">
            <p class="block-heading">Sign In</p>
			
            <div class="block-body">
			
                <form action="<?php echo U('Login/Login');?>" method="post">
                    <label>Username</label>
                    <input type="text" class="span12" name="username" style="border-radius: 3px;" placeholder="学号">
                    <label>Password</label>
                    <input type="password" class="span12" name="password" style="border-radius: 3px;" placeholder="教务网密码">
					  <input type="submit" class="btn btn-primary pull-right" style="border-radius: 3px;" value="login">
                    <!--<a href="<?php echo U('Login/Login');?>" class="btn btn-primary pull-right">login</a>-->		
                    <label class="remember-me"><input type="checkbox"> Remember me</label>
                    <div class="clearfix"></div>
                </form>
				
            </div>
        </div>
        <!-- 
         <p class="pull-right" style=""><a href="#" target="blank">Theme by Portnine</a></p>
        <p><a href="reset-password.html">Forgot your password</a></p>
         -->  
    </div>
</div>
    <script src="/hnustxl/Application/Home/View/Public/lib/bootstrap/js/bootstrap.js"></script>   
  </body>
</html>