<?php  
    ob_start();
    session_start();
    require_once 'f.php';
    $error = $temp_username = $temp_password = "";
    if(isset($_SESSION['check']))
    {
        if(isset($_SESSION['user']))
        {
            if(password_verify($_SESSION['user'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'],$_SESSION['check'])) header('Location:home.php');
        }
        else echo "No!";
    }
    if(isset($_GET['fl']))
    {
        destroy_data_and_session();
        unset($_GET['fl']);
        header("Location:login.php");
    }
    if(isset($_POST['username']) && isset($_POST['password']))
    {
        $temp_username = sanitizeInput($_POST['username']);
        $temp_password = sanitizeInput($_POST['password']);

        if($temp_password == "" || $temp_username == "")
        {
            $error = "Input a Valid Username and Password!";
        }
        else
        {
            $query = "SELECT * FROM users WHERE employee = '$temp_username'";
            $result = queryMySql($query);            
            if(!$result)
            {
                $error = "Input a Valid Username and Password!";
            }
            else
            {
                if($result->num_rows == 1)
                {
                    $row = $result->fetch_array();
                    if( $row['password'] === "$2y$10$7jRGwtOOhnoV0kSqFBSB0eJIvkDEyhrKcBbN53AFriQFA8SsHRWxm")
                    {
                        if($row['status'] == "LOGGED IN")
                        {
                            $error = "Please Try Again Later!";
                        }
                        else{
                            $_SESSION['user'] = $row['employee'];
                            $_SESSION['dept'] = gD($row['user_ID']);
                            if(insertLog(1)) 
                            { 
                                if(isset($_SESSION['dept']))
                                {
                                    $_SESSION['initiated'] = 1;
                                    $_SESSION['check'] = password_hash($_SESSION['user'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'], PASSWORD_DEFAULT);
                                    $_SESSION['in_time'] = date("Y-m-d H:i:s");
                                    
                                    session_regenerate_id();
                                    header("Location:home.php");
                                }
                                else {destroy_data_and_session(); $error = "Please Try Again Later!";}
                            }
                            else {destroy_data_and_session(); $error = "Please Try Again Later!";}
                        }
                    }
                    else
                    {
                        $error = "Input a Valid Username and Password!";
                    }
                }
                else
                {
                    $error = "Input a Valid Username and Password!";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>709 Login</title>
    <script src = "js/OSC.js"></script>
    
    <link rel = "stylesheet" href = "bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "css/login.css" >
    <!-- <link href="/docs/4.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    
    <script>


        function formValidate(form)
        {
            fail = "";
            fail += validateUsername(form.username.value);
            fail += validatePassword(form.password.value);
           
            if(fail == "") return true;
            else
            { 
                
                return false;
            }
        }

        function validateUsername(field)
        {
            return (field == "")? "No Username was Entered" : "";
        }
        function validatePassword(field)
        {
            return (field == "") ? "No Password was Entered\n" : ""; 
        }
    </script>
</head>
<body>
    <!-- Navbar -->
    

    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-drill">
        <a class="navbar-brand" href="#">Drillog Petro-Dynamics Limited</a>
        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
            </ul>
            <form class="form-inline mt-2 mt-md-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div> -->
    </nav>
   <div class="text-center">
        <form class="form-signin" method = "POST" action = " " onsubmit = "return formValidate(this)" >
            <!-- <img class="mb-4" src="/docs/4.3/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72"> -->
            <h1 class="h3 mb-3 font-weight-normal">709 Log In</h1>
            <?php echo ($error == "")? "" : "<div class = 'alert alert-danger' role = 'alert'>".htmlspecialchars($error)."</div>";?>
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="text" id="inputEmail" class="form-control" placeholder="Username"  name = "username" required autofocus autocomplete = "off">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" name = "password" required autocomplete = "off"> 
            <div class="checkbox mb-3">
                <label>
                <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="btn btn-md btn-login btn-block " type="submit">Sign in</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2017-2019</p>
        </form>
   </div>
    


   
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script>window.jQuery || document.write('<script src="vendor/jquery-slim.min.js"><\/script>')</script><script src="bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>

</body>
</html>