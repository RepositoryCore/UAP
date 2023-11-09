<?php 
	session_set_cookie_params(86400); 
	ob_start();
	session_start();

	require 'connection/config/connection2.php';
	$obj_admin = new Admin_Class();
