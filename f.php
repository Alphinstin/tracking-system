<?php
    ob_start();
    $hn = "localhost";
    $un = "root";
    $pw = "";
    $db = "709";
    $ldb = "709_logs";
    $cdb = "709_chats";
    $c = new mysqli($hn, $un, $pw, $db);
    if(!$c) die("Fatal Error");
    $cl = ""; 
    logConnection();
    $cc = "";
   chatConnection();
     
  
    // For PRooduction
     define("logtable", "id INT AUTO_INCREMENT NOT NULL, log VARCHAR(255) NOT NULL, time TIME NOT NULL, PRIMARY KEY(id)",false);



    // PLease write structure for chat table 
    define("chat_table","id INT NOT NULL AUTO_INCREMENT, employee VARCHAR(60) NOT NULL, post VARCHAR(2555) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)", false);
    createChatTable("PDL_IT_1",chat_table);




    // define("usertable", "user_ID VARCHAR(255) NOT NULL, employee VARCHAR(60) NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(10) NOT NULL, PRIMARY KEY(user_ID)",false);
     createLogTable(logtable);
    
    // createTable("users",usertable);
    // define("departments", "department VARCHAR(60) NOT NULL, members VARCHAR(100) NOT NULL, user_ID VARCHAR(255) NOT NULL, PRIMARY KEY(members), UNIQUE(user_ID)",false);
    // createTable("departments", departments);
    function queryMySQL($query)
    {
        global $c;
        $result = $c->query($query);
        if(!$result) die("Fatal Error!".$c->error);
        return $result;
    }
    function sanitizeInput($field)
    {
        global $c;
        $field = strip_tags($field);
        $field = htmlentities($field);
        if(get_magic_quotes_gpc()) $field = stripslashes($field);
        return $c->real_escape_string($field);
    }
    function sanitizeInput2($c,$field)
    {
        $field = strip_tags($field);
        $field = htmlentities($field);
        if(get_magic_quotes_gpc()) $field = stripslashes($field);
        return $c->real_escape_string($field);
    }
    function createTable($name, $query)
    {
        $fquery = "CREATE TABLE IF NOT EXISTS $name($query)Engine=InnoDB";
        $result = queryMySQL($fquery);
    }
    function queryLogTable($query)
    {
        global $cl;
        $result = $cl->query($query);
        if(!$result) die("Fatal Error!".$cl->error);
        return $result;
    }
    function queryChatTable($query)
    {
        global $cc;
        $result = $cc->query($query);
        if(!$result) die("Fatal Error!".$cc->error);
        return $result;
    }
    function logConnection()
    {
        global $hn; global $un; global $pw; global $ldb; global $cl;
        $cl = new mysqli($hn, $un, $pw, $ldb);
        
        if(!$cl)
        {
            // Callback to Server goes Here!
            die("Fatal Error!");
        }
    }
    function chatConnection()
    {
        global $hn; global $un; global $pw; global $cdb; global $cc;
        $cc = new mysqli($hn, $un, $pw, $cdb);
        
        if(!$cc)
        {
            // Callback to Server goes Here!
            die("Fatal Error!");
        }
    }
    function createChatTable($name, $query)
    {

        $fquery = "CREATE TABLE IF NOT EXISTS $name($query)Engine=InnoDB";
        $result = queryChatTable($fquery);
    }
    function createLogTable($query)
    {
       // logConnection();
        $fquery = "CREATE TABLE IF NOT EXISTS ".date("DdY")."($query)Engine=InnoDB";
        $result = queryLogTable($fquery);
    }
    function destroy_data_and_session()
    {
        $_SESSION = array(); // Empty the session array
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
        return true;
    }
    function closeConn($c)
    {
        $c->close();
        
    }
    function insertDb($c, $val)
    {
        $result =  $c->query($val);
        if(!$result) die("Fatal Error!");
        return $result;
    }
    function insertLog($val)
    {   
        createLogTable(logtable);
        switch ($val) {
            case '0':
               logConnection();
               global $cl;
               mysqli_autocommit($cl,false);
               $flag = true;
               $date = date("DdY");
               $stmt1 = $cl->prepare("INSERT INTO $date VALUES(?,?,?)");
               $stmt1->bind_param('sss', $id, $log, $time);
               $id = '';
               $log = sanitizeInput2($cl, $_SESSION['user']).' just logged out';
               $time = date("H:i:s");
              
               $ft = $stmt1->execute();
               if(!$ft) $flag = false;

               global $c;
               mysqli_autocommit($c,false);
               $stmt2 = $c->prepare("UPDATE users SET status = ? WHERE employee = ?");
               $stmt2->bind_param('ss', $status, $employee);
               $status = 'LOGGED OUT';
               $employee = sanitizeInput($_SESSION['user']);
               $st = $stmt2->execute();
               if(!$st) $flag = false;

               if($flag) 
               {
                   mysqli_commit($c);
                   mysqli_commit($cl);
                   return true;
               }
               else
               {
                   mysqli_rollback($c);
                   mysqli_rollback($cl);
                   return false;
               }
               
            break;
            case '1':
                logConnection();
                global $cl;
                mysqli_autocommit($cl,false);
                $flag = true;
                $date = date("DdY");
                $stmt1 = $cl->prepare("INSERT INTO $date VALUES(?,?,?)");
                $stmt1->bind_param('sss', $id, $log, $time);
                $id = '';
                $log = sanitizeInput2($cl, $_SESSION['user']).' just logged in';
                $time = date("H:i:s");
            
                $ft = $stmt1->execute();
                if(!$ft) $flag = false;

                global $c;
                mysqli_autocommit($c,false);
                $stmt2 = $c->prepare("UPDATE users SET status = ? WHERE employee = ?");
                $stmt2->bind_param('ss', $status, $employee);
                $status = 'LOGGED IN';
                $employee = sanitizeInput($_SESSION['user']);
                $st = $stmt2->execute();
                if(!$st) $flag = false;

                if($flag) 
                {
                    mysqli_commit($c);
                    mysqli_commit($cl);
                    return true;
                }
                else
                {
                    mysqli_rollback($c);
                    mysqli_rollback($cl);
                    return false;
                }
            break;
            default:
                # code...
                break;
        }
    }
    function get_end_time()
    {
        $end_time = "";
        if(isset($_SESSION['in_time']))
        {
            $interval = new DateInterval("PT30M");
            $start = new DateTime($_SESSION['in_time']);
            $end_time = $start->add($interval);
            return $end_time;
        }
        else
        {
            $_SESSION['in_time'] = date("Y-m-d H:i:s");
            $interval = new DateInterval("PT30M");
            $start = new DateTime($_SESSION['in_time']);
            $end_time = $start->add($interval);
            return $end_time;
        }
    }
    function autologOut()
    {
        $et = get_end_time();
       // Get Time Difference
       $ct = new DateTime(date("Y-m-d H:i:s"));
       $diff = $et->diff($ct);
       $m = $diff->i;
      if($ct >= $et) 
      {
        if(insertLog(0)) 
        {
            header("Location:login.php");
            destroy_data_and_session();
        }
      }      
    }
    function gD($id)
    {
        $q = "SELECT department FROM departments WHERE user_ID = $id";
        $r = queryMySQL($q);
        $w = $r->fetch_array();
        var_dump($w);
        return $w['department'];
    }
    ob_end_flush();
?>
