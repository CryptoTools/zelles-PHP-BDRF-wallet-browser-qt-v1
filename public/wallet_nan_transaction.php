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
            $menu_page = "transactions";
            require'z_menu.php';
            ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <div class="txdiv">
            <table style="width: 100%;">
               <tr>
                  <td class="txtdt"></td>
                  <td class="txtdt">Time</td>
                  <td class="txtdt">Type</td>
                  <td class="txtdt">Address</td>
                  <td class="txtdt">Amount</td>
               </tr>
            <?php
            $bgcol = "1";
            $useclass = 'txtdb';
            $json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=listtransactions&acnt='.$udb_email.'&sid=BDRFM';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $json_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json_feed = curl_exec($ch);
            curl_close($ch);
            $tx_array = json_decode($json_feed, true);
            $transactions = $tx_array['transactions'];
            function invenDescSort($item1,$item2) {
               if ($item1['time'] == $item2['time']) return 0;
               return ($item1['time'] < $item2['time']) ? 1 : -1;
            }
            usort($transactions,'invenDescSort');
            foreach($transactions as $key => $value) {
               $dtx_confirmations = $transactions[$key]['confirmations'];
               $dtx_address = $transactions[$key]['address'];
               $dtx_category = $transactions[$key]['category'];
               $dtx_amount = $transactions[$key]['amount'];
               $dtx_timestamp = $transactions[$key]['time'];
               if(!$dtx_address) { $dtx_address = '<i style="color: 888888;">(unavailable)</i>'; $dtx_confirmations = '10'; }
               if($dtx_timestamp!="") {
                  $dtx_time = date("n/j/Y G:i",$dtx_timestamp);
                  if($bgcol=="1") { $bgcol = "2"; $useclass = 'txtda'; } else { $bgcol = "1"; $useclass = 'txtdb'; }
                  if($dtx_category=="send") { $dtx_type = 'Sent to'; }
                  if($dtx_category=="receive") { $dtx_type = 'Received with'; }
                  if($dtx_confirmations>"6") { $dtx_confirmations = "&#8730;"; }
                  echo '<tr>
                           <td class="'.$useclass.'">'.$dtx_confirmations.'</td>
                           <td class="'.$useclass.'">'.$dtx_time.'</td>
                           <td class="'.$useclass.'">'.$dtx_type.'</td>
                           <td class="'.$useclass.'">'.$dtx_address.'</td>
                           <td align="right" class="'.$useclass.'">'.$dtx_amount.'</td>
                        </tr>';
               }
            }
            ?>
            </table>
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>