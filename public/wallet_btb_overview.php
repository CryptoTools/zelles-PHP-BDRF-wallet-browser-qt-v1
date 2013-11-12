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
$pz_coin_name = 'Bitbar';
$pz_coin_initl = 'btb';
$pz_coin_initu = 'BTB';
$pz_coin_api = 'Bitbar';

$json_url = 'http://bdrf.info/api_'.$pz_coin_initl.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getbalance&acnt='.$udb_email.'&sid=BDRFM';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $json_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$json_feed = curl_exec($ch);
curl_close($ch);
$balance_array = json_decode($json_feed, true);
$balance = $balance_array['balance'];
$unconfirmed = $balance_array['unconfirmed'];
$cntr = "0";
$bgcol = "1";
$txss = '';
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
   $cntr++;
   if($cntr<="3") {
      $dtx_address = $transactions[$key]['address'];
      $dtx_category = $transactions[$key]['category'];
      $dtx_amount = $transactions[$key]['amount'];
      $dtx_timestamp = $transactions[$key]['time'];
      if(!$dtx_address) { $dtx_address = '<i style="color: 888888;">(unavailable)</i>'; }
      $dtx_time = date("n/j/Y G:i",$dtx_timestamp);
      if($dtx_timestamp!="") {
         if($bgcol=="1") { $bgcol = "2"; $useclass = 'txtda'; } else { $bgcol = "1"; $useclass = 'txtdb'; }
         $dtx_type = '<img src="images/icon_other_large.png" style="width: 72px;">';
         if($dtx_category=="send") { $dtx_type = '<img src="images/icon_sent_large.png" style="width: 72px;">'; }
         if($dtx_category=="receive") { $dtx_type = '<img src="images/icon_received_large.png" style="width: 72px;">'; }
         $txss .= '<tr>
                      <td rowspan="2" style="width: 72px;">'.$dtx_type.'</td>
                      <td style="padding-left: 10px;">'.$dtx_time.'</td>
                      <td align="right">'.$dtx_amount.'</td>
                   </tr><tr>
                      <td colspan="2" style="padding-left: 10px;">'.$dtx_address.'</td>
                   </tr>';
      }
   }
}
$cntr = $cntr - 1;
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
            $menu_page = "overview";
            require'z_menu.php';
            ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <table class="overviewtable">
               <tr>
                  <td valign="top">
                     <table>
                        <tr>
                           <td valign="top" style="height: 30px; font-size: 15px; font-weight: bold;" nowrap>Wallet</td>
                           <td valign="top" nowrap></td>
                        </tr><tr>
                           <td valign="top" style="height: 30px; font-size: 11px;" nowrap>Balance:</td>
                           <td valign="top" style="padding-left: 15px; font-size: 11px; font-weight: bold;" nowrap><?php echo $balance.' '.$pz_coin_initu  ?></td>
                        </tr><tr>
                           <td valign="top" style="height: 40px; font-size: 11px;" nowrap>Unconfirmed:</td>
                           <td valign="top" style="padding-left: 15px; font-size: 11px; font-weight: bold;" nowrap><?php echo $unconfirmed.' '.$pz_coin_initu  ?></td>
                        </tr><tr>
                           <td valign="top" style="font-size: 11px;" nowrap>Number of transactions:</td>
                           <td valign="top" style="padding-left: 15px; font-size: 11px;" nowrap><?php echo $cntr; ?></td>
                        </tr>
                     </table>
                  </td>
                  <td valign="top" class="overviewtdr">
                     <table style="width: 100%; font-size: 10px;">
                        <tr>
                           <td colspan="3" valign="top" style="height: 30px; font-size: 11px; font-weight: bold;" nowrap>Recent transactions</td>
                        </tr><?php echo $txss; ?>
                     </table>
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