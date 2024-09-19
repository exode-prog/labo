<?php 
if(!function_exists('authenticate')){
	function authenticate(){
		session_start(); 
		if (!isset($_SESSION['user'])) {
			header('location:../admin/authentification.php');
		}
	}
}
