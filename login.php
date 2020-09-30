<?php

$msg = "";$con = mysqli_connect("localhost","root","","everification_code");

if(isset($_POST['submit'])){

	$email =  mysqli_real_escape_string($con,$_POST['email']);
	$password = mysqli_real_escape_string($con,$_POST['password']);

	if($email == "" || $password == ""){
	 $msg = "Please Check Your Input";
	}else{
	 $sql = "SELECT `id`, `password`, `isconfirm` FROM `users` WHERE email='$email' ";
	 $run = mysqli_query($con,$sql);

	 if(mysqli_num_rows($run) > 0){
	  $data = mysqli_fetch_assoc($run);
	  if(password_verify($password, $data['password'])){
         if($data['isconfirm'] == 0){
             $msg = "Please verify your email";
         }else{
           $msg = "you loged in";
         }
	  }else{
	  	$msg = "Please Check Your Input";
	  }
	 }else{
	 	$msg = "Please Check Your Input";
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

			<form method="post" action="login.php">
				<input class="form-control" type="email"  name="email" placeholder="Email....."><br>
				<input class="form-control" type="text"  name="password" placeholder="Password......"><br>
				<input class="form-control btn btn-info" type="submit" name="submit" value="Login">
                 
			</form><br>
           <a type="button" onclick="pageRedirect()" class="text-right text-danger">Forgot Password ? Click here</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	function pageRedirect() {
      window.location.href = "http://localhost/mail20/forgotpassword.php";
    }  
</script>

</body>
</html>