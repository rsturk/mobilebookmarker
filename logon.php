<?php
session_start();

 $address = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/";

if(isset($_GET['id'])){
    if($_GET['id'] == 'logoff'){
        // reset Session note
	//    $_SESSION = array();
	//    session_destroy();

	unset($_SESSION['authenticated']);
	unset($_SESSION['user']);
	unset($_SESSION['pwd']);

    }
}
if(isset($_SESSION['user'])){
	if(($_SESSION['user'] != "" && $_SESSION['user'] != null)){
		header('Location: ' . $address . 'index.php');
	}
}

// process script only if the form has been submitted
if (array_key_exists('login', $_POST)) {

	$username = trim($_POST['username']);
	$pwd = trim($_POST['pwd']);
	
	$_SESSION['authenticated'] = 'ChangeTheKey';
	$_SESSION['user'] = $username;
	$_SESSION['pwd'] = $pwd;
	header('Location: ' . $address . 'index.php');
	
}
?>
<!doctype html>
<head>
	<title>Mobile Bookmarker - Log on</title>  

        <meta name="viewport" content="height=device-height,width=device-width" >
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" />  
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js"></script>
	
</head>
<body>
    <div data-role="page" data-theme="a">
	<div data-role="header" data-theme="a" style="color: #f0f0f0;" data-nobackbtn="true">
	    <h1>Bookmark Login</h1>
	</div>

	<div data-role="content"> 
        <!-- <div id="loginform"> -->
	   <form id="form1" name="form1" method="post" action="" data-transition="slide">
               <p>
                   <label for="textfield">Username:</label>
	           <input type="text" name="username" id="username" />
	       </p>
	       <p>
	           <label for="textfield">Password:</label>
         	   <input type="password" name="pwd" id="pwd" />
	       </p>
	       <p>
		   <!-- <input name="login" type="submit" id="login" value="Log in"  />  -->
		   <button type="submit" data-theme="a" name="login" value="Log in" id="login">Submit</button> 
               </p>
	       <p>
          
               </p>
           </form>

	</div>
	 <?php
             if (isset($error)) {
              echo "<div data-role='footer' style='text-align: center; color: #c8ed34;'><p>$error</p></div>";
             }
         ?>
	
	<div data-role="footer">
	    <h1><?php echo $userName; ?></h1>
        </div>
	
</div>

</body>
</html>
