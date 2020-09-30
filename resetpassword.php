<?php
require_once "function.php";
$msg = "";
$con = mysqli_connect("localhost","root","","everification_code");

	if (isset($_GET['email']) && isset($_GET['token'])) {

		$email = mysqli_real_escape_string($con,$_GET['email']);
		$token = mysqli_real_escape_string($con,$_GET['token']);




	$run = mysqli_query($con,"SELECT id FROM users WHERE email='$email' AND token='$token' AND token<>'' AND tokenExpire > NOW() ");



	if (mysqli_num_rows($run) > 0) {
		//$data = mysqli_fetch_assoc($run);
		//echo $data['id'];
		$newPassword = generateNewString();
		//echo $newPassword;
		$newPasswordEncrypted = password_hash($newPassword, PASSWORD_BCRYPT);
	    //echo $newPasswordEncrypted;

		mysqli_query($con,"UPDATE users SET token='', password = '$newPasswordEncrypted'
			WHERE email='$email' ");

		echo "Your New Password Is $newPassword<br><a href='login.php'>Click Here To Log In</a>";
	}
          


	}else{
	  redirectToLoginPage();
	}

?>