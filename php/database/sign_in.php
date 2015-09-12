<?php
  require_once ("mysql_connect.php");


  connect();
  $username=strtolower($_POST['username']);  
  $passwordencryped = $_POST['password'];

  $res = checkusernamepassword($username,$passwordencryped);  
  echo $res;

  function checkusernamepassword($username,$password) {
    session_start();
    $sql = "select * from user where username = '$username' and password = '$password'";
    $result = mysql_query($sql);
    if(mysql_num_rows($result) == 1) {         
        while ($row = mysql_fetch_array($result)) {
            $_SESSION['language'] = $row["language"];
            $_SESSION['email'] = $row["email"];
        } 
        $_SESSION['username'] = $username; 
      return "success";  
    } else {
      return "failed";  
    }
  }

?>