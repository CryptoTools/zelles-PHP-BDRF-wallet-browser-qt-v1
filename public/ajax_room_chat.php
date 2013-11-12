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
$room = security($_GET['room']);
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

$char_color = '0B3861';
$Query = mysql_query("SELECT id, email, username, message FROM privatechat WHERE room='$room' and status='viewable' ORDER BY id DESC LIMIT 40");
while($Row = mysql_fetch_assoc($Query)) {
   $db_chat_id = $Row['id'];
   $db_chat_email = $Row['email'];
   $db_chat_username = $Row['username'];
   $db_chat_message = $Row['message'];
   if($char_color=='0489B1'){ $char_color = '0B3861'; } else { $char_color = '0489B1'; }
   $db_chat_message = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target='_blank'>\\0</a>", $db_chat_message);
   preg_match_all('/(http|https):\/\/[^ ]+(\.gif|\.jpg|\.jpeg|\.png)/',$db_chat_message, $matches);
   if($udb_email=="TheAdminsEmail@address.com") {
      $newcomment = '<p id="chatcomment" style="font-size: 12px; margin-top: 6px;"><b style="color: #'.$char_color.';"><a href="ajax_chatroom_action.php?ban='.$db_chat_email.'" target="_blank" style="font-weight: bold; text-decoration: none; color: #000000;">[Ban]</a> <a href="ajax_chatroom_action.php?del='.$db_chat_id.'" target="_blank" style="font-weight: bold; text-decoration: none; color: #000000;">[X]</a> '.$db_chat_username.': </b>'.$db_chat_message.'</p>';
   } else {
      $newcomment = '<p id="chatcomment" style="font-size: 12px; margin-top: 6px;"><b style="color: #'.$char_color.';">'.$db_chat_username.': </b>'.$db_chat_message.'</p>';
   }
   if(isset($matches[0][0])) {
      if($matches[0][0]!="") {
         $newcomment .= '<center><a href="'.$matches[0][0].'" target="_blank"><img src="'.$matches[0][0].'" border="0" style="height: 70px;"></a></center>';
      }
   }
   $compilecomments = $newcomment.$compilecomments;
}
echo $compilecomments;
?>