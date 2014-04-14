<?php
		// Set time limit to indefinite execution

		set_time_limit (1);	
		$msg = $_GET['msg'];
		$host = "192.168.1.101";
		$port = 8080;
		
		set_time_limit(1);

		$socket = socket_create(AF_INET, SOCK_STREAM, 0);
		socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
		socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
		
		$result = socket_connect($socket,$host,$port) or die ("Could not bint to socket\n");
//		socket_set_timeout($socket, 1);		
//		socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>1, "usec"=>0));
		$res=socket_write($socket, $msg,strlen($msg)) or die ("Could not write to socket\n");
?>