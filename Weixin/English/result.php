<?php
//  学信网  四六级
session_start();
if(empty($_SESSION['status'])||$_SESSION['status']==0){
	if($_GET['wei']==1){
		header("Location: ../../Admin/login/index?wei=1");
	}else{
		header("Location: ../../Admin");
	}
}
if(!empty($_POST)){
	$ch = curl_init();
	$url="http://www.chsi.com.cn/cet/query?zkzh=".$_POST['number']."&xm=".urlencode($_POST['name']);//430111151108224   %E5%88%98%E5%85%B4%E9%BE%99
	$url1="http://www.chsi.com.cn/cet/";
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER,$url1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
    /*
	$IP=array("202.106.16.36:3128");
	$k=array_rand($IP,1);// 获取随机 IP 的下标值
	curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
	curl_setopt($ch, CURLOPT_PROXY, $IP[$k]); //代理服务器地址
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
	*/
    
	$English = curl_exec($ch);
	curl_close($ch);
	
	$pattern='/(?:<th>|<td>|"top">|"color666">).{1,}(?:<\/th>|<\/td>|<\/span>)|[0-9]{1,3}\s*(?:<\/span>|<br \/>|<\/td>)/';
	preg_match_all($pattern,$English,$out);
	
	$pattern='/[0-9]{1,3}/';
	preg_match_all($pattern,$out[0][11],$out1);$out[0][11]=$out1[0][0];
	preg_match_all($pattern,$out[0][13],$out1);$out[0][13]=$out1[0][0];
	preg_match_all($pattern,$out[0][15],$out1);$out[0][15]=$out1[0][0];
	preg_match_all($pattern,$out[0][17],$out1);$out[0][17]=$out1[0][0];
	// var_dump($out);
}else{
	header("Location: index.html");
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
		<div class="banner">
			<div id="wrapper">
				<div id="scroller" style="float:none">
					<ul id="thelist">
						<li style="float:none">
							<img src="./public/logo.jpg" alt="" style="width:100%">
						</li>
					</ul>
				</div>
			</div>
			<div class="clr"></div>
		</div>
		<div class="cardexplain">
			<?php
			/*
			if($out[0][11]>=425){
				echo '<ul class="round roundgreen" id="success">
						<li style="height:40px;line-height:40px; font-size:16px; text-align:center">恭喜你成功通过</li>
					 </ul>';
			}else{
				echo '<ul class="round roundyellow" id="success">
						<li style="height:40px;line-height:40px; font-size:16px; text-align:center">你未能成功通过</li>
					  </ul>';
			}
            */
			?>	
			<ul class="round">
				<li class="title mb"><span class="none">查询结果</span></li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>考生姓名</th>
								<?php echo $out[0][1]; ?>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>学校</th>
								<?php echo $out[0][3]; ?>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>准考证号</th>
								<?php echo $out[0][7]; ?>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>考试时间</th>
								<?php echo $out[0][9]; ?>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>听力</th>
								<td><?php echo $out[0][13]; ?></td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>阅读</th>
								<td><?php echo $out[0][15]; ?></td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>写作翻译</th>
								<td><?php echo $out[0][17]; ?></td>
							</tr>
						</tbody>
					</table>
				</li>
				<li class="nob" style="height:30px;line-height:30px;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
						<tbody>
							<tr>
								<th>成绩总分</th>
								<td><?php echo $out[0][11]; ?></td>
							</tr>
						</tbody>
					</table>
				</li>
			</ul>
            <div id="mcover" onclick="document.getElementById(&#39;mcover&#39;).style.display=&#39;&#39;;" style=""><img src="./public/guide.png"></div>
            <div id="mess_share">
                <div id="share_1">
                    <button class="button2" onclick="document.getElementById(&#39;mcover&#39;).style.display=&#39;block&#39;;"><img src="./public/icon_msg.png"> 发送给朋友</button>
                </div>
                <div id="share_2">
                    <button class="button2" onclick="document.getElementById(&#39;mcover&#39;).style.display=&#39;block&#39;;"><img src="./public/icon_timeline.png"> 分享到朋友圈</button>
                </div>
            </div>	
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
						"title": "四六级查询"
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