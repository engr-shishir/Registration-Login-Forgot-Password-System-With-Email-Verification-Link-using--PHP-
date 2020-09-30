
 >## "Registration With Email Verification & Login & Forgoot Password System (PHP)"
<br/><br/><br/>



> ## Working Proces...........
  + Create a Database with  6(six) column
  + Create a users Table bellow sql comand or manually
  + 👇👇👇👇👇👇 
  

```sql
CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `isconfirm` tinyint(4) NOT NULL,
  `token` varchar(10) NOT NULL,
  `tokenExpire` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
  <br/><br/><br/><br/><br/><br/> 




+ ## Folder Structure<hr>
   * App
      - phpmailer
         + PHPMailerAutoload.php
         + class.smtp.php
         + class.phpmailer.php
      - registration.php
      - confirm.php
      - login.php
      - forgotpassword.php
      - resetpassword.php
  <br/><br/><br/><br/><br/><br/>




+ Create registration.php
  * Create a form with `(action="registration.php")` &  `(method="post")` Attribute
  * Create 5  `<input>` filds
  * get value from the inputs filds and put into separet variable
  * insert getting data into database table with `(isconfirm = 0)` and insert `(token = random token)` which is jenareting before submitng form in `function.php` file
  * Download [PHPMailerAutoload.php](https://github.com/phpList/phplist3/blob/master/public_html/lists/admin/PHPMailer/PHPMailerAutoload.php "Github") 
  * Download [class.smtp.php](https://github.com/KyleAMathews/phpmailer/blob/master/class.smtp.php "Github")
  * Download [class.phpmailer.php](https://github.com/PHPMailer/PHPMailer/blob/master/src/PHPMailer.php "Github")
  * Insert Downloaded `3` file into `phpmailer` folder
  * Send mail with `verification link & token`, which email is insert in email `<input>` fild
  * If user click verification link then database updated with `(isconfirm = 1)` `(token = "")` and redirect to `confirm.php` Page
  * If `confirm.php` execute done then it redirect user `login.php`
  * Now user can login with email and password
  * Intire code is bellow  ..............................👇👇👇👇👇👇 
  <br/><br/> 
 

+ Create login.php
    * Make sure your database connection
    * Create a login form with 2 `<input`> field.
    * get data from input field and check this data is avilabel avilabel or not and `(isconfirm = 1)` or not. If data is avilable but `(isconfirm != 0)`, user shuld verify email first. Otherwise user unable to loge in.
    * Sometime user forgoot his password and try to recover password.
    * For this he should be click `forgoot Password ? click here` option. Now he redirected to `forgootpassword.php` 
    * Intire code is bellow  ............................👇👇👇👇👇👇 
    <br/><br/> 

+ Create forgootpassword.php
   *  Make sure your database connection
   *  He put his correct registered email and click  `Reset Password` button.
   *  Now a reset password links send his email address and send update database to `(token = $token)` `( tokenExpire = DATE_ADD(NOW(), INTERVAL 5 MINUTE))`
   *  he should click this sending link and now he redirected to `resetpassword.php` and get a new automatic janerated `new password` and update database `(token = "")` `(password = '$newPasswordEncrypted')`
   *  Intire code is bellow  ............................👇👇👇👇👇👇 
<br><br><br><br><br><br>






>## registration.php
```php

<?php
$msg = "";
require 'phpmailer/PHPMailerAutoload.php';

$con = mysqli_connect(host, username, password, database name);

if (isset($_POST['submit']))
{
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    if ($name == "" || $email == "" || $password != $cpassword)
    {
        $msg = "Please Check Your Input";
    }
    else
    {

        $sql = "SELECT `id` FROM `users` WHERE email='$email' ";
        $run = mysqli_query($con, $sql);
        if (mysqli_num_rows($run) > 0)
        {
            $msg = "Email Already Exists";
        }
        else
        {
            $token = 'abcdefghijklmnopqrstuvwxwzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890$*@';
            $token = str_shuffle($token);
            $token = substr($token, 0, 10);

            $password = password_hash($password, PASSWORD_BCRYPT);

            $query1 = "INSERT INTO `users`(`name`, `email`, `password`, `isconfirm`, `token`) VALUES ('$name','$email','$password','0','$token')";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "Sender Email Account";
            $mail->Password = "Sender Email Account Password";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->setFrom('Sender Email Account', 'Sender Name');
            $mail->addAddress($email, $name);
            $mail->Subject = "Verify Your Email";
            $mail->isHTML(true);
            $mail->Body = "<h1 align=center>Please Click On The Link Bellow ::</h1><br><br>
<a align=center href='http://localhost/app/confirm.php?email=$email&token=$token'>Verify</a>";
            if (!$mail->send())
            {
                $msg = "Plese Try Carefully";
            }
            else
            {
                $msg = "You Have Been Rgistered! Please Verify Your Email";
                $run = mysqli_query($con, $query1);
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

			<?php if (isset($msg)) echo $msg ?>

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


```
<br><br><br><br><br><br><br><br>








>## confirm.php
```php
<?php
$con = mysqli_connect(host, username, password, database name);


if (!isset($_GET['email']) || !isset($_GET['token']))
{
    header('location: registration.php');
    exit();

}
else
{
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $token = mysqli_real_escape_string($con, $_GET['token']);

    $sql = "SELECT `id` FROM `users` WHERE email='$email' ";
    $run = mysqli_query($con, $sql);

    if (mysqli_num_rows($run) > 0)
    {

        $sql = "UPDATE users SET isconfirm= 1, token='' WHERE email='$email' ";
        $run = mysqli_query($con, $sql);

        header('location: login.php');
        exit();
    }

}

?>

```
<br><br><br><br><br><br><br><br>

>## login.php
```php

<?php
$msg = "";
$con = mysqli_connect(host, username, password, database name);

if (isset($_POST['submit']))
{

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    if ($email == "" || $password == "")
    {
        $msg = "Please Check Your Input";
    }
    else
    {
        $sql = "SELECT `id`, `password`, `isconfirm` FROM `users` WHERE email='$email' ";
        $run = mysqli_query($con, $sql);

        if (mysqli_num_rows($run) > 0)
        {
            $data = mysqli_fetch_assoc($run);
            if (password_verify($password, $data['password']))
            {
                if ($data['isconfirm'] == 0)
                {
                    $msg = "Please verify your email";
                }
                else
                {
                    $msg = "you loged in";
                }
            }
            else
            {
                $msg = "Please Check Your Input";
            }
        }
        else
        {
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

			<?php if (isset($msg)) echo $msg ?>

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
      window.location.href = "http://localhost/app/forgotpassword.php";
    }  
</script>

</body>
</html>


```
<br><br><br><br><br><br><br><br>




>## forgotpassword.php
```php

<?php
require 'phpmailer/PHPMailerAutoload.php';
require_once "function.php";
$msg = "";

$con = mysqli_connect(host, username, password, database name);

if (isset($_POST['submit']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    if ($email == "")
    {
        $msg = "Please Check Your Input";
    }
    else
    {
        $sql = "SELECT `id` FROM `users` WHERE email='$email' ";
        $run = mysqli_query($con, $sql);
        if (mysqli_num_rows($run) > 0)
        {

            $token = generateNewString();

            $sql = "UPDATE users SET  token='$token', tokenExpire = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email='$email' ";
            $run = mysqli_query($con, $sql);

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "Sender Account";
            $mail->Password = "Sender Account Passowrd";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->setFrom('Sender Account', 'Sender Name');
            $mail->addAddress($email);
            $mail->Subject = "Reset Password Link";
            $mail->isHTML(true);
            $mail->Body = "
			    Hi,<br><br>
	            
	            In order to reset your password, please click on the link below:<br>
	            <a href='
	            http://localhost/app/resetPassword.php?email=$email&token=$token
	            '>http://localhost/app/resetPassword.php?email=$email&token=$token</a><br><br>
	            
	            Kind Regards,<br>
	            My Name
		";
            if (!$mail->send())
            {
                $msg = "Plese Try Carefully";
            }
            else
            {
                $msg = "Reset Pasword Link Send In inbox";
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

			<?php if (isset($msg)) echo $msg ?>

			<form method="post" action="forgotpassword.php">
				<input class="form-control" type="email"  name="email" placeholder="Enter Registered Email"><br>
				<input class="form-control btn btn-info" type="submit" name="submit" value="Registration">
			</form>
		</div>
	</div>
</div>



</body>
</html>


```
<br><br><br><br><br><br><br><br>

>## resetpassword.php
```php
<?php
require_once "function.php";
$msg = "";
$con = mysqli_connect("localhost", "root", "", "everification_code");

if (isset($_GET['email']) && isset($_GET['token']))
{

    $email = mysqli_real_escape_string($con, $_GET['email']);
    $token = mysqli_real_escape_string($con, $_GET['token']);

    $run = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND token='$token' AND token<>'' AND tokenExpire > NOW() ");

    if (mysqli_num_rows($run) > 0)
    {
        $newPassword = generateNewString();
        $newPasswordEncrypted = password_hash($newPassword, PASSWORD_BCRYPT);
        mysqli_query($con, "UPDATE users SET token='', password = '$newPasswordEncrypted'
			WHERE email='$email' ");

        echo "Your New Password Is $newPassword<br><a href='login.php'>Click Here To Log In</a>";
    }

}
else
{
    redirectToLoginPage();
}

?>

```
<br><br><br><br>




>##  function.php
```php

<?php
function generateNewString($len = 10)
{
    $token = "poiuztrewqasdfghjklmnbvcxy1234567890";
    $token = str_shuffle($token);
    $token = substr($token, 0, $len);

    return $token;
}

function redirectToLoginPage()
{
    header('Location: login.php');
    exit();
}

?>


```


