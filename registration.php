<?php
$msg = "";
 require 'phpmailer/PHPMailerAutoload.php';

$con = mysqli_connect("localhost","root","","everification_code");


if(isset($_POST['submit'])){
	$name =  mysqli_real_escape_string($con,$_POST['name']);
	$email =  mysqli_real_escape_string($con,$_POST['email']);
	$password = mysqli_real_escape_string($con,$_POST['password']);
	$cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

	if($name == "" || $email == "" || $password != $cpassword)
	{
       $msg = "Please Check Your Input";
	}else{
      
      $sql = "SELECT `id` FROM `users` WHERE email='$email' ";
      $run = mysqli_query($con,$sql);
      if(mysqli_num_rows($run) > 0){
      	$msg = "Email Already Exists";
      }else{
      	$token = 'abcdefghijklmnopqrstuvwxwzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890$*@';
      	$token = str_shuffle($token);
      	$token = substr($token,0,10);

      	$password = password_hash($password, PASSWORD_BCRYPT);


      	$query1 = "INSERT INTO `users`(`name`, `email`, `password`, `isconfirm`, `token`) VALUES ('$name','$email','$password','0','$token')";

	    


$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host="smtp.gmail.com";
$mail->SMTPAuth=true;
$mail->Username="shishirbhuiyan83@gmail.com";
$mail->Password="bismillahshishirbhuiyan83@gmail.com";
$mail->SMTPSecure="tls";
$mail->Port=587;
$mail->setFrom('shishirbhuiyan83@gmail.com','Shishir');
$mail->addAddress($email, $name);
$mail->Subject="Verify Your Email";
$mail->isHTML(true); 
$mail->Body="<h1 align=center>Please Click On The Link Bellow ::</h1><br><br>
<a align=center href='http://localhost/mail20/confirm.php?email=$email&token=$token'>Verify</a>";
if(!$mail->send())
{
	$msg ="Plese Try Carefully";
}
else{
 $msg ="You Have Been Rgistered! Please Verify Your Email";
	$run = mysqli_query($con,$query1);
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

			<form method="post" action="registration.php">
				<input class="form-control" type="text"  name="name" placeholder="Name......"><br>
				<input class="form-control" type="email"  name="email" placeholder="Email....."><br>
				<input class="form-control" type="text"  name="password" placeholder="Password......"><br>
				<input class="form-control" type="text"  name="cpassword" placeholder="Confirm Password......"><br>
				<input class="form-control btn btn-info" type="submit" name="submit" value="Registration">
			</form>
		</div>
	</div>
</div>


</body>
</html>