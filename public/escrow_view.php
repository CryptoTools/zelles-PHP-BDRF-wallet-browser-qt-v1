<?php
require"../coinapi_private/data.php";
set_time_limit(0);
$getaction = security($_POST['action']);
if(isset($_SESSION['apiidentity'])) {
   header("Location: index.php");
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
      });
      setTimeout(function() {
         $('#bal').load('ajax_balance.php?type=btb');
      }, 100);
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
            <?php require'z_minimenuo.php'; ?>
         </td>
      </tr><tr>
         <td align="left" valign="top" class="consolebody">
            <div style="padding: 10px;">
            <b style="font-size: 11px;">BDRF Escrow System:</b>
            <table style="width: 100%;">
               <tr>
                  <td valign="top">
                     <div class="tradescroll">
                     <table class="tradetable">
                        <tr>
                           <td title="What the user wants." style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px; width: 90px;">Wanting</td>
                           <td title="What the user is offering." style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px; width: 90px;">Offering</td>
                           <td title="The amount that the user wants." style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px; width: 120px;">Want Amount</td>
                           <td title="The rate the user is offering for each one." style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px; width: 120px;">Offer Rate</td>
                           <td title="The total that the user is willing to pay." style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px; width: 120px;">Offer Total</td>
                           <td style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px; width: 65px;"></td>
                        </tr>
                        <?php
                        $Query = mysql_query("SELECT status FROM trade WHERE status='open'");
                        if(mysql_num_rows($Query) != 0) {
                           $Query = mysql_query("SELECT id, buyer, want, offer, amount, rate, total FROM trade WHERE status='open' ORDER BY id DESC");
                           while($Row = mysql_fetch_assoc($Query)) {
                              $tdb_id = $Row['id'];
                              $tdb_buyer = $Row['buyer'];
                              $tdb_want = $Row['want'];
                              $tdb_offer = $Row['offer'];
                              $tdb_amount = $Row['amount'];
                              $tdb_rate = $Row['rate'];
                              $tdb_total = $Row['total'];
                              if($tdb_want=="btb") { $tdb_want = 'Bitbar'; }
                              if($tdb_want=="btc") { $tdb_want = 'Bitcoin'; }
                              if($tdb_want=="ftc") { $tdb_want = 'Feathercoin'; }
                              if($tdb_want=="ltc") { $tdb_want = 'Litecoin'; }
                              if($tdb_want=="mec") { $tdb_want = 'Megacoin'; }
                              if($tdb_want=="nan") { $tdb_want = 'Nanotoken'; }
                              if($tdb_offer=="btb") { $tdb_offer = 'Bitbar'; }
                              if($tdb_offer=="btc") { $tdb_offer = 'Bitcoin'; }
                              if($tdb_offer=="ftc") { $tdb_offer = 'Feathercoin'; }
                              if($tdb_offer=="ltc") { $tdb_offer = 'Litecoin'; }
                              if($tdb_offer=="mec") { $tdb_offer = 'Megacoin'; }
                              if($tdb_offer=="nan") { $tdb_offer = 'Nanotoken'; }
                              echo '<tr>
                                       <td title="What the user wants." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;">'.$tdb_want.'</td>
                                       <td title="What the user is offering." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;">'.$tdb_offer.'</td>
                                       <td align="right" title="The amount that the user wants." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;">'.$tdb_amount.'</td>
                                       <td align="right" title="The rate the user is offering for each one." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;">'.$tdb_rate.'</td>
                                       <td align="right" title="The total that the user is willing to pay." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;">'.$tdb_total.'</td>
                                       <td align="center" style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px;"></td>
                                    </tr>';
                           }
                        } else {
                           echo '<tr>
                                    <td title="What the user wants." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;"><i>empty</i></td>
                                    <td title="What the user is offering." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;"><i>empty</i></td>
                                    <td align="right" title="The amount that the user wants." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;"><i>empty</i></td>
                                    <td align="right" title="The rate the user is offering for each one." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;"><i>empty</i></td>
                                    <td align="right" title="The total that the user wants." style="border-bottom: 1px solid #d8d8d8; color: #999999; padding: 2px;"><i>empty</i></td>
                                    <td align="center" style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px;"></td>
                                 </tr>';
                        }
                        ?>
                     </table>
                     </div>
                  </td>
                  <td valign="top" style="width: 220px;">

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
<?php
set_time_limit(30);
?>