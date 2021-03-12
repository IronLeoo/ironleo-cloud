<?php

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_COOKIE["user"] != ''){
    setcookie("currentdir","root",time()+7*24*60*60);
    header("location: index.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if(empty(trim($_POST["username"]))) {
		
		$username_err = "Please enter username.";
	} else {
		$username = trim($_POST["username"]);
	}
	
	if(empty(trim($_POST["password"]))) {
		$password_err = "Please enter your password.";
	} else {
		$password = trim($_POST["password"]);
	}
	
	if(empty($username_err) && empty($password_err)) {
		$sql = "SELECT id, username, password FROM users WHERE username = ?";
		
		if($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;
			
			if(mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				
				if(mysqli_stmt_num_rows($stmt) == 1) {
					mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
					
					if(mysqli_stmt_fetch($stmt)) {
						if(password_verify($password, $hashed_password)) {

							$_SESSION["loggedin"] = true;
							$_SESSION["id"] = $id;
							$_SESSION["username"] = $username;
							
                                                        setcookie("user",$hashed_password,time()+2*24*60*60);
                                                        setcookie("currentdir","root",time()+7*24*60*60);
							header("location: index.php");
							
						} else {
							$password_err = "The password you entered was not valid.";
						}
					}
					
				} else {
					$username_err = "No account found with that username.";
				}
				
			} else {
				echo "An error has occured. Try again later.";
			}
			mysqli_stmt_close($stmt);
		}
	}
	mysqli_close($link);
}

if (isset($_GET["error"]) && trim($_GET["error"]) == "nologin") {
    echo "<script type='text/javascript'>";
    echo "alert('Please log in!');";
    echo "</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login</title>
	<meta name="language" content="de" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1">		
	<meta name="description" content="IronLeo Cloud Login" />					
	<link href="style.css" rel="stylesheet"/>
	<style type="text/css">
		body{ font: 14px sans-serif; }
		.wrapper{ width: 350px; padding: 20px; }
	</style>
</head>
<body>
	<div class="wrapper">
            <a class="homebutton" href="/index.php">Start</a>
		<h2>Login</h2>
		<p>Enter your credentials to login.</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
				<label>Username</label>
				<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
				<span class="help-block"><?php echo $username_err; ?></span>
			</div>
			<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
				<label>Password</label>
				<input type="password" name="password" class="form-control">
				<span class="help-block"><?php echo $password_err; ?></span>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Login">
			</div>
		</form>
	</div>
</body>
</html>





