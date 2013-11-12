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
$char_color = '0B3861';

$inDB = mysql_query("SELECT * FROM online WHERE ip='$ip'");
if(!mysql_num_rows($inDB)) {
   mysql_query("INSERT INTO online (id,ip,email,username,user,guest) VALUES('','$ip','$udb_email','$udb_chathandle','2','2')");
} else {
   if($udb_email) {
      mysql_query("UPDATE online SET email='$udb_email' WHERE ip='$ip'");
      mysql_query("UPDATE online SET username='$udb_chathandle' WHERE ip='$ip'");
      mysql_query("UPDATE online SET user='1' WHERE ip='$ip'");
      mysql_query("UPDATE online SET guest='2' WHERE ip='$ip'");
   } else {
      mysql_query("UPDATE online SET user='2' WHERE ip='$ip'");
      mysql_query("UPDATE online SET guest='1' WHERE ip='$ip'");
   }
   mysql_query("UPDATE online SET dt=NOW() WHERE ip='$ip'");
}
// mysql_query("DELETE FROM tz_who_is_online WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
mysql_query("UPDATE online SET user='2' WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
mysql_query("UPDATE online SET guest='2' WHERE dt<SUBTIME(NOW(),'0 0:10:0')");

$Query = mysql_query("SELECT id, email, username, message FROM chat WHERE status='viewable' ORDER BY id DESC LIMIT 40");
while($Row = mysql_fetch_assoc($Query)) {
   $db_chat_id = $Row['id'];
   $db_chat_email = $Row['email'];
   $db_chat_username = $Row['username'];
   $db_chat_message = $Row['message'];
   if($char_color=='0489B1'){ $char_color = '0B3861'; } else { $char_color = '0489B1'; }
   $db_chat_message = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target='_blank'>\\0</a>", $db_chat_message);
   preg_match_all('/(http|https):\/\/[^ ]+(\.gif|\.jpg|\.jpeg|\.png)/',$db_chat_message, $matches);
   if($udb_email=="TheAdminsEmail@address.com") {
      $newcomment = '<p id="chatcomment" style="font-size: 12px; margin-top: 6px;"><b style="color: #'.$char_color.';"><a href="ajax_chat_action.php?ban='.$db_chat_email.'" target="_blank" style="font-weight: bold; text-decoration: none; color: #000000;">[Ban]</a> <a href="ajax_chat_action.php?del='.$db_chat_id.'" target="_blank" style="font-weight: bold; text-decoration: none; color: #000000;">[X]</a> '.$db_chat_username.': </b>'.$db_chat_message.'</p>';
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