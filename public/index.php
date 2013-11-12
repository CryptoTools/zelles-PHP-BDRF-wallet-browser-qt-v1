<?php
require"../coinapi_private/data.php";
//$getmobile = security($_GET['mobile']);
//if($getmobile=="no") {
//   setcookie("mobile","no",time() + (10 * 365 * 24 * 60 * 60));
//   header("Location: index.php");
//}
//if(isset($_COOKIE['mobile'])) {
//   $mobilecookie = security($_COOKIE['mobile']);
//} else {
//   $mobilecookie = '';
//}
$ismobile = false;
//if($mobilecookie!="no") {
//   $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
//   if(preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
//      $ismobile = true;
//   } else if(preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
//      $ismobile = true;
//   }
//}

$getaction = security($_POST['action']);
$logout = security($_GET['logout']);
if($logout=="logout") {
   session_destroy();
   setcookie("identa", '', time()-1000);
   setcookie("identa", '', time()-1000, '/');
   setcookie("identb", '', time()-1000);
   setcookie("identb", '', time()-1000, '/');
   header("Location: index.php");
}
if(isset($_SESSION['apiidentity'])) {
   $EMAIL_INDENT = security($_SESSION['apiidentity']);
   $Query = mysql_query("SELECT email FROM accounts WHERE email='$EMAIL_INDENT'");
   if(mysql_num_rows($Query) != 0) {
      header("Location: account.php");
   }
}
if($getaction=="login") {
   $login_email = security($_POST['email']);
   $login_password = security($_POST['password']);
   if($login_email) {
      if($login_password) {
         $login_password = substr($login_password, 0, 30);
         $login_password = md5($login_password);
         $Query = mysql_query("SELECT email FROM accounts WHERE email='$login_email'");
         if(mysql_num_rows($Query) == 1) {
            $Query = mysql_query("SELECT email, password, status, banned, pub_key, priv_key FROM accounts WHERE email='$login_email'");
            while($Row = mysql_fetch_assoc($Query)) {
               $login_db_email = $Row['email'];
               $login_db_password = $Row['password'];
               $login_db_status = $Row['status'];
               $login_db_banned = $Row['banned'];
               $login_db_pub_key = $Row['pub_key'];
               $login_db_priv_key = $Row['priv_key'];
               if($login_password==$login_db_password) {
                  if($login_db_status=="1") {
                     if($login_db_banned!="1") {
                        $_SESSION['apiidentity'] = $login_db_email;
                        setcookie("identa",$login_db_pub_key,time() + (10 * 365 * 24 * 60 * 60));
                        setcookie("identb",$login_db_priv_key,time() + (10 * 365 * 24 * 60 * 60));
                        header('Location: account.php');
                        $onloader = ' onload="alert(\'Logged in!\')"';
                     } else {
                        $onloader = ' onload="alert(\'That account has been banned.\')"';
                     }
                  } else {
                     $onloader = ' onload="alert(\'You have not activated your account\nusing the activation email.\')"';
                  }
               } else {
                  $onloader = ' onload="alert(\'Invalid password!\')"';
               }
            }
         } else {
            $onloader = ' onload="alert(\'Account does not exist!\')"';
         }
      } else {
         $onloader = ' onload="alert(\'No password was entered!\')"';
      }
   } else {
      $onloader = ' onload="alert(\'No email was entered!\')"';
   }
}
if($getaction=="register") {
   $register_email = security($_POST['email']);
   $register_password = security($_POST['password']);
   $register_conpassword = security($_POST['conpassword']);
   if($register_email) {
      if($register_password) {
         if($register_password==$register_conpassword) {
            $register_password = substr($register_password, 0, 30);
            $register_password = md5($register_password);
            $register_pub_key = pubkeygen();
            $register_priv_key = pubkeygen();
            $Query = mysql_query("SELECT email FROM accounts WHERE email='$register_email'");
            if(mysql_num_rows($Query) == 0) {
               $sql = mysql_query("INSERT INTO accounts (id,date,ip,email,password,status,banned,pub_key,priv_key) VALUES ('','$date','$ip','$register_email','$register_password','1','0','$register_pub_key','$register_priv_key')");
               $onloader = ' onload="alert(\'Account created! You can login now.\')"';
            } else {
               $onloader = ' onload="alert(\'There is already an account using that email!\')"';
            }
         } else {
            $onloader = ' onload="alert(\'Passwords do not match!\')"';
         }
      } else {
         $onloader = ' onload="alert(\'No password was entered!\')"';
      }
   } else {
      $onloader = ' onload="alert(\'No email was entered!\')"';
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
            <?php require'z_minimenuo.php'; ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <table style="width: 100%; font-size: 11px;">
               <tr>
                  <td valign="top" colspan="2" style="height: 30px;">
                     <b style="font-size: 11px;">BDRF.info Wallet System:</b>
                  </td>
               </tr><tr>
                  <td valign="top">
                     <form action="index.php" method="POST">
                     <input type="hidden" name="action" value="register">
                     <table style="width: 300px; font-size: 11px;">
                        <tr>
                           <td colspan="2" align="left" style="height: 30px; font-weight: bold;">Registration:</td>
                        </tr><tr>
                           <td align="right" style="height: 30px; padding-right: 15px;" nowrap>Email</td>
                           <td align="right" style="height: 30px;"><input type="text" name="email" placeholder="Email" style="height: 20px; width: 100%;"></td>
                        </tr><tr>
                           <td align="right" style="height: 30px; padding-right: 15px;" nowrap>Password</td>
                           <td align="right" style="height: 30px;"><input type="password" name="password" placeholder="Password" style="height: 20px; width: 100%;"></td>
                        </tr><tr>
                           <td align="right" style="height: 30px; padding-right: 15px;" nowrap>Repeat</td>
                           <td align="right" style="height: 30px;"><input type="password" name="conpassword" placeholder="Repeat Password" style="height: 20px; width: 100%;"></td>
                        </tr><tr>
                           <td colspan="2" align="right" style="height: 30px;"><input type="submit" name="submit" value="Register"></td>
                        </tr>
                     </table>
                     </form>
                  </td>
                  <td valign="top">
                     <form action="index.php" method="POST">
                     <input type="hidden" name="action" value="login">
                     <table style="width: 300px; font-size: 11px;">
                        <tr>
                           <td colspan="2" align="left" style="height: 30px; font-weight: bold;">Login:</td>
                        </tr><tr>
                           <td align="right" style="height: 30px; padding-right: 15px;" nowrap>Email</td>
                           <td align="right" style="height: 30px;"><input type="text" name="email" placeholder="Email" style="height: 20px; width: 100%;"></td>
                        </tr><tr>
                           <td align="right" style="height: 30px; padding-right: 15px;" nowrap>Password</td>
                           <td align="right" style="height: 30px;"><input type="password" name="password" placeholder="Password" style="height: 20px; width: 100%;"></td>
                        </tr><tr>
                           <td colspan="2" style="height: 30px;" align="right"><input type="submit" name="submit" value="Login"></td>
                        </tr>
                     </table>
                     </form>
                  </td>
               </tr><tr>
                  <td valign="top" colspan="2">
                     <b style="font-size: 11px;">Coins Supported:</b>
                     <ul style="font-size: 11px; margin-top: 10px; margin-bottom: 10px; padding-left: 20px;">
                        <li style="height: 20px;">BTB Bitbar</li>
                        <li style="height: 20px;">BTC Bitcoin</li>
                        <li style="height: 20px;">FTC Feathercoin</li>
                        <li style="height: 20px;">LTC Litecoin</li>
                        <li style="height: 20px;">MEC Megacoin</li>
                        <li style="height: 20px;">NAN Nanotoken</li>
                     </ul>
                     Perfect for using coins with games and services. Also featuring API connectivity for services.
                     <p style="margin-top: 10px; margin-bottom: 10px;">The coin client might charge a network fee. We do not charge any fees.<br>
                     You must keep a minimum of (0.01 BTB,BTC,FTC,LTC,MEC)(0.2 NAN) in your wallet when withdrawing for possible network fees.</p>
                     Support development by donating Bitcoin to: 1351W7LaB1zAWzbLyoSoDZvZYDDPYcAg7z
                  </td>
               </tr>
            </table>
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>