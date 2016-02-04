<?php
session_start();
if(empty($_SESSION['status'])||$_SESSION['status']==0){
    if($_GET['wei']==1){
        header("Location: ../../Admin/login/index?wei=1");
    }else{
        header("Location: ../../Admin");
    }    
}
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<title>hnustxl</title>
		<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<link href="./public/cet.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="./public/jquery.min.js"></script>
		<script type="text/javascript" src="./public/main.js"></script>
	<script id="b5mmain" type="text/javascript" charset="utf-8" src="./public/b5m.main.js"></script><link rel="stylesheet" href="./public/b5m-plugin.css" type="text/css"><link rel="stylesheet" href="./public/b5m.botOrTopBanner.css" type="text/css"></head>

	<body id="wrap" style="">
		<style>
			.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}
		</style>
		<div class="banner">
			<div id="wrapper">
				<div id="scroller" style="float:none">
					<ul id="thelist">
						<img src="./public/logo.jpg" alt="" style="width:100%">
					</ul>
				</div>
			</div>
			<div class="clr"></div>
		</div>
		<div class="cardexplain">
			<ul class="round">
				<li>
					<h2>全国大学英语四六级考试成绩查询</h2>
					<div class="text">
						查询方法<br>
						①搜索并关注公众号“gh_8cc89c6d804e”<br>
						②用户查询“四六级”</div>
				</li>
			</ul>
			<form method="post" action="result.php" id="form" onsubmit="return tgSubmit()">
				<ul class="round">
					<li class="title mb"><span class="none">查分入口</span></li>
					<li class="nob">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
							<tbody>
								<tr>
									<th>姓名</th>
									<td><input type="text" class="px" placeholder="请输入姓名" id="name" name="name" value="">
									</td>
								</tr>
							</tbody>
						</table>
					</li>
					<li class="nob">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
							<tbody>
								<tr>
									<th>准考试号</th>
									<td><input type="text" class="px" placeholder="请输入15位准考证号" id="number" name="number" value="">
									</td>
								</tr>
							</tbody>
						</table>
					</li>
				</ul>
				<div class="footReturn" style="text-align:center">
					<input type="hidden" name="openid" value="">
					<input type="submit" style="margin:0 auto 20px auto;width:90%" class="submit" value="提交查询">
				</div>
			</form>
			<script>
				function showTip(tipTxt) {
					var div = document.createElement('div');
					div.innerHTML = '<div class="deploy_ctype_tip"><p>' + tipTxt + '</p></div>';
					var tipNode = div.firstChild;
					$("#wrap").after(tipNode);
					setTimeout(function () {
						$(tipNode).remove();
					}, 1500);
				}
				function tgSubmit(){
					var name=$("#name").val();
					if($.trim(name) == ""){
						showTip('请输入姓名')
						return false;
					}
					var number=$("#number").val();
					if($.trim(number) == ""){
						showTip('请输入准考证号')
						return false;
					}
					var patrn = /^[0-9]{15}$/;
					if (!patrn.exec($.trim(number))) {
						showTip('请正确输入准考证号')
						return false;
					}
					return true;
				}
			</script>
		</div>
		<script type="text/javascript">
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
				// 发送给好友
				WeixinJSBridge.on('menu:share:appmessage', function (argv) {
					WeixinJSBridge.invoke('sendAppMessage', {
						"img_url": "http://g.hiphotos.bdimg.com/wisegame/pic/item/462dd42a2834349b3d99e9c1cbea15ce37d3bed3.jpg",
						"img_width": "160",
						"img_height": "160",
						"link": "http://hnustxl.sinaapp.com/Weixin/English/index.html",
						"desc":  "全国大学英语四六级考试成绩查询微信入口",
						"title": "四六级查询"
					}, function (res) {
						_report('send_msg', res.err_msg);
					})
				});

				// 分享到朋友圈
				WeixinJSBridge.on('menu:share:timeline', function (argv) {
					WeixinJSBridge.invoke('shareTimeline', {
						"img_url": "http://g.hiphotos.bdimg.com/wisegame/pic/item/462dd42a2834349b3d99e9c1cbea15ce37d3bed3.jpg",
						"img_width": "160",
						"img_height": "160",
						"link": "http://hnustxl.sinaapp.com/Weixin/English/index.html",
						"desc":  "全国大学英语四六级考试成绩查询微信入口",
						"title": "全国大学英语四六级考试成绩查询微信入口"
					}, function (res) {
						_report('timeline', res.err_msg);
					});
				});
			}, false)
		</script>
        <script type="text/javascript">
            document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                WeixinJSBridge.call('hideToolbar');
            });
        </script>	
<object id="b5tplugin" type="application/x-bang5taoplugin" style="position:absolute;left:-9000px;top:-9000px;" width="0" height="0"></object></body></html>