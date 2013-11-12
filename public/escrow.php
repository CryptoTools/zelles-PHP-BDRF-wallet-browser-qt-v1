<?php
require"../coinapi_private/data.php";
set_time_limit(0);
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
if(isset($_POST['cancel'])) {
   $ordercancel = security($_POST['cancel']);
   $Query = mysql_query("SELECT status FROM trade WHERE status='open'");
   if(mysql_num_rows($Query) != 0) {
      $Query = mysql_query("SELECT id, buyer, offer, total FROM trade WHERE id='$ordercancel' and status='open' LIMIT 1");
      while($Row = mysql_fetch_assoc($Query)) {
         $cdb_buyer = $Row['buyer'];
         $cdb_offer = $Row['offer'];
         $cdb_total = $Row['total'];
      }
      if($cdb_buyer==$cdb_buyer) {
         $json_url = 'http://bdrf.info/api_'.$cdb_offer.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=move&acnt=trade&sid=BDRFM&to=zme@hack4.us-BDRFM-'.$cdb_buyer.'&amount='.$cdb_total;
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $json_url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $json_feed = curl_exec($ch);
         curl_close($ch);
         $txid_array = json_decode($json_feed, true);
         $move_result = $txid_array['message'];
         if($move_result=="success") {
            $sql = mysql_query("UPDATE trade SET seller='$udb_email' WHERE id='$ordercancel'");
            $sql = mysql_query("UPDATE trade SET status='canceled' WHERE id='$ordercancel'");
            $onloader = ' onload="alert(\'Order was canceled and refunded.\')"';
         } else {
            $onloader = ' onload="alert(\'Server connection error.\nTry again.\')"';
         }
      } else {
         $onloader = ' onload="alert(\'This is not your order to cancel.\')"';
      }
   } else {
      $onloader = ' onload="alert(\'It looks like that order was filled or canceled.\')"';
   }
}
if(isset($_POST['complete'])) {
   $ordercomplete = security($_POST['complete']);
   $Query = mysql_query("SELECT status FROM trade WHERE status='open'");
   if(mysql_num_rows($Query) != 0) {
      $Query = mysql_query("SELECT id, buyer, want, offer, amount, rate, total FROM trade WHERE id='$ordercomplete' and status='open' LIMIT 1");
      while($Row = mysql_fetch_assoc($Query)) {
         $cdb_buyer = $Row['buyer'];
         $cdb_want = $Row['want'];
         $cdb_offer = $Row['offer'];
         $cdb_amount = $Row['amount'];
         $cdb_rate = $Row['rate'];
         $cdb_total = $Row['total'];
      }
      if($cdb_buyer!=$udb_email) {
         $json_url = 'http://bdrf.info/api_'.$cdb_want.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getbalance&acnt='.$udb_email.'&sid=BDRFM';
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $json_url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $json_feed = curl_exec($ch);
         curl_close($ch);
         $balance_array = json_decode($json_feed, true);
         $usermoney = $balance_array['balance'];
         if(!$usermoney) {
            $onloader = ' onload="alert(\'Server connection error.\nTry again.\')"';
         } else {
            if($usermoney>=$cdb_total) {
               $json_url = 'http://bdrf.info/api_'.$cdb_want.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=move&acnt='.$udb_email.'&sid=BDRFM&to=zme@hack4.us-BDRFM-'.$cdb_buyer.'&amount='.$cdb_amount;
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $json_url);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
               $json_feed = curl_exec($ch);
               curl_close($ch);
               $txid_array = json_decode($json_feed, true);
               $move_result = $txid_array['message'];
               if($move_result=="success") {
                  $move_result=="empty";
                  $json_url = 'http://bdrf.info/api_'.$cdb_offer.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=move&acnt=trade&sid=BDRFM&to=zme@hack4.us-BDRFM-'.$udb_email.'&amount='.$cdb_total;
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $json_url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  $json_feed = curl_exec($ch);
                  curl_close($ch);
                  $txid_array = json_decode($json_feed, true);
                  $move_result = $txid_array['message'];
                  if($move_result=="success") {
                     $sql = mysql_query("UPDATE trade SET seller='$udb_email' WHERE id='$ordercomplete'");
                     $sql = mysql_query("UPDATE trade SET status='success' WHERE id='$ordercomplete'");
                     $onloader = ' onload="alert(\'You have completed this order.\')"';
                  } else {
                     $sql = mysql_query("UPDATE trade SET seller='$udb_email' WHERE id='$ordercomplete'");
                     $sql = mysql_query("UPDATE trade SET status='failed' WHERE id='$ordercomplete'");
                     $onloader = ' onload="alert(\'Server connection error during trade.\nAll data is saved and admin will fix it soon.\')"';
                  }
               } else {
                  $onloader = ' onload="alert(\'Server connection error.\nTry again..\')"';
               }
            } else {
               $onloader = ' onload="alert(\'You do not have enough to complete this order.\')"';
            }
         }
      } else {
         $onloader = ' onload="alert(\'You can not buy from yourself.\')"';
      }
   } else {
      $onloader = ' onload="alert(\'It looks like that order was filled or canceled.\')"';
   }
}
if(isset($_POST['want'])) {
   $orderwant = security($_POST['want']);
   $orderoffer = security($_POST['offer']);
   $orderamount = satoshisize(security($_POST['amount']));
   $orderrate = satoshisize(security($_POST['rate']));
   if(!$orderrate) {
      $onloader = ' onload="alert(\'No rate was entered.\')"';
   } else {
      if(!$orderamount) {
         $onloader = ' onload="alert(\'No amount entered.\')"';
      } else {
         if($orderamount>="0.00000001") {
         if($orderrate>="0.00000001") {
            if(is_numeric($orderamount)) {
               if(is_numeric($orderrate)) {
                  $ordertotal = satoshisize(($orderamount * $orderrate));
                  if(($orderwant=="btb")||($orderwant=="btc")||($orderwant=="ftc")||($orderwant=="ltc")||($orderwant=="mec")||($orderwant=="nan")) {
                     if(($orderoffer=="btb")||($orderoffer=="btc")||($orderoffer=="ftc")||($orderoffer=="ltc")||($orderoffer=="mec")||($orderoffer=="nan")) {
                        $json_url = 'http://bdrf.info/api_'.$orderoffer.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getbalance&acnt='.$udb_email.'&sid=BDRFM';
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $json_url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $json_feed = curl_exec($ch);
                        curl_close($ch);
                        $balance_array = json_decode($json_feed, true);
                        $usermoney = $balance_array['balance'];
                        if(!$usermoney) {
                           $onloader = ' onload="alert(\'Server connection error.\nTry again.\')"';
                        } else {
                           if($usermoney>=$ordertotal) {
                              if($orderwant!=$orderoffer) {
                                 $json_url = 'http://bdrf.info/api_'.$orderoffer.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=move&acnt='.$udb_email.'&sid=BDRFM&to=zme@hack4.us-BDRFM-trade&amount='.$ordertotal;
                                 $ch = curl_init();
                                 curl_setopt($ch, CURLOPT_URL, $json_url);
                                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                 $json_feed = curl_exec($ch);
                                 curl_close($ch);
                                 $txid_array = json_decode($json_feed, true);
                                 $move_result = $txid_array['message'];
                                 if($move_result=="success") {
                                    $sql = mysql_query("INSERT INTO trade (id,date,ip,buyer,seller,want,offer,amount,rate,total,status) VALUES ('','$date','$ip','$udb_email','','$orderwant','$orderoffer','$orderamount','$orderrate','$ordertotal','open')");
                                    $onloader = ' onload="alert(\'Offer was placed and is waiting for someone to complete it.\')"';
                                 } else {
                                    $onloader = ' onload="alert(\'Server connection error.\nTry again.\')"';
                                 }
                              } else {
                                 $onloader = ' onload="alert(\'You can not trade for the same coin.\')"';
                              }
                           } else {
                              $onloader = ' onload="alert(\'You dont have enough '.$orderoffer.' to place this order.\')"';
                           }
                        }
                     } else {
                        $onloader = ' onload="alert(\'Invalid coin selected.\')"';
                     }
                  } else {
                     $onloader = ' onload="alert(\'Invalid coin selected.\')"';
                  }
               } else {
                  $onloader = ' onload="alert(\'Invalid rate was entered.\')"';
               }
            } else {
               $onloader = ' onload="alert(\'Invalid amount was entered.\')"';
            }
         } else {
            $onloader = ' onload="alert(\'No rates below 0.00000001 allowed!\')"';
         }
         } else {
            $onloader = ' onload="alert(\'No amounts below 0.00000001 allowed!\')"';
         }
      }
   }
}

?>
<html>
<head>
   <title>BDRF Escrow System</title>
   <link rel="icon" type="image/png" href="images/favicon.png">
   <link rel="stylesheet" type="text/css" href="style_default.css">
   <script type="text/javascript" src="jquery-1.3.1.min.js" ></script>
   <script type="text/javascript" src="jquery.timers-1.1.2.js" ></script>
   <script type="text/javascript">
      $(function() {
         $('#viewbal').change(function() {
            var val = this.value;
            $("#bal").html('<img src="images/loading.gif" style="width:16px;" title="Loading">');
            $('#bal').load('ajax_balance.php?type='+val);
         })
         $("#chatter").submit(function(event) {
            event.preventDefault();
            var $form = $( this ),
            term = $form.find( 'input[name="speak"]' ).val(),
            url = $form.attr( 'action' );
            var posting = $.post( url, { speak: term } );
            $('#speak').val('');
            var val = this.value;
         })
      });
      setTimeout(function() {
         $('#bal').load('ajax_balance.php?type=btb');
         $("#latesttrades").load("ajax_escrow.php");
      }, 500);
      setInterval(function () {
         $("#latesttrades").load("ajax_escrow.php");
      }, 15000);
      setInterval(function () {
         $("#chatroom").load("ajax_chat.php");
         $('#chatroom').scrollTop($('#chatroom')[0].scrollHeight);
      }, 2000);
   </script>
   <script type="text/javascript">
      function toggle_visibility(id) {
         var e = document.getElementById(id);
         if(e.style.display == 'block')
            e.style.display = 'none';
         else
            e.style.display = 'block';
      }
      function buycalculator() {
         m = document.getElementById("amount").value;
         n = document.getElementById("rate").value;
         if(m=="") { m = 0; }
         if(n=="") { n = 0; }
         o = m*n;
         g = o.toFixed(8);
         document.getElementById("estimated").innerHTML = g;
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
                  <td align="center">BDRF Escrow System</td>
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
            <b style="font-size: 11px;">BDRF Escrow System:</b>
            <table style="width: 100%;">
               <tr>
                  <td valign="top">
                     <div class="tradescroll" id="latesttrades">
                     <center><img src="images/loading.gif" border="0"></center>
                     </div>
                  </td>
                  <td valign="top" style="width: 220px;">
                     <table style="width: 220px; font-size: 11px;">
                        <tr>
                           <td colspan="2" style="height:18px; font-weight: bold;" nowrap>Balances:</td>
                        </tr><tr>
                           <td style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Type:</td>
                           <td style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>
                              <select id="viewbal" style="width: 100%; text-align: right;">
                                 <option value="btb">Bitbar</option>
                                 <option value="btc">Bitcoin</option>
                                 <option value="ftc">Feathercoin</option>
                                 <option value="ltc">Litecoin</option>
                                 <option value="mec">Megacoin</option>
                                 <option value="nan">Nanotoken</option>
                              </select>
                           </td>
                        </tr><tr>
                           <td style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Balance:</td>
                           <td align="right" style="height:18px; padding-left: 15px;" nowrap><span id="bal"><img src="images/loading.gif" style="width:16px;" title="Loading"></span></td>
                        </tr>
                     </table>
                     <div style="margin-top: 10px;"></div>
                     <form action="escrow.php" method="POST">
                     <table style="width: 220px; font-size: 11px;">
                        <tr>
                           <td colspan="2" style="height:18px; font-weight: bold;" nowrap>Post offer:</td>
                        </tr><tr>
                           <td title="The one you want. The 'Amount'." style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Want</td>
                           <td title="The one you want. The 'Amount'." align="right" style="height:18px; padding-left: 15px;" nowrap>
                              <select name="want" style="width: 100%; text-align: right;">
                                 <option value="btb">Bitbar</option>
                                 <option value="btc">Bitcoin</option>
                                 <option value="ftc">Feathercoin</option>
                                 <option value="ltc">Litecoin</option>
                                 <option value="mec">Megacoin</option>
                                 <option value="nan">Nanotoken</option>
                              </select>
                           </td>
                        </tr><tr>
                           <td title="The one you are offering. Used for the 'Rate'." style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Offer:</td>
                           <td title="The one you are offering. Used for the 'Rate'." align="right" style="height:18px; padding-left: 15px;" nowrap>
                              <select name="offer" style="width: 100%; text-align: right;">
                                 <option value="btb">Bitbar</option>
                                 <option value="btc">Bitcoin</option>
                                 <option value="ftc">Feathercoin</option>
                                 <option value="ltc">Litecoin</option>
                                 <option value="mec">Megacoin</option>
                                 <option value="nan">Nanotoken</option>
                              </select>
                           </td>
                        </tr><tr>
                           <td title="The amount you want." style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Amount:</td>
                           <td title="The amount you want." align="right" style="height:18px; padding-left: 15px;" nowrap><input type="text" name="amount" id="amount" onkeyup="buycalculator()" onchange="buycalculator()" onmouseout="buycalculator()" style="width: 100%; text-align: right;"></td>
                        </tr><tr>
                           <td title="The rate you will buy each one at." style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Rate:</td>
                           <td title="The rate you will buy each one at." align="right" style="height:18px; padding-left: 15px;" nowrap><input type="text" name="rate" id="rate" onkeyup="buycalculator()" onchange="buycalculator()" onmouseout="buycalculator()" style="width: 100%; text-align: right;"></td>
                        </tr><tr>
                           <td title="The estimated total." style="height:18px; padding-left: 15px; font-weight: bold;" nowrap>Estimated:</td>
                           <td title="The estimated total." align="right" style="height:18px; padding-left: 15px;" nowrap><span id="estimated">0.00000000</span></td>
                        </tr><tr>
                           <td align="right" colspan="2" style="height:18px; font-weight: bold;" nowrap><input type="submit" name="submit" value="Submit" style="padding: 2px;"></td>
                        </tr>
                     </table>
                     </form>
                  </td>
               </tr>
            </table>
            <div style="margin-top: 10px;"></div>
            <div id="chatroom" style="width: 100%; height: 190px; background: #FFFFFF; border-top: 1px solid #d8d8d8; border-left: 1px solid #d8d8d8; border-right: 1px solid #d8d8d8; border-left: 0px none #d8d8d8; overflow-y: scroll;">
            <center><img src="images/loading.gif" border="0"></center>
            </div>
            <form action="ajax_speak.php" id="chatter">
            <input type="text" name="speak" id="speak" style="width: 100%; height: 20px; border: 1px solid #d8d8d8;">
            </form>
            </div>
         </td>
      </tr>
   </table>
   </center>
</body>
</html>
<?php
set_time_limit(30);
?>