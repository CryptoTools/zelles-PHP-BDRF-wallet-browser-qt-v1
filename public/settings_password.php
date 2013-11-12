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
if(isset($_POST['newpassword'])) {
   $oldpassword = security($_POST['oldpassword']);
   $newpassword = security($_POST['newpassword']);
   $conpassword = security($_POST['conpassword']);
   $oldpassword = substr($oldpassword, 0, 30);
   $newpassword = substr($newpassword, 0, 30);
   $conpassword = substr($conpassword, 0, 30);
   $enc_oldpassword = md5($oldpassword);
   $enc_newpassword = md5($newpassword);
   $enc_conpassword = md5($conpassword);
   if($newpassword) {
      if($oldpassword) {
         if($enc_newpassword==$enc_conpassword) {
            $Query = mysql_query("SELECT password FROM accounts WHERE email='$udb_email'");
            while($Row = mysql_fetch_assoc($Query)) {
               $account_db_password = $Row['password'];
            }
            if($account_db_password==$enc_oldpassword) {
               $sql = mysql_query("UPDATE accounts SET password='$enc_newpassword' WHERE email='$udb_email'");
               $onloader = ' onload="alert(\'Password has been changed.\')"';
            } else {
               $onloader = ' onload="alert(\'Old password did not match your accounts.\')"';
            }
         } else {
            $onloader = ' onload="alert(\'New passwords were not repeated correctly.\')"';
         }
      } else {
         $onloader = ' onload="alert(\'The old password was not entered.\')"';
      }
   } else {
      $onloader = ' onload="alert(\'The new password was now entered.\')"';
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
            <b style="font-size: 11px;">BDRF System Settings:</b>
            <div style="margin-top: 10px;"></div>
            <form action="settings_password.php" method="POST">
            <table style="width: 100%; height: 80px; font-size: 12px;">
               <tr>
                  <td colspan="2"><b>Set new password:</b></td>
               </tr><tr>
                  <td style="width: 120px;" nowrap><b>Old password:</b></td>
                  <td style="padding-left: 15px;"><input type="password" name="oldpassword" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td nowrap><b>New password:</b></td>
                  <td style="padding-left: 15px;"><input type="password" name="newpassword" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td nowrap><b>Reapeat password:</b></td>
                  <td style="padding-left: 15px;"><input type="password" name="conpassword" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td colspan="2" align="right"><input type="submit" name="submit" value="Change Password" style="padding: 2px;"></td>
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