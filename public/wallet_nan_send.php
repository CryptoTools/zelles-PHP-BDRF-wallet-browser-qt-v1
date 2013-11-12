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
$pz_coin_name = 'Nanotoken';
$pz_coin_initl = 'nan';
$pz_coin_initu = 'NAN';
$pz_coin_api = 'Nanotoken';

if(isset($_POST['send'])) {
   $sendamount = security($_POST['sendamount']);
   $sendto = security($_POST['sendto']);
   $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=sendcoin&acnt='.$udb_email.'&sid=BDRFM&to='.$sendto.'&amount='.$sendamount;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $json_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $json_feed = curl_exec($ch);
   curl_close($ch);
   $txid_array = json_decode($json_feed, true);
   $txid = $txid_array['txid'];
   $txidmessage = $txid_array['message'];
   if($txid) {
      $onloader = ' onload="alert(\''.$sendamount.' '.$pz_coin_name.'s have been sent successfully.\nTxid: '.$txid.'\')"';
   } else {
      $onloader = ' onload="alert(\'Error: '.$txidmessage.'\')"';
   }
}
$json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getbalance&acnt='.$udb_email.'&sid=BDRFM';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $json_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$json_feed = curl_exec($ch);
curl_close($ch);
$balance_array = json_decode($json_feed, true);
$balance = $balance_array['balance'];
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
            $menu_page = "send";
            require'z_menu.php';
            ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <form action="wallet_<?php echo $pz_coin_initl; ?>_send.php" method="POST">
            <div class="senddiv">
            <table style="width: 100%;">
               <tr>
                  <td>
                     <table style="width: 100%; font-size: 11px; border: 1px solid #d8d8d8;">
                        <tr>
                           <td style="height: 34px; width: 60px; padding: 10px;" nowrap>Pay To:</td>
                           <td style="height: 34px; padding: 10px;" nowrap><input type="text" name="sendto" placeholder="Enter a <?php echo $pz_coin_name; ?> address" style="width: 100%; height: 24px;"></td>
                        </tr><tr>
                           <td style="height: 34px; padding: 10px;" nowrap>Amount:</td>
                           <td style="height: 34px; padding: 10px;" nowrap><input type="text" name="sendamount" style="width: 160px; height: 24px;"></td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
            </div>
            <table style="width: 100%; font-size: 11px; margin-top: 10px;">
               <tr>
                  <td>Balance: <?php echo $balance.' '.$pz_coin_initu; ?></td>
                  <td align="right">
                     <input type="hidden" name="send" value="go">
                     <input type="submit" name="submit" value="" class="buttonsend">
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