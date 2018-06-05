<?php
	class BaseController{
		// 设置网页编码
		function __construct(){
			header("content-type:text/html;charset=utf-8");
		}

		// 显示一定的简短提示文字，然后，自动跳转(可以设定停留的时间秒数)
		function goToUrl($msg,$url,$time){
			echo "<font color = red>$msg</font>";
			echo "<br /><a href='$url'>返回</a>";
			echo "<br />{$time}秒之后自动跳转。";
			header("refresh:$time;url=$url"); // 自动定时跳转功能
		} 
	}
?>