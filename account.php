<?php
    ob_start();
    session_start();
    require 'f.php';
    if(isset($_SESSION['user']))
   {
      if(password_verify($_SESSION['user'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'],$_SESSION['check']))
      {
         autologOut();
         $user = $_SESSION['user'];
         $dept = $_SESSION['dept'];
      }
      else {destroy_data_and_session(); header("Location:login.php");}
   }
   else header("Location:login.php");
   $success = false;
   $error = "";

   if(isset($_POST['oldp']) && isset($_POST['newp']) && isset($_POST['cnewp']))
   {
      $temp_password = validateOld($_POST['oldp']);
      $temp_new = validateNew($_POST['newp']);
      $temp_cnew = validateCnew($_POST['cnewp'],$_POST['newp'],$_POST['oldp']);


      if($temp_password && $temp_new && $temp_cnew)
      {
         $p = "";
         $stmt = $c->prepare("SELECT password FROM users WHERE employee = ?");
         $stmt->bind_param('s',$po);
         $po = $user;
         $stmt->execute();
         $result  = $stmt->get_result();
         $line = $result->fetch_array(MYSQL_NUM);
         $p = $line[0];
         if(password_verify($temp_password, $p))
         {
            if(password_verify($temp_new, $p)) $error = "New Password Cannot be Equal to Old Password";
            else
            {
               $stmt2 = $c->prepare("UPDATE users SET password = ? WHERE employee = ? ");
               $stmt2->bind_param('ss',$pd,$u);
               $u = $user;
               $pd = password_hash($temp_new, PASSWORD_DEFAULT);
               $f = $stmt2->execute();
   
               if($f) $success = true;
   
            }
         }
         else $error = "Invalid Input for Old Password";
      }     
   }
   function validateOld($field)
   {
      global $error;
      trim($field) == ""? $error = "Invalid Input For Old Password" : $error = "";
      strlen($field) < 8? $error = "Ínvalid Input for Old Password" : $error = "";
      preg_match('/[^\w@]/i', $field) ? $error = "invalid Input for Old Password" : $error = "";
      return ($error == "")?sanitizeInput($_POST['oldp']) : "";
   }

   function validateNew($field)
   {
      global $error;
      trim($field) == ""? $error = "Invalid Input For New Password" : $error = "";
      strlen($field) < 8? $error = "Ínvalid Input for New Password" : $error = "";
      preg_match('/[^\w@]/i', $field) ? $error = "invalid Input for New Password" : $error = "";
      return ($error == "")?  sanitizeInput($_POST['newp']) : "";
   }

   function validateCNew($field, $field1, $field2)
   {
      global $error;
      trim($field2) == ""? $error = "Invalid Input For Confirm New Password" : $error = "";
      strlen($field2) < 8? $error = "Ínvalid Input for Confirm New Password" : $error = "";
      preg_match('/[^\w@]/i', $field2) ? $error = "invalid Input for Confirm New Password" : $error = "";
      $field2 == $field ? $error = "New Password cannot be Equal to Old Password" : $error = "";
      $error = ($field == $field1)? "" : "New Pasword and Confirm Password not match!";
      return ($error == "")? true : "";
   } 

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Home</title>
      <script src = "js/OSC.js"></script>
      <link rel = "stylesheet" href = "bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/css/bootstrap.min.css">
      <link rel = "stylesheet" href = "css/login.css" >
      <link rel="stylesheet" href="css/dashboard.css">
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/cbox.css">
      <script>
         function validateForm(form)
         {
            fail = validateOld(form.oldp.value);
            fail += validateNew(form.newp.value);
            fail += validateCNew(form.oldp.value, form.newp.value, form.cnewp.value);
            let i = document.getElementById('error');
            if(fail == "") return true;
            else {i.innerHTML = `<div class = "alert alert-danger"> ${fail}</div> `;return false;}

         }

         function validateOld(field)
         {
            if(/[\w]/.test(field) && field.length >= 8) return "";
            else return "Invalid Input for Old Password <br>";
         }
         function validateNew(field)
         {
            if(/[^\w@]/.test(field) || field.length < 8) return "Invalid Input for New Password<br/>";
            else return "";
         }
         function validateCNew(field2, field1, field)
         {
            if(field1.trim() == "") return "Invalid Input for Confirm Password!<br>";
            else
            {
               if(field1 === field) 
               {
                  if(field1 === field2) return "New Password cannot be Equal to Old Password!";
                  else return "";
               }
               else return "New Pasword and Confirm Password not match!<br>";
            }
         }
         
      </script>
       <style>
         .usr{
            box-sizing: border-box;
            padding: 8px;
            font-size: 15px;
         }
         .usr a, .usr a:hover, .usr a:active{
            color:white;
            text-decoration:none;
         }
         .usr a i {
            font-size: 16px;
         }
      </style>
   </head>
   <body>
   <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-drill">
         <a class="navbar-brand" href="#">Drillog Petro-Dynamics Limited</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarCollapse">
               <ul class="navbar-nav mr-auto"></ul>
               <ul class="navbar-nav px-3">
               <li class="nav-item text-nowrap usr">
                  
               <a href="#">
                  <i class = "fa fa-user"></i>
                  <?php  echo htmlspecialchars($user); ?>   
               </a>
                        
                  </li>
               <li class = "nav-item dropdown">
                     <a href="" class="nav-link dropdown-toggle" id = "navbarDropdownMenuLink" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "false" onclick = "sN()">
                        <i class="fa fa-bell"></i>
                     </a>
                     <div class = "dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="padding-top: 0px; padding-bottom: 0px;left:-120px;">
                        <div class="card example-1 scrollbar-deep-purple bordered-deep-purple thin" id = "noti">
                           <div class="card-body notification cv">
                              
                           </div>   
                        </div>
                     </div>     
                  </li>
                  <form class="form-inline d-flex dropdown">
                        <input class="form-control form-control-sm" type="text" placeholder="Search" id = "search">
                        <button class="btn btn-outline-success btn-sm" type="button"  data-toggle="dropdown" onclick = "searchs()">Go</button>
                        <ul class="dropdown-menu" role="menu"  style="padding-top: 0px; padding-bottom: 0px;" id = "search_results">
                        <div class="card example-1 scrollbar-deep-purple bordered-deep-purple thin">
                           <div class="card-body"  id = "results" style="padding: 0;">
                           <center>
                           <div class="spinner-border text-success" role="status">
                              <span class="sr-only">Loading...</span>
                           </div>
                           </center>
                           </div>   
                        </div>
                        </ul>
                  </form>
                                 
                  <li class="nav-item text-nowrap ">
                     <a href="javascript:void(0)" class="nav-link" onclick = "logout()">Sign Out 
                        <i class="fa fa-sign-out"></i>
                     </a>
                  </li>
               </ul>
         </div>
   </nav>
        <div class="container-fluid">
         <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
               <div class="sidebar-sticky">
               <ul class="nav flex-column">
                  <li class="nav-item">
                     <a class="nav-link " href="home.php">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                     Dashboard <span class="sr-only">(current)</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link " href="order.php">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                     Order
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link active" href="account.php">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                     Account
                     </a>
                  </li>
               </ul>

               <!-- <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                  <span>Saved reports</span>
                  <a class="d-flex align-items-center text-muted" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                  </a>
               </h6>
               <ul class="nav flex-column mb-2">
                  <li class="nav-item">
                     <a class="nav-link" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                     Current month
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                     Last quarter
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                     Social engagement
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                     Year-end sale
                     </a>
                  </li>
               </ul> -->
               </div>
               
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
               <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
               <h1 class="h2">Change Password</h1>
               </div>
               <div id = "modal-holder">
              
               </div>
               <form method = "POST" onsubmit = "return validateForm(this)" action = "" autocomplete = "off"> 
                  <div id = "error">
                     <?php echo($error == "")? "": "<div class = 'alert alert-danger'>".htmlspecialchars($error);"</div>"?>
                     <?php echo($success)? "<div class = 'alert alert-success'> Password was Changed Successfully </div>" : ""; ?>
                  </div>
                  <div class="form-group">
                     <label for="formGroupExampleInput">Old Password</label>
                     <input type="password" class="form-control" id="formGroupExampleInput" placeholder="Old Password" name = "oldp" required>  
                  </div>
                  <div class="form-group">
                     <label for="formGroupExampleInput1">New Password</label>
                     <input type="password" class="form-control" id="formGroupExampleInput1" placeholder="New Password" name = "newp" required>
                  </div>
                  <div class="form-group">
                     <label for="formGroupExampleInput2">Confirm New Password</label>
                     <input type="password" class="form-control" id="formGroupExampleInput2" placeholder="Confirm New Password" name = "cnewp" required>
                  </div>
                  <div style="margin-top: 30px;">
                  <button type="submit" class="btn btn-success">Reset</button>
                  </div>
                 
               </form>
            </main>
         </div>
      </div>


      <script>window.jQuery || document.write('<script src="vendor/jquery-slim.min.js"><\/script>')</script><script src="bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
      <script>employee = "<?php echo htmlspecialchars($user);?>";department ="<?php echo htmlspecialchars($dept);?>";</script>
      <script src = "js/oj.js"></script>
      <script>        
        
         
      </script>
    </body>
</html>
<?php ob_end_flush(); ?>