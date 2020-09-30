<?php
require 'phpmailer/PHPMailerAutoload.php';
require_once "function.php";
$msg = "";


$con = mysqli_connect("localhost","root","","everification_code");

if(isset($_POST['submit'])){
   $email =  mysqli_real_escape_string($con,$_POST['email']);
   if($email == "" )
	{
       $msg = "Please Check Your Input";
	}else{
	  $sql = "SELECT `id` FROM `users` WHERE email='$email' ";
      $run = mysqli_query($con,$sql);
      if(mysqli_num_rows($run) > 0){
      	
      	$token = generateNewString();

	     $sql = "UPDATE users SET  token='$token', tokenExpire = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email='$email' ";
	     $run = mysqli_query($con,$sql);

		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->Host="smtp.gmail.com";
		$mail->SMTPAuth=true;
		$mail->Username="shishirbhuiyan83@gmail.com";
		$mail->Password="bismillahshishirbhuiyan83@gmail.com";
		$mail->SMTPSecure="tls";
		$mail->Port=587;
		$mail->setFrom('shishirbhuiyan83@gmail.com','Shishir');
		$mail->addAddress($email);
		$mail->Subject="Reset Password Link";
		$mail->isHTML(true); 
		$mail->Body="
			    Hi,<br><br>
	            
	            In order to reset your password, please click on the link below:<br>
	            <a href='
	            http://localhost/mail20/resetPassword.php?email=$email&token=$token
	            '>http://localhost/mail20/resetPassword.php?email=$email&token=$token</a><br><br>
	            
	            Kind Regards,<br>
	            My Name
		";
		if(!$mail->send())
		{
			$msg ="Plese Try Carefully";
		}
		else{
		 $msg ="Reset Pasword Link Send In inbox";
		}

      }
	}
}


?>


<!DOCTYPE html>
<html>
<head>
<title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>

<div class="container" style="margin-top: 100px;">
	<div class="row justify-content-center">
		<div class="col-md-6 col-offset-3" align="center">

			<?php if(isset($msg)) echo $msg ?>

			<form method="post" action="forgotpassword.php">
				<input class="form-control" type="email"  name="email" placeholder="Enter Registered Email"><br>
				<input class="form-control btn btn-info" type="submit" name="submit" value="Registration">
			</form>
		</div>
	</div>
</div>



</body>
</html>