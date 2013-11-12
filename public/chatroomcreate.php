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
if(isset($_POST['createroom'])) {
   $cr_createroom = security($_POST['createroom']);
   $cr_allowed = security($_POST['allowed']);
   if($cr_createroom) {
      if($cr_allowed) {
         $Query = mysql_query("SELECT room FROM chatroom WHERE room='$cr_createroom' and status='viewable'");
         if(mysql_num_rows($Query) == 0) {
            $cr_allowed_array = explode(',', $cr_allowed);
            $actual_allowed = serialize($cr_allowed_array);
            $sql = mysql_query("INSERT INTO chatroom (id,date,ip,email,username,room,allowed,status) VALUES ('','$date','$ip','$udb_email','$udb_chathandle','$cr_createroom','$actual_allowed','viewable')");
            $onloader = ' onload="alert(\'Room created. You can now join and use it.\')"';
         } else {
            $onloader = ' onload="alert(\'A room already exist with that name.\')"';
         }
      } else {
         $onloader = ' onload="alert(\'No one was added to access the room.\')"';
      }
   } else {
      $onloader = ' onload="alert(\'No room name was entered.\')"';
   }
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
            <form action="chatroom.php" method="GET">
            <table style="width: 100%; font-size: 12px;">
               <tr>
                  <td colspan="2"><b>Join a room:</b></td>
               </tr><tr>
                  <td align="right" style="width: 120px;" nowrap><b>Room Name:</b></td>
                  <td style="padding-left: 15px;"><input type="text" name="room" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td colspan="2" align="right"><input type="submit" name="submit" value="Join Room" style="padding: 2px;"></td>
               </tr>
            </table>
            </form>
            <div style="margin-top: 10px;"></div>
            <hr>
            <div style="margin-top: 10px;"></div>
            <form action="chatroommanage.php" method="POST">
            <table style="width: 100%; font-size: 12px;">
               <tr>
                  <td colspan="2"><b>Manage your rooms:</b></td>
               </tr><tr>
                  <td align="right" style="width: 120px;" nowrap><b>Room Name:</b></td>
                  <td style="padding-left: 15px;"><input type="text" name="room" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td colspan="2" align="right"><input type="submit" name="submit" value="Manage" style="padding: 2px;"></td>
               </tr>
            </table>
            </form>
            <div style="margin-top: 10px;"></div>
            <hr>
            <div style="margin-top: 10px;"></div>
            <form action="chatroomcreate.php" method="POST">
            <table style="width: 100%; font-size: 12px;">
               <tr>
                  <td colspan="2"><b>Create a room:</b></td>
               </tr><tr>
                  <td align="right" style="width: 120px;" nowrap><b>Room Name:</b></td>
                  <td style="padding-left: 15px;"><input type="text" name="createroom" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td align="right" valign="top" nowrap><b>Allowed In:</b><br>Chat handles<br>seperated by<br>commas</td>
                  <td style="padding-left: 15px;">
                     <textarea name="allowed" style="width: 100%; height: 180px; border: 1px solid #d8d8d8;"></textarea>
                  </td>
               </tr><tr>
                  <td colspan="2" align="right"><input type="submit" name="submit" value="Create Room" style="padding: 2px;"></td>
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