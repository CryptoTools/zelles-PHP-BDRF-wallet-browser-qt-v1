<?php
require"../coinapi_private/data.php";
$getaction = security($_POST['action']);
if(!isset($_SESSION['apiidentity'])) {
   header("Location: index.php");
}
if(isset($_SESSION['apiidentity'])) {
   $EMAIL_INDENT = security($_SESSION['apiidentity']);
   $Query = mysql_query("SELECT email FROM accounts WHERE email='$EMAIL_INDENT'");
   if(mysql_num_rows($Query) == 0) {
      header("Location: index.php");
   }
}
$allowedin = '1';
$room = security($_POST['room']);
$Query = mysql_query("SELECT room FROM chatroom WHERE room='$room' and status='viewable'");
if(mysql_num_rows($Query) == 0) {
   header("Location: chatroomcreate.php");
} else {
   $Query = mysql_query("SELECT email FROM chatroom WHERE room='$room' and status='viewable'");
   while($Row = mysql_fetch_assoc($Query)) {
      $rdb_email = $Row['email'];
   }
   if($udb_email==$rdb_email) {
      $allowedin = '7';
   }
}
if($allowedin!='7'){
   header("Location: chatroomcreate.php");
}
$Query = mysql_query("SELECT allowed, status FROM chatroom WHERE room='$room' and status='viewable'");
while($Row = mysql_fetch_assoc($Query)) {
   $d_allowed = $Row['allowed'];
   $d_status = $Row['status'];
}
$d_allowed_array = unserialize($d_allowed);

$action = security($_POST['action']);
if($action=="delete") {
   $sql = mysql_query("UPDATE chatroom SET status='deleted' WHERE room='$room' and status='viewable'");
   header("Location: chatroomcreate.php");
}
if($action=="edit") {
   $updated_allowed = security($_POST['allowed']);
   $explded_allowed = explode(',', $updated_allowed);
   $seriald_allowed = serialize($explded_allowed);
   $sql = mysql_query("UPDATE chatroom SET allowed='$seriald_allowed' WHERE room='$room' and status='viewable'");
   header("Location: chatroomcreate.php");
}
?>
<html>
<head>
   <title>BDRF Wallet System</title>
   <link rel="icon" type="image/png" href="images/favicon.png">
   <link rel="stylesheet" type="text/css" href="style_default.css">
   <script type="text/javascript">
      function toggle_visibility(id) {
         var e = document.getElementById(id);
         if(e.style.display == 'block')
            e.style.display = 'none';
         else
            e.style.display = 'block';
      }
   </script>
</head>
<body<?php if(isset($onloader)) { echo $onloader; } ?>>
   <center>
   <table class="console">
      <tr>
         <td align="left" class="consoletitle">
            <table class="consoletitletable">
               <tr>
                  <td align="left" class="consoletitletd"></td>
                  <td align="center">BDRF Wallet System</td>
                  <td align="right" class="consoletitletd"><a href="index.php" style="text-decoration: none; color: #000000;">BDRF.info</a></td>
               </tr>
            </table>
         </td>
      </tr><tr>
         <td align="left" class="consoleminimenu">
            <?php require'z_minimenu.php'; ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <b style="font-size: 11px;">BDRF Private Chatrooms:</b>
            <div style="margin-top: 10px;"></div>
            <form action="chatroommanage.php" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="room" value="<?php echo $room; ?>">
            <table style="width: 100%; font-size: 12px;">
               <tr>
                  <td colspan="2"><b>Manage room:</b></td>
               </tr><tr>
                  <td align="right" valign="top" style="width: 160px;" nowrap><b>Allowed In:</b><br>Chat handles<br>seperated by<br>commas</td>
                  <td style="padding-left: 15px;">
                     <textarea name="allowed" style="width: 100%; height: 180px; border: 1px solid #d8d8d8;"><?php
                        $x = 0;
                        foreach($d_allowed_array as $p_allowed) {
                           $x++;
                           if($x==1) {
                              echo $p_allowed;
                           } else {
                              echo ','.$p_allowed;
                           }
                        }
                     ?></textarea>
                  </td>
               </tr><tr>
                  <td colspan="2" align="right"><input type="submit" name="submit" value="Update Room" style="padding: 2px;"></td>
               </tr>
            </table>
            </form>
            <div style="margin-top: 10px;"></div>
            <hr>
            <div style="margin-top: 10px;"></div>
            <form action="chatroommanage.php" method="POST">
            <table style="width: 100%; font-size: 12px;">
               <tr>
                  <td><b>Delete Room:</b></td>
               </tr><tr>
                  <td align="right">
                     <input type="hidden" name="room" value="<?php echo $room; ?>">
                     <input type="hidden" name="action" value="delete">
                     <input type="submit" name="submit" value="Delete Room" style="padding: 2px;">
                  </td>
               </tr>
            </table>
            </form>
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>