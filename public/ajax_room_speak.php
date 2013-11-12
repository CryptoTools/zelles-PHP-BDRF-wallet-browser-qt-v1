<?php
require"../coinapi_private/data.php";
$getaction = security($_POST['action']);
if(!isset($_SESSION['apiidentity'])) {
   die("n/a");
}
if(isset($_SESSION['apiidentity'])) {
   $EMAIL_INDENT = security($_SESSION['apiidentity']);
   $Query = mysql_query("SELECT email FROM accounts WHERE email='$EMAIL_INDENT'");
   if(mysql_num_rows($Query) == 0) {
      die("n/a");
   }
}
$allowedin = '1';
$room = security($_POST['room']);
$Query = mysql_query("SELECT room FROM chatroom WHERE room='$room' and status='viewable'");
if(mysql_num_rows($Query) == 0) {
   die('That chatroom does not exist.');
   exit;
} else {
   $Query = mysql_query("SELECT email, allowed FROM chatroom WHERE room='$room' and status='viewable'");
   while($Row = mysql_fetch_assoc($Query)) {
      $rdb_email = $Row['email'];
      $rdb_allowed = $Row['allowed'];
      $usrdb_allowed = unserialize($rdb_allowed);
   }
   if($udb_email=="TheAdminsEmail@address.com") {
      $allowedin = '7';
   }
   if($udb_email==$rdb_email) {
      $allowedin = '7';
   } else {
      foreach($usrdb_allowed as $key => $value) {
         if($udb_email==$value) {
            $allowedin = '7';
         }
      }
   }
}
if($allowedin!='7'){
   die('The owner of this room has not granted you access.');
   exit;
}
if(isset($_POST['speak'])) {
   $speak = security($_POST['speak']);
   if($room!="") {
      if($speak!="") {
         if($udb_chathandle) {
            $sql = mysql_query("INSERT INTO privatechat (id,date,ip,email,username,room,message,status) VALUES ('','$date','$ip','$udb_email','$udb_chathandle','$room','$speak','viewable')");
         }
      }
   }
}
?>