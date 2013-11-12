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
$pz_coin_name = 'Feathercoin';
$pz_coin_initl = 'ftc';
$pz_coin_initu = 'FTC';
$pz_coin_api = 'Feathercoin';
if(isset($_POST['newaddress'])) {
   $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getnewaddress&acnt='.$udb_email.'&sid=BDRFM';
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $json_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $json_feed = curl_exec($ch);
   curl_close($ch);
   $address_array = json_decode($json_feed, true);
   $address = $address_array['address'];
   $onloader = ' onload="alert(\'Created a new '.$pz_coin_name.' address successfully.\n'.$address.'\')"';
}
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
<body<?php if(isset($onloader)) { echo $onloader; } ?>>
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
            $menu_page = "receive";
            require'z_menu.php';
            ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <div class="addressbookdiv">
            <table class="addressbooktable">
               <tr>
                  <td align="center" class="addressbooktdt">Label</td>
                  <td align="center" class="addressbooktdt" id="rightside">Address</td>
                  <td class="addressbooktdblock"></td>
               </tr>
            </table>
            <div class="addressbookscroll">
            <table class="addressbooktable">
               <?php
               $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getaccountaddresses&acnt='.$udb_email.'&sid=BDRFM';
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $json_url);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
               $json_feed = curl_exec($ch);
               curl_close($ch);
               $addressbook_array = json_decode($json_feed, true);
               $addresses = $addressbook_array['addresses'];
               if($addresses=="") {
                  $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getnewaddress&acnt='.$udb_email.'&sid=BDRFM';
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $json_url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  $json_feed = curl_exec($ch);
                  $addressbook_array = json_decode($json_feed, true);
                  $address = $addressbook_array['address'];
                  curl_close($ch);
                  echo '<tr>
                           <td class="addressbooktda"></td>
                           <td class="addressbooktda" id="rightside">'.$address.'</td>
                        </tr>';
               } else {
                  $bgcol = "1";
                  $useclass = 'addressbooktdb';
                  foreach($addresses as $address) {
                     if($bgcol=="1") { $bgcol = "2"; $useclass = 'addressbooktda'; } else { $bgcol = "1"; $useclass = 'addressbooktdb'; }
                     echo '<tr>
                              <td class="'.$useclass.'"></td>
                              <td class="'.$useclass.'" id="rightside">'.$address.'</td>
                           </tr>';
                  }
               }
               ?>
            </table>
            </div>
            </div>
            <form action="wallet_<?php echo $pz_coin_initl; ?>_receive.php" method="POST" style="margin-top: 10px;">
            <input type="hidden" name="newaddress" value="go">
            <input type="submit" name="buttonnewaddress" value="" class="buttonnewaddress">
            </form>
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>