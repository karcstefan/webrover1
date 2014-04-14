<?php
		$msg = $_GET['msg'];
		$host = "192.168.1.101";
		$port = 8080;
		$socket = socket_create(AF_INET, SOCK_STREAM, 0);
		$result = socket_connect($socket,$host,$port) or die ("Could not bint to socket\n");
		$res=socket_write($socket, $msg,strlen($msg)) or die ("Could not write to socket\n");		
?>