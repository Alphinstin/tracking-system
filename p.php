<?php
    session_start();
    require 'f.php';

//
    if(isset($_POST['cid'])) gC($_POST['cid']);
    if(isset($_POST['l'])) gO($_SESSION['dept'],$_POST['l']);
    if(isset($_POST['messages'])) iM($_POST['messages']);
    if(isset($_POST['id'])) gN($_SESSION['user']);
    if(isset($_POST['unsee'])) uN($_POST['unsee']);
    if(isset($_POST['s'])) sO($_POST['s']);
    if(isset($_POST['approve'])) ae($_POST['approve']);
    if(isset($_POST['rep'])) gC($_POST['rep']);
    function gC($cid)
    {
        $cidi = preg_replace('/-/i','_',strtolower($cid));
        $chats = array();
        chatConnection(); //Establish Chat Connection 
        global $cc;
        $u = sanitizeInput2($cc, $_SESSION['user']);
        createChatTable($cidi,chat_table);
        $query = "SELECT * FROM $cidi ";
        $result = queryChatTable($query);
        $line = "line";
        $rows = $result->num_rows;
        for ($i=0; $i < $rows; $i++) { 
            $row = $result->fetch_array();
            $t = new DateTime($row['date']);
            $c = new DateTime();
            $diff = $c->diff($t);
            ($diff->s > 0)? $row['date'] = ($diff->s)."s" : "";
            ($diff->i > 0)? $row['date'] = ($diff->i)."m" : "";
            ($diff->h > 0)? $row['date'] = ($diff->h)."h" : "";
            ($diff->d > 0)? $row['date'] = ($diff->d)."d" : "";
            ($diff->m > 0)? $row['date'] = ($diff->m)."mth" : "";
            ($diff->y > 0)? $row['date'] = ($diff->y)."y" : "";
            $row['chat_id'] = $cid;
            $chats[$i] = $row;
        }
        unset ($_POST['cid']);
        closeConn($cc);
        echo json_encode($chats);
    }
    function gO($dept, $limit)
    {
        $dd = array();
        $q = "SELECT order_details.* FROM order_details, department_order WHERE department_order.department = '$dept' && department_order.order_id = order_details.order_id ORDER BY id DESC LIMIT $limit";
        global $c;
        $r = queryMysql($q);
        unset($_POST['l']);
        if($r->num_rows)
        {
            for($i = 0; $i < $r->num_rows; $i++)
            {
                $l = $r->fetch_array();
                $row = $l['issue_date'];
                $t = new DateTime($row);
                $c = new DateTime();
                $diff = $c->diff($t);
                ($diff->s > 0)? $l['date'] = ($diff->s)."s" : "";
                ($diff->i > 0)? $l['date'] = ($diff->i)."m" : "";
                ($diff->h > 0)? $l['date'] = ($diff->h)."h" : "";
                ($diff->d > 0)? $l['date'] = ($diff->d)."d" : "";
                ($diff->m > 0)? $l['date'] = ($diff->m)."mth" : "";
                ($diff->y > 0)? $l['date'] = ($diff->y)."y" : "";
                
                $dd["$i"] = $l;
            }
        }
        else{ echo "No Orders!";return true;}
        echo json_encode($dd);
    }
    function iM($m)
    {
     
            $m =json_decode($_POST['messages']);
            $er = "";
            $ed = "";

            foreach($m as $key => $value) {
                $table = preg_replace('/-/i','_',$value->chat_id);
                createChatTable($table,chat_table);
                chatConnection();
                global $cc;
                $stmt = $cc->prepare("INSERT INTO $table VALUES(?,?,?,?)");
                $stmt->bind_param('isss', $id, $e, $p, $t);
                $id = '';
                $e  = sanitizeInput2($cc,$value->employee);
                $p  = sanitizeInput2($cc,$value->post);
                $t  = sanitizeInput2($cc,date("Y-m-d H:i:s"));
                $stmt->execute();
                if($stmt->errno) $er = $stmt->errno;
                if(!$er) $ed .= "1";
                else $ed .= "0";
            }
            if($ed){closeConn($cc);echo $ed;}

       
    }
    function gN($dept)
    {
        $dd = array();
        $v = true;
        $q = "SELECT * FROM notifications WHERE department = '$dept' ORDER BY id DESC";
        global $c;
        $r = queryMysql($q);
        unset($_POST['l']);
        if($r->num_rows)
        {
            for($i = 0; $i < $r->num_rows; $i++)
            {
                $l = $r->fetch_array();
                $t = new DateTime($l['time']);
                $c = new DateTime();
                $diff = $c->diff($t);
                ($diff->s > 0)? $l['time'] = ($diff->s)."s" : "";
                ($diff->i > 0)? $l['time'] = ($diff->i)."m" : "";
                ($diff->h > 0)? $l['time'] = ($diff->h)."h" : "";
                ($diff->d > 0)? $l['time'] = ($diff->d)."d" : "";
                ($diff->m > 0)? $l['time'] = ($diff->m)."mth" : "";
                ($diff->y > 0)? $l['time'] = ($diff->y)."y" : "";
                $dd["$i"] = $l;
            }
            $v = false;
            echo json_encode($dd);
        }

        if($v) echo "No Notifications";
        
    }

    function uN($noti)
    {   
        $er = "";
        $ed = "";
        $noti = json_decode($noti);
        global $c;
        
            # code...
            $stmt = $c->prepare("UPDATE notifications SET seen_value = '1' WHERE id = ?");
            $stmt->bind_param('s',$id);
            foreach ($noti as $key => $value) {
                $id = $key;
                $stmt->execute();
                if($stmt->errno) $er = $stmt->errno;
                if(!$er) $ed .= "1";
                else $ed .= "0";
            }
            $stmt->close();
       echo $ed;
    }

    function sO($query)
    {
        $query = sanitizeInput($query); // Sanitize Input
        global $c;
        //Prepare A Statement
        $r = [];
        $stmt = "SELECT * FROM order_details WHERE order_id LIKE '%$query%' OR description LIKE '%$query%' ORDER BY order_details.order_id DESC";
        $result = queryMySQL($stmt);
        for ($i=0; $i < $result->num_rows; $i++) { 
            $r[$i] = $result->fetch_array();
        }
        echo json_encode($r);
    }

    function ae($query)
    {
        $query = (array)json_decode($query);
        $l = "";
        $m = "";

        if(count((array)$query) > 1)
        {
            $query = array_reverse($query);
            var_dump($query);
           foreach ($query as $key => $value) {
               # code...
               $o = gU($value);
               if($o) $m .=1;
               else $m .=0;
           }
               
            
            if(preg_match('/0/i',$m)) $l = false;
            else $l = true;
        }
        else
        {
            $n = "";
            foreach($query as $key => $value)
            {
               $n = $value;
            }
            $l = gU($n);            
        }
        if($l) echo "Successful!";
        else echo "Unsuccessful";
    }

    function gU($order)
    {
        //GEt Departments
        //Use Departments to get UserID
        // use userID to get users;

        global $c;
        $f = $f1 = $f2 = $f3 = $f4 = $f5 = true;
        $stage = 2;
        if($_SESSION['dept'] == "ACCOUNTS") $stage = 3;
        $f = $f1 = $f2 = $f3 = $f4 = $f5 = true;
       
        $temp_dep = array();
        $flag = false;
        mysqli_autocommit($c,false);

        $stmt  = $c->prepare("SELECT department FROM department_order WHERE order_id = ?");
        $stmt->bind_param('s',$id);
        $id = $order;
        $ft = $stmt->execute();
        if(!$ft){$flag = true;$f = false;}
        $result = $stmt->get_result();
        while($line = $result->fetch_array())
        {
            array_push($temp_dep, $line[0]);
        }
        $temp_dep = array_unique($temp_dep);
        if(in_array("ACCOUNTS", $temp_dep))
        {

        }
        else
        {
            $stmt5 = $c->prepare('INSERT INTO department_order VALUES(?,?,?)');
            $stmt5->bind_param('ssi',$ddd, $orde, $id);
            $ddd = "ACCOUNTS";
            $orde = $order;
            $id = "";
            $f5 = $stmt5->execute();
            array_push($temp_dep, "ACCOUNTS");
        }
      
         //Use Departments to get UserID
        $temp_id = array();
        $stmt2 = $c->prepare("SELECT user_ID FROM departments WHERE department = ?");
        $stmt2->bind_param('s',$d);
        for ($i=0; $i < count($temp_dep); $i++) { 
            $d = $temp_dep[$i];
            $ft = $stmt2->execute();
            if(!$ft){$flag = true;$f1 = false;}
            $result = $stmt2->get_result();
            while($line = $result->fetch_array())
            {
                array_push($temp_id, $line[0]);
            }
        }
        unset($temp_dep);
       
        $members = array();
        $stmt2 = $c->prepare("SELECT employee FROM users WHERE user_ID = ?");
        $stmt2->bind_param('s',$do);
        for ($i=0; $i < count($temp_id); $i++) { 
            $do = $temp_id[$i];
            $ft = $stmt2->execute();
            if(!$ft){$flag = true;$f2 = false;}
            $result = $stmt2->get_result();
            while($line = $result->fetch_array())
            {
                array_push($members, $line[0]);
            }
        }
        unset($temp_id);
        
        $stage = 2;
        if($_SESSION['dept'] == "ACCOUNTS") {$stage = 3;}
        $stmt3 = $c->prepare("UPDATE order_details SET stage = ? WHERE order_id = ?");
        $stmt3->bind_param('is', $st, $f);
        $st = $stage;
        $f = $order;
        $ft = $stmt3->execute();
        if(!$ft){$flag = true;$f3 = false;}
       
        $stmt4 = $c->prepare("INSERT INTO notifications VALUES(?,?,?,?,?)");
            $stmt4->bind_param('issis', $ii, $dep, $no, $se, $ti);
            $ii = "";
            if($stage == 2) $no = "$order was just approved";
            else if($stage == 3) $no = "Funds released for $order";
            $se = 0;
            $ti = date('Y-m-d H:i:s');
            $members = array_unique($members);
            for ($i=0; $i < count($members); $i++) { 
               $dep = $members[$i];
               $ft  = $stmt4->execute();
               if(!$ft) {$flag = true;$f4 = false;}
            }
       
        
        unset($members);

        if(!$flag)
        {
            mysqli_commit($c);
            return !$flag;
        }
        else
        {
            mysqli_rollback($c);
            return $flag;
        }
        
    }

    
?>