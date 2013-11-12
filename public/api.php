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
?>
<html>
<head>
   <title>BDRF Coin Wallets API</title>
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
<body>
   <center>
   <table class="console">
      <tr>
         <td align="left" class="consoletitle">
            <table class="consoletitletable">
               <tr>
                  <td align="left" class="consoletitletd"></td>
                  <td align="center">BDRF Coin Wallets API</td>
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
            <b style="font-size: 11px;">BDRF Wallet API:</b>
            <div class="apidiv">
            <div style="padding-left: 10px">
            <b style="font-size:11px;">Your API keys:</b>
            <table style="font-size:11px; margin-top: 10px; margin-bottom: 10px;">
               <tr>
                  <td valign="top" style="padding: 3px; padding-left: 20px;" nowrap>Key 1:</td>
                  <td valign="top" style="padding: 3px; padding-left: 20px;" nowrap><?php echo $udb_pub_key; ?></td>
               </tr><tr>
                  <td valign="top" style="padding: 3px; padding-left: 20px;" nowrap>Key 2:</td>
                  <td valign="top" style="padding: 3px; padding-left: 20px;" nowrap><?php echo $udb_priv_key; ?></td>
               </tr>
            </table>
            <p></p>
            <b style="font-size:11px;">Coin APIs offered:</b>
            <table style="font-size:11px; margin-top: 10px; margin-bottom: 10px;">
               <tr>
                  <td style="padding: 3px; padding-left: 20px;">BTB Bitbar</td>
                  <td style="padding: 3px; padding-left: 20px;">api_btb.php</td>
               </tr><tr>
                  <td style="padding: 3px; padding-left: 20px;">BTC Bitcoin</td>
                  <td style="padding: 3px; padding-left: 20px;">api_btc.php</td>
               </tr><tr>
                  <td style="padding: 3px; padding-left: 20px;">FTC Feathercoin</td>
                  <td style="padding: 3px; padding-left: 20px;">api_ftc.php</td>
               </tr><tr>
                  <td style="padding: 3px; padding-left: 20px;">LTC Litecoin</td>
                  <td style="padding: 3px; padding-left: 20px;">api_ltc.php</td>
               </tr><tr>
                  <td style="padding: 3px; padding-left: 20px;">MEC Megacoin</td>
                  <td style="padding: 3px; padding-left: 20px;">api_mec.php</td>
               </tr><tr>
                  <td style="padding: 3px; padding-left: 20px;">NAN Nanotoken</td>
                  <td style="padding: 3px; padding-left: 20px;">api_nan.php</td>
               </tr>
            </table>
            <p></p>
            <b style="font-size:11px;">Required for all calls:</b>
            <ul style="font-size:11px; margin-top: 10px; margin-bottom: 10px; padding-left: 20px;">
               <li>puk= Key 1, your key located above.</li>
               <li>prk= Key 2, your key located above.</li>
               <li>act= API Call</li>
            </ul>
            <p></p>
            <b style="font-size:11px;">Required for some calls:</b>
            <ul style="font-size:11px; margin-top: 10px; margin-bottom: 10px; padding-left: 20px;">
               <li>acnt= Acount name, usually the username or email for the user.</li>
               <li>sid= Group ID, up to 5 digits. Each site needs a different ID.</li>
               <li>to= An address to send to.</li>
               <li>amount= Amount of coins.</li>
               <li>cnt=amount Amount transactions to list, this call is optional.</li>
            </ul>
            <p></p>
            <center>
            <table style="font-size:11px; border-collapse: collapse;">
               <tr>
                  <td valign="top" style="font-weight: bold;" nowrap>API Calls:</td>
                  <td valign="top" style="font-weight: bold;">Description</td>
                  <td valign="top" style="font-weight: bold;" nowrap>Required Calls</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>getinfo</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>get the current network block count and difficulty.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>none</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>getbalance</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>get the balace of the specified account.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>acnt=account</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>getnewaddress</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>get a new address for the specified account.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>acnt=account</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>getaccountaddress</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>list the current receiving address for the specified account.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>acnt=account</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>getaccountaddresses</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>list all the receiving addresses for the specified account.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>acnt=account</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>sendcoin</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>send coins from the specified account.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>acnt=account<br>to=address<br>amount=amount</td>
               </tr><tr>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #333333; padding: 3px;" nowrap>listtransactions</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding: 3px;" nowrap>List transactions from the specified account.</td>
                  <td valign="top" style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; border-right: 1px solid #333333; padding: 3px;" nowrap>acnt=account</td>
               </tr>
            </table>
            </center>
            </div>
            </div>
            <form target="calltester" action="calltester.php" method="GET" style="margin-top: 10px; margin-bottom: 10px;">
            <table style="font-size:11px; width: 100%;">
               <tr>
                  <td style="width: 75px; font-weight: bold;" nowrap>Test Calls:</td>
                  <td style="width: 100%;" nowrap><input type="text" name="search" value="http://bdrf.info/api_mec.php?puk=<?php echo $udb_pub_key; ?>&prk=<?php echo $udb_priv_key; ?>&act=getinfo" style="width: 100%; height: 22px;"></td>
                  <td style="width: 50px;" nowrap><input type="submit" name="submit" value="Test" style="width: 50px;"></td>
               </tr>
            </table>
            </form>
            <iframe src="http://bdrf.info/api_mec.php?puk=<?php echo $udb_pub_key; ?>&prk=<?php echo $udb_priv_key; ?>&act=getinfo" name="calltester" style="height: 80px; width: 100%;">
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>