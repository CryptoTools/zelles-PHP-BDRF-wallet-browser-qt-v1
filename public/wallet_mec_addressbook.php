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
$pz_coin_name = 'Megacoin';
$pz_coin_initl = 'mec';
$pz_coin_initu = 'M&#931;C';
$pz_coin_api = 'Megacoin';
?>
<html>
<head>
   <link rel="icon" type="image/png" href="images/favicon.png">
   <title>BDRF Coin Wallets - <?php echo $pz_coin_name.' - Wallet'; ?></title>
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
<body>  
   <center>
   <table class="console">
      <tr>
         <td align="left" class="consoletitle">
            <table class="consoletitletable">
               <tr>
                  <td align="left" class="consoletitletd"><img src="images/<?php echo $pz_coin_initl; ?>.png" style="width: 18px;"></td>
                  <td align="center"><?php echo $pz_coin_name.' - Wallet'; ?></td>
                  <td align="right" class="consoletitletd"><a href="index.php" style="text-decoration: none; color: #000000;">BDRF.info</a></td>
               </tr>
            </table>
         </td>
      </tr><tr>
         <td align="left" class="consoleminimenu">
            <?php require'z_minimenu.php'; ?>
         </td>
      </tr><tr>
         <td align="left" class="consolemenu">
            <?php
            $menu_page = "addressbook";
            require'z_menu.php';
            ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <span style="font-size: 11px; font-weight: bold;">Under development</span>
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>