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
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Home</title>
      <script src = "js/OSC.js"></script>
      <script src="js/jspdf.min.js"></script>
      <script src="js/html2canvas.js"></script>
      <link rel = "stylesheet" href = "bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/css/bootstrap.min.css">
      <link rel = "stylesheet" href = "css/login.css" >
      <link rel="stylesheet" href="css/dashboard.css">
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/cbox.css">
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
                  <div class="form-inline d-flex dropdown">
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
                  </div>
                                 
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
                     <a class="nav-link active" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                     Dashboard <span class="sr-only">(current)</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="order.php">
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
               <h1 class="h2">Dashboard</h1>
               <div id = "modal-holder">
              
               </div>
               
               <div class="btn-toolbar mb-2 mb-md-0">
               <a href="#" class="btn btn-success btn-lg disabled sa" role="button" aria-disabled="true" id = "single" onclick = "approve()"><i class="fa fa-file-o sai"></i></a>
               <a href="#" class="btn btn-success btn-lg disabled sa" role="button" aria-disabled="true" id = "multiple" onclick = "approve()"><i class="fa fa-copy sai"></i></a>
                  
               </div>
               </div>

       
               <div id="accordion">
                  <center>
                     <div> No Orders! </div>
                  </center>
               </div>
               <!-- chatbox--empty  will  make the shit dissapear-->
               <div class = "cbox--holder" id = "cbox">
              
               </div>
            </main>
         </div>
      </div>
        
    
      <script>window.jQuery || document.write('<script src="vendor/jquery-slim.min.js"><\/script>')</script><script src="bootstrap-4.3.1-dist/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
      <script>employee = "<?php echo htmlspecialchars($user);?>";department ="<?php echo htmlspecialchars($dept);?>";</script>
      <script src = "js/f.js"></script>
     
      <script>
         (function($) {
            $(document).ready(function() {
               var $chatbox = $('.chatbox'),
                     $chatboxTitle = $('.chatbox__title'),
                     $chatboxTitleClose = $('.chatbox__title__close'),
                     $chatboxCredentials = $('.chatbox__credentials');
               $chatboxTitle.on('click', function() {
                     $chatbox.eq(0).toggleClass('chatbox--tray');
               });
               $chatboxTitleClose.on('click', function(e) {
                     e.stopPropagation();
                     $chatbox.addClass('chatbox--closed');
               });
               $chatbox.on('transitionend', function() {
                     if ($chatbox.hasClass('chatbox--closed')) $chatbox.remove();
               });
               $chatboxCredentials.on('submit', function(e) {
                     e.preventDefault();
                     $chatbox.removeClass('chatbox--empty');
               });
            });
         })(jQuery);
         
      </script>
   </body>
</html>