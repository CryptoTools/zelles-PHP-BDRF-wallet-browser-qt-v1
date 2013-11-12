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
?>
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
                              if($udb_email!=$tdb_buyer) {
                                 $textcolor = '#999999';
                                 $orderbutton = '<form action="escrow.php" method="POST">
                                                 <input type="hidden" name="complete" value="'.$tdb_id.'">
                                                 <input type="submit" name="submit" value="Complete" style="padding: 2px;">
                                                 </form>';
                              } else {
                                 $textcolor = '#000000';
                                 $orderbutton = '<form action="escrow.php" method="POST">
                                                 <input type="hidden" name="cancel" value="'.$tdb_id.'">
                                                 <input type="submit" name="submit" value="Cancel" style="padding: 2px;">
                                                 </form>';
                              }
                              echo '<tr>
                                       <td title="What the user wants." style="border-bottom: 1px solid #d8d8d8; color: '.$textcolor.'; padding: 2px;">'.$tdb_want.'</td>
                                       <td title="What the user is offering." style="border-bottom: 1px solid #d8d8d8; color: '.$textcolor.'; padding: 2px;">'.$tdb_offer.'</td>
                                       <td align="right" title="The amount that the user wants." style="border-bottom: 1px solid #d8d8d8; color: '.$textcolor.'; padding: 2px;">'.$tdb_amount.'</td>
                                       <td align="right" title="The rate the user is offering for each one." style="border-bottom: 1px solid #d8d8d8; color: '.$textcolor.'; padding: 2px;">'.$tdb_rate.'</td>
                                       <td align="right" title="The total that the user is willing to pay." style="border-bottom: 1px solid #d8d8d8; color: '.$textcolor.'; padding: 2px;">'.$tdb_total.'</td>
                                       <td align="center" style="border-bottom: 1px solid #d8d8d8; font-weight: bold; padding: 2px;">'.$orderbutton.'</td>
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