<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php
		/*
			设计一个类：mysql数据库操作类
			设计目标：
			1.该类一实例化，就可以自动连接上mysql数据库;
			2.该类可以单独去设定要使用的链接编码(set names XXX);
			3.该类可以单独去设定要使用的数据库(use XXX);	
			4.可以主动关闭链接
		*/
		class MySQLDB{
			private $link = null;  // 用于存储连接成功之后的资源
			private $host;
			private $port;
			private $user;
			private $pass;
			private $charset;
			private $dbname;

			// 实现单例第2步：用于储存唯一的单例对象
			private static $instance = null;
			// 实现单例第3步：
			static function GetInstance($config){
				// 没有就创建(判断不严谨 self::$instance = 1 也可以通过)
				// if(!isset(self::$instance)){
				// 判断是否是它的实例
				if(!(self::$instance instanceof self)){
					self::$instance = new self($config); // 就创建并保存
				}
				// 有就返回
				return self::$instance;
			}
			// 实现单例第4步：私有化这个克隆的魔术方法
			private function __clone(){}

			// 实现单例第1步：
			private function __construct($config){
				$this->host = !empty($config['host'])?$config['host']:'localhost';
				$this->port = !empty($config['port'])?$config['port']:3306;
				$this->user = !empty($config['user'])?$config['user']:'root';
				$this->pass = !empty($config['pass'])?$config['pass']:'';
				$this->charset = !empty($config['charset'])?$config['charset']:'utf8';
				$this->dbname = !empty($config['dbname'])?$config['dbname']:'';
				// 然后连接数据库
				$this->link = @mysql_connect("{$this->host}:{$this->port}","{$this->user}","{$this->pass}") or die("连接失败");
				// 设置数据库编码
				$this->setCharset($config['charset']); 
				
				// 设定要使用的数据库名
				$this->selectDB($config['dbname']);
			}
			// 重新设置编码
			function setCharset($charset){
				mysql_query("set names $charset");
			}
			// 可以设定要使用的链接编码
			function selectDB($dbname){
				mysql_query("use $dbname");
			}
			// 可关闭链接
			function closeDB(){
				mysql_close($this->link);
			}

			// 这个方法为了执行一条增删改语句，他可以返回真假结果
			function exec($sql){
				/*$result = mysql_query($sql);
				if($result === false){
					// 语句执行失败，则需要处理这种失败情况：
					echo "<p>sql语句执行失败，请参考如下信息：";
					echo "<br />错误代号：".mysql_errno(); // 获取错误代号
					echo "<br />错误信息：".mysql_error(); // 获取错误提示内部
					echo "<br />错误语句：".$sql;
					die();
				}*/
				$result = $this->query($sql);
				return true;
				
			}

			// 这个方法为了执行一条返回一行数据的语句，他可以返回一维数组
			// 数组的下标，就是sql语句中的取出的字段名
			function GetOneRow($sql){
				$result = $this->query($sql);

				// 如果没有出错，则开始处理数据，以返回数组。此时$result是一个结果集
				$rec = mysql_fetch_assoc($result); // 取出第一行数据
				mysql_free_result($result); // 释放资源(销毁结果集)
				return $rec;
			}

			// 这个方法为了执行sql返回多行数据的语句，他可以返回二维数组
			function GetRows($sql){
				$result = $this->query($sql);

				// 如果没有出错，则开始处理数据，以返回数组。此时$result是一个结果集
				$arr = array(); // 空数组,用于存放要返回的结果数组(二维)
				while ($rec = mysql_fetch_assoc($result)) {
					$arr[]=$rec;
				}
				mysql_free_result($result); // 释放资源(销毁结果集)
				return $arr;
			}

			// 这个方法为了执行一条返回一个数据的语句,他可以返回一个直接值
			// 这条语句类似这样：select count(*) as c from user_list;
			function GetOneData($sql){
				$result = $this->query($sql);
				// 这里必须使用mysql_fetch_row,因为不知道字段名是什么
				$rec = mysql_fetch_row($result);  // 数组 array(0=>5)
				mysql_free_result($result); // 释放资源(销毁结果集)
				$data = $rec[0];
				return $data;
			}

			// 这个方法用于执行任何sql语句,并进行错误处理,或返回结果
			function query($sql){
				$result = mysql_query($sql,$this->link);
				if($result === false){
					// 语句执行失败，则需要处理这种失败情况：
					echo "<p>sql语句执行失败，请参考如下信息：";
					echo "<br />错误代号：".mysql_errno(); // 获取错误代号
					echo "<br />错误信息：".mysql_error(); // 获取错误提示内部
					echo "<br />错误语句：".$sql;
					die();
				}
				return $result; // 返回的是"执行的结果"
			}
		}
	?>
</body>
</html>