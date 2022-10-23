<?php
//include config
require_once('../includes/config.php');


//check if already logged in
if( $user->is_logged_in() ){ header('Location: index.php'); } 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin- Login</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="shortcut icon" href="..\..\bararchet\images\logo.png" type="image/x-icon">
   <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-lg-6 mx-auto">
<h1 align="center" class="text-success">Teach for Change and Impact Iniatitive BLOG- Please login to use</h1>
	<div id="login">

	<?php

	//process login form if submitted
	if(isset($_POST['submit'])){

		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		if($user->login($username,$password)){ 

			//logged in return to index page
			header('Location: index.php');
			exit;
		

		} else {
			$message = '<p class="error">Wrong username or password</p>';
		}

	}//end if submit

	if(isset($message)){ echo $message; }
	?>
</div>
	<form action="" method="post">
	<p><label>Username</label><input class='form-control' type="text" name="username" value=""  /></p>

	<p><label>Password</label><input class='form-control' type="password" name="password" value=""  /></p>

	<p><label></label><input class='btn btn-success' type="submit" name="submit" value="Login"  /></p>
	</form>

</div>
</div>
</div>
</body>
</html>
