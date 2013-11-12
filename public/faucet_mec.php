<?php
require"../coinapi_private/data.php";

$timestamp_now = strtotime('now');
$timestamp_tomorrow = strtotime('tomorrow');
$day_today_day = date('l',$timestamp_now);
$date_today_date = date('dS',$timestamp_now);
$day_today_time = date('g:i a',$timestamp_now);
$day_today = $day_today_time.' on '.$day_today_day.', the '.$date_today_date;
$date_tomorrow_date = date('dS',$timestamp_tomorrow);
$day_tomorrow_day = date('l',$timestamp_tomorrow);
$day_tomorrow = $day_tomorrow_day.', on the '.$date_tomorrow_date;

$datec = date('G');

$result = mysql_query("SELECT * FROM box2 WHERE datec='$datec'");
$num_rows = mysql_num_rows($result);
if($num_rows>3) {
   $onloader = ' onload="alert(\'It seams someone is trying to abuse us.\nTry again a later today.\')"';
} else {
   if(isset($_POST['faction'])) { $Take_Action = security(strip_tags($_POST['faction'])); } else { $Take_Action = "none"; }
   if(isset($_POST['addr'])) { $User_Address = security(strip_tags($_POST['addr'])); }
   if($Take_Action=="faucet") {
      if(isset($_POST['addr'])) {
         if($User_Address!="") {
               $result = mysql_query("SELECT * FROM box2 WHERE date='$date' and address='$User_Address'");
               $num_rows = mysql_num_rows($result);
               if($num_rows==0) {
                  $result = mysql_query("SELECT * FROM box2 WHERE ip='$ip' and date='$date'");
                  $num_rows = mysql_num_rows($result);
                  if($num_rows==0) {
                     $result = mysql_query("SELECT * FROM box2 WHERE email='$udb_email' and date='$date'");
                     $num_rows = mysql_num_rows($result);
                     if($num_rows==0) {
                        $send_amount = "0.01";
                        $json_url = 'http://bdrf.info/api_mec.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=sendcoin&acnt=me@hack4.us&sid=BDRFM&to='.$User_Address.'&amount='.$send_amount;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $json_url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $json_feed = curl_exec($ch);
                        curl_close($ch);
                        $txid_array = json_decode($json_feed, true);
                        $txid = $txid_array['txid'];
                        if($txid) { $send_message = $txid; } else { $send_message = $txid_array['message']; }
                        $sql = mysql_query("INSERT INTO box2 (id,date,datec,ip,email,address,amount,paid) VALUES ('','$date','$datec','$ip','$udb_email','$User_Address','0.01','1')");
                        $onloader = ' onload="alert(\'Success, Megacoins sent.\n'.$send_message.'\')"';
                     } else {
                        $onloader = ' onload="alert(\'You already requested Megacoins today.\nTry again tomorrow.\')"';
                     }
                  } else {
                     $onloader = ' onload="alert(\'You already requested Megacoins today.\nTry again tomorrow.\')"';
                  }
               } else {
                  $onloader = ' onload="alert(\'You already requested Megacoins today.\nTry again tomorrow.\')"';
               }
         } else {
            $onloader = ' onload="alert(\'You did not enter an address. Try again!\nTry again tomorrow.\')"';
         }
      } else {
         $onloader = ' onload="alert(\'You did not enter an address. Try again!\nTry again tomorrow.\')"';
      }
   }
}
?>
<html>
<head>
   <title>BDRF Megacoin Faucet</title>
   <link rel="icon" type="image/png" href="images/favicon.png">
   <link rel="stylesheet" type="text/css" href="style_default.css">
   <script type="text/javascript" src="jquery-1.3.1.min.js" ></script>
   <script type="text/javascript" src="jquery.timers-1.1.2.js" ></script>
   <script type="text/javascript">
      $(document).ready(function(){
         $("#coina").everyTime(10, function(){
            $("#coina").animate({left:"730px"}, 5000).animate({left:"10"}, 5000);
         });
         $("#coinb").everyTime(10, function(){
            $("#coinb").animate({left:"730px"}, 4000).animate({left:"10"}, 4000);
         });
         $("#coinc").everyTime(10, function(){
            $("#coinc").animate({left:"730px"}, 3000).animate({left:"10"}, 3000);
         });
      });
   </script>
   <script type="text/javascript">
      function toggle_visibility(id) {
         var e = document.getElementById(id);
         if(e.style.display == 'block')
            e.style.display = 'none';
         else
            e.style.display = 'block';
      }
      function setaddr() {
         document.getElementById('addr').value = document.getElementById('setaddr').value;
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
                  <td align="center">BDRF Megacoin Faucet</td>
                  <td align="right" class="consoletitletd"><a href="index.php" style="text-decoration: none; color: #000000;">BDRF.info</a></td>
               </tr>
            </table>
         </td>
      </tr><tr>
         <td align="left" class="consoleminimenu">
            <?php
            if(isset($udb_email)) {
               require'z_minimenu.php';
            } else {
               require'z_minimenuo.php';
            }
            ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px; font-size: 11px;">
            <b>BDRF Megacoin Faucet:</b>
            <center style="margin-top: 20px; font-size: 12px;"><?php echo 'It is <b>'.$day_today.'</b>. Request again <b>'.$day_tomorrow.'</b>'; ?></center>
            <table style="width: 100%; height: 100px;">
               <tr>
                  <td align="center">
                     <table>
                        <tr>
                           <td nowrap>Megacoin Address:</td>
                           <td style="padding-left: 10px;" nowrap><input type="text" id="setaddr" placeholder="MDHdcuvRbdMHFxFhH9kaK12JaNDxpYsNVq" onclick="setaddr()" onkeyup="setaddr()" onkeydown="setaddr()" onchange="setaddr()" style="width: 400px; height: 22px;"></td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
            <form method="POST" action="faucet_mec.php">
            <input type="hidden" name="faction" value="faucet">
            <input type="hidden" id="addr" name="addr" value="">
            <div class="coin_box_rail">
            <div class="coin_box">
               <div id="coina" class="coin"><input type="submit" name="submit" value="" class="targetmec"></div>
            </div>
            <div class="coin_box">
               <div id="coinb" class="coin"><input type="submit" name="submit" value="" class="targetmec"></div>
            </div>
            <div class="coin_box">
               <div id="coinc" class="coin"><input type="submit" name="submit" value="" class="targetmec"></div>
            </div>
            </div>
            </form>
            <div style="margin-top: 10px;"></div>
            Enter you Megacoin Address and click one of the Megacoins above to receive an instant payout.
            <div style="margin-top: 10px;"></div>
            Donate Megacoin to the faucet here: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>