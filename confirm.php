

<?php
 
$con = mysqli_connect("localhost","root","","everification_code");

 function redirect(){
 	header('location: registration.php');
 	exit();
 }





if(!isset($_GET['email']) || !isset($_GET['token'])){
	redirect();
	
}else{
  $email = mysqli_real_escape_string($con,$_GET['email']);
  $token = mysqli_real_escape_string($con,$_GET['token']);

      $sql = "SELECT `id` FROM `users` WHERE email='$email' ";
      $run = mysqli_query($con,$sql);

      if(mysqli_num_rows($run) > 0){

      $sql = "UPDATE users SET isconfirm= 1, token='' WHERE email='$email' ";
      $run = mysqli_query($con,$sql);

      	header('location: login.php');
        exit();
      }
  
}


?>