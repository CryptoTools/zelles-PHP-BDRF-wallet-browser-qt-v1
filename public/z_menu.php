<table class="menutable">
   <tr>
      <?php
      if($menu_page=="overview") {
         echo '<td class="menuoverviewtd"><input type="submit" name="menuoverview" value="" class="menuoverviewactive" onclick="window.location = \'wallet_'.$pz_coin_initl.'_overview.php\';"></td>';
      } else {
         echo '<td class="menuoverviewtd"><input type="submit" name="menuoverview" value="" class="menuoverview" onclick="window.location = \'wallet_'.$pz_coin_initl.'_overview.php\';"></td>';
      }
      if($menu_page=="send") {
         echo '<td class="menusendtd"><input type="submit" name="menusend" value="" class="menusendactive" onclick="window.location = \'wallet_'.$pz_coin_initl.'_send.php\';"></td>';
      } else {
         echo '<td class="menusendtd"><input type="submit" name="menusend" value="" class="menusend" onclick="window.location = \'wallet_'.$pz_coin_initl.'_send.php\';"></td>';
      }
      if($menu_page=="receive") {
         echo '<td class="menureceivetd"><input type="submit" name="menureceive" value="" class="menureceiveactive" onclick="window.location = \'wallet_'.$pz_coin_initl.'_receive.php\';"></td>';
      } else {
         echo '<td class="menureceivetd"><input type="submit" name="menureceive" value="" class="menureceive" onclick="window.location = \'wallet_'.$pz_coin_initl.'_receive.php\';"></td>';
      }
      if($menu_page=="transactions") {
         echo '<td class="menutransactiontd"><input type="submit" name="menutransaction" value="" class="menutransactionactive" onclick="window.location = \'wallet_'.$pz_coin_initl.'_transaction.php\';"></td>';
      } else {
         echo '<td class="menutransactiontd"><input type="submit" name="menutransaction" value="" class="menutransaction" onclick="window.location = \'wallet_'.$pz_coin_initl.'_transaction.php\';"></td>';
      }
      if($menu_page=="addressbook") {
         echo '<td class="menuaddressbooktd"><input type="submit" name="menuaddressbook" value="" class="menuaddressbookactive" onclick="window.location = \'wallet_'.$pz_coin_initl.'_addressbook.php\';"></td>';
      } else {
         echo '<td class="menuaddressbooktd"><input type="submit" name="menuaddressbook" value="" class="menuaddressbook" onclick="window.location = \'wallet_'.$pz_coin_initl.'_addressbook.php\';"></td>';
      }
      if(($menu_page=="overview")||($menu_page=="send")) {
         echo '<td class="menuexporttd"><input type="submit" name="menuexport" value="" class="menuexportfaded"></td>';
      } else {
         echo '<td class="menuexporttd"><input type="submit" name="menuexport" value="" class="menuexport" onclick="alert(\'Coming soon.\');"></td>';
      }
      ?>
   </tr>
</table>