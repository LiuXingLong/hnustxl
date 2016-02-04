<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Error</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" type="text/css" href="/hnustxl/Application/Home/View/Public/lib/bootstrap/css/bootstrap.css">  
    <link rel="stylesheet" type="text/css" href="/hnustxl/Application/Home/View/Public/stylesheets/theme.css">
    <link rel="stylesheet" href="/hnustxl/Application/Home/View/Public/lib/font-awesome/css/font-awesome.css">
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
  </head>
  <body class="http-error">   
        <div class="row-fluid">
    <div class="http-error">
        <div class="http-error-message">
            <div class="error-caption">
                <p>Error !</p>
            </div>
            <div class="error-message">
                <p><?php echo($error); ?><p>
                <p class="return-home"">页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b></p>              
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>