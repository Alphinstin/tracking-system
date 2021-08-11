<?php
     ob_start();
     session_start();
     require_once 'f.php';
     //destroy_data_and_session();
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
     $error = "";
     $success = false;
     if(isset($_POST['description']) && isset($_POST['send_to']))
     {
        $ddd= "";
         $y = false;
         $stage = 1;
         $description  = validateDescription($_POST['description']);
         $send_to      = validateSendTO($_POST['send_to']);
         if(isset($_POST['involved'])) $involved     = validateInvolved($_POST['involved']);
         else $involved = [];

         if(strlen($description) > 0 && strlen($send_to) > 0 && count($involved) >= 0)
         {
            
            if($send_to == "ACCOUNTS") $stage = 2;

            if($dept == "SUPPLY CHAIN") $ddd = "SC";
            else $ddd = $dept;
            // GET THE LAST iD
            $query = "SELECT order_id FROM department_order WHERE order_id LIKE 'PDL-$ddd-%' ORDER BY id DESC LIMIT 1";
            $result = queryMySQL($query);
            $last = $result->fetch_array();
            $id = $last[0];
           
            $id = preg_replace("/PDL-$ddd-/i","", $id);
            $id = $id + 1;
            $members = array();
            $tmp = array();
            if(count($involved) > 0)
            {
               $tmp = $involved;
               array_push($tmp, $send_to);
               array_push($tmp, $dept);
               
               for ($i=0; $i < count($tmp); $i++) { 
                  $query = "SELECT user_ID FROM departments WHERE department = '$tmp[$i]'";
                  $result = queryMySQL($query);
                  for ($j=0; $j < $result->num_rows; $j++) { 
                     $name = $result->fetch_array();
                     array_push($members, $name[0]);
                  }
                 
               }
               for ($i=0; $i < count($members); $i++) { 
                  # code...
                  $query = "SELECT employee FROM users WHERE user_ID = '$members[$i]'";
                  $result = queryMySQL($query);
                  $name = $result->fetch_array();
                  $members[$i] = $name[0];
               }
            }  
            else
            {
               $tmp = $involved;
               array_push($tmp, $send_to);
               array_push($tmp, $dept);
               
               for ($i=0; $i < count($tmp); $i++) { 
                  $query = "SELECT user_ID FROM departments WHERE department = '$tmp[$i]'";
                  $result = queryMySQL($query);
                  for ($j=0; $j < $result->num_rows; $j++) { 
                     $name = $result->fetch_array();
                     array_push($members, $name[0]);
                  }
               }
              
               for ($i=0; $i < count($members); $i++) { 
                  $query = "SELECT employee FROM users WHERE user_ID = '$members[$i]'";
                  $result = queryMySQL($query);
                  $name = $result->fetch_array();
                  $members[$i] = $name[0];
               }

            }
            mysqli_autocommit($c, false);
            $flag = false;
            global $c;
            $stmt = $c->prepare("INSERT INTO department_order VALUES(?,?,?)");
            $stmt->bind_param('ssi', $d, $o, $i);
            $i = "";
            $o = "PDL-$ddd-$id";
            $f = false;
            $f1 = false;
            $f2 = false;
            if(count($tmp) > 0)
            {
               for ($y=0; $y < count($tmp); $y++) { 
                 $d = $tmp[$y];
                 $ft = $stmt->execute();
                  
                 if(!$ft) {$f = true; $flag = true;}
               }
            }
            else
            {
               $d = $dept;
               $ft = $stmt->execute();
               if(!$ft) {$f = true; $flag = true;}
            }
           
            $stmt2 = $c->prepare("INSERT INTO order_details VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $stmt2->bind_param('sssisssssss', $oi,$de,$is,$st,$it,$p1,$p2,$p3,$p4,$p5,$p6);
            $oi = "PDL-$ddd-$id";
            $de = sanitizeInput($description);
            $is = sanitizeInput($user);
            $st = $stage;
            $it = sanitizeInput(date('Y-m-d H:i:s'));
            $p1 = $p2 = $p3 = $p4 = $p5 = $p6 = NULL;

            $ft = $stmt2->execute();
            if(!$ft) {$f1 = true; $flag = true;}
            // var_dump($stmt2);
            $stmt3 = $c->prepare("INSERT INTO notifications VALUES(?,?,?,?,?)");
            $stmt3->bind_param('issis', $ii, $dep, $no, $se, $ti);
            $ii = "";
            $no = "PDL-$ddd-$id was just created";
            $se = 0;
            $ti = date('Y-m-d H:i:s');
            $members = array_unique($members);
            for ($i=0; $i < count($members); $i++) { 
               $dep = $members[$i];
               $ft  = $stmt3->execute();
               if(!$ft) {$f2 = true; $flag = true;}
            }
         //   var_dump($members);
            

            if(!$flag)
            {
               mysqli_commit($c);
               $success = true;
            }
            else
            {
               mysqli_rollback($c);
                var_dump([$f, $f1, $f2]);
               $error = "Something Went Wrong!";
            }
         }
        else 
        {
          var_dump($description);
          var_dump($_POST['send_to']);
          var_dump($involved);
        }
     }
     function rE()
     {
        global $error;
        if($error)
        {
           return "<div class = 'alert alert-danger'>$error</div>";
        }
        else
        {
           return "";
        }
     }
     function validateDescription($field)
     {
         global $error;
        if(preg_match('[^a-zA-Z0-9-_]i',$field) || strlen($field) < 5) {$error .= "Invalid Entry for descriptions!"; return false;}
        else return $field;
     }
     function validateSendTO($field)
     {
      global $error;
        if(preg_match('[^a-zA-Z0-9]i', $field)) {$error .= "Send to Contains Invalid Charscters"; return false;}
        else if(preg_match('/-1/i', $field)) {$error .= "Send to Contains Invalid Characters"; return false;}
        else return $field;
     }
     function validateInvolved($field)
     {
      global $error;
        $e ="";
        if(count($field) > 0)
        {
           for($i = 0; $i < count($field); $i++)
           {
              if(preg_match('/[^\w-1]/', $field[$i]))
              {
                 $e .= "Involved PArties Contain Invalid Characters";
              }
           }
        }
       else $e = "";
        if($e) {$error .= "Involved Parties Contains Invalid Characters"; return false;}
        else return $field;
  
     }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Order</title>
      <script src = "js/OSC.js"></script>
      <link rel = "stylesheet" href = "bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/css/bootstrap.min.css">
      <link rel = "stylesheet" href = "css/login.css" >
      <link rel="stylesheet" href="css/dashboard.css">
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/cbox.css">
      <script>
         function validateForm(form)
         {
            console.log("Here");
            fail = validateDescription(form.description.value);
            fail += validateSendTo(form.send_to.value);
            fail += validateInvolved();
           
            if(fail == "")
            {
               console.log("Not The Issue!");
               return true;
            }
            else {alert(fail);return false;}
         }

         function validateDescription(field)
         {
            if(/[\w]/.test(field)) return "";
            else return "Field Only Requires a-z, A-Z, 0-9, _\n";
         }
         function validateSendTo(field)
         {
            if(/-1/.test(field)) return "Please Select a Valid Department\n";
            else return "";
         }
         function validateInvolved()
         {
            var checkbox = document.querySelector('input[name="involved[]"]:checked');
            if(checkbox != null)
            {
               for (let index = 0; index < checkbox.length; index++) {
                  if(checkbox[index] == form.send_to.value) return "Duplicate Entries for Involved Parties and Send To";
               }
               return "";
            }
            else return "";
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
                     <a class="nav-link active" href="order.php">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                     Order
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="account.php">
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
               <h1 class="h2">Create Order</h1>
               </div>
               <div id = "modal-holder">
              
               </div>
               <form method = "POST" onsubmit = "return validateForm(this)" action = ""> 
                  <div>
                     <?php echo($error == "")? "": "<div class = 'alert alert-danger'>".htmlspecialchars($error);"</div>"?>
                     <?php echo($success)? "<div class = 'alert alert-success'> Order Was Created Successfully </div>" : ""; ?>
                  </div>
                  <div class="form-group">
                     <label for="exampleFormControlTextarea1">Order Description</label>
                     <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name = "description" required></textarea>
                  </div>
                  <div class="form-group">
                     <label for="exampleFormControlSelect1">Send To</label>
                     <select class="form-control" id="exampleFormControlSelect1" name = "send_to">
                        <option value = "-1" >--SELECT DEPARTMENT--</option>
                        <!-- <option value = "IT">IT</option>
                        <option value = "SALES">SALES</option> -->
                        <option value = "ACCOUNTS">ACCOUNTS</option>
                        <option value = "SUPPLY CHAIN">SUPPLY CHAIN</option>
                     </select>
                  </div>
                  <div>
                     <label>Involved Parties</label>
                  </div>
                  <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="IT" name = "involved[]">
                  <label class="form-check-label" for="inlineCheckbox1">IT</label>
                  </div>
                  <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="ACCOUNTS" name = "involved[]">
                  <label class="form-check-label" for="inlineCheckbox2">ACCOUNTS</label>
                  </div>
                  <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="SUPPLY CHAIN" name = "involved[]">
                  <label class="form-check-label" for="inlineCheckbox3">SUPPLY CHAIN</label>
                  </div>
                  <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="SALES" name = "involved[]">
                  <label class="form-check-label" for="inlineCheckbox4">SALES</label>
                  </div>
                  
                  <div style="margin-top: 30px;">
                  <button type="submit" class="btn btn-success">Create</button>
                  <button type = "reset" class = "btn btn-danger">Reset</button>
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