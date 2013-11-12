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
if(isset($_POST['newhandle'])) {
   $newhandle = security($_POST['newhandle']);
   if($newhandle) {
      $a = strtolower ($newhandle);
      if(strpos($a,'userhandle') !== false) {
         $onloader = ' onload="alert(\'Chat handle can not contain userhandle.\')"';
      } else {
         if(strpos($a,'admin') !== false) {
            $onloader = ' onload="alert(\'Chat handle can not contain admin.\')"';
         } else {
            if(strpos($a,'support') !== false) {
               $onloader = ' onload="alert(\'Chat handle can not contain support.\')"';
            } else {
               $strlength = strlen($newhandle);
               if($strlength<=25) {
                  if($strlength>=3) {
                     $Query = mysql_query("SELECT handle FROM chathandle WHERE handle='$newhandle'");
                     if(mysql_num_rows($Query) == 0) {
                        $sql = mysql_query("UPDATE chathandle SET handle='$newhandle' WHERE email='$udb_email'");
                        $onloader = ' onload="alert(\'New chat handle set.\')"';
                     } else {
                        $onloader = ' onload="alert(\'That chat handle is already taken.\')"';
                     }
                  } else {
                     $onloader = ' onload="alert(\'Chat handle must be between 3 and 25 digits.\')"';
                  }
               } else {
                  $onloader = ' onload="alert(\'Chat handle must be between 3 and 25 digits.\')"';
               }
            }
         }
      }
   } else {
      $onloader = ' onload="alert(\'No chat handle was entered.\')"';
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
            <form action="settings_handle.php" method="POST">
            <table style="width: 100%; height: 80px; font-size: 12px;">
               <tr>
                  <td><b>Set new chat handle:</b></td>
               </tr><tr>
                  <td><input type="text" name="newhandle" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;"></td>
               </tr><tr>
                  <td align="right"><input type="submit" name="submit" value="Change Handle" style="padding: 2px;"></td>
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