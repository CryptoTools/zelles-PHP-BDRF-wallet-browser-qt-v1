<?php
require"../coinapi_private/data.php";
$getaction = security($_POST['action']);
if(!isset($_SESSION['apiidentity'])) {
   die("n/a");
}
if(isset($_SESSION['apiidentity'])) {
   $EMAIL_INDENT = security($_SESSION['apiidentity']);
   $Query = mysql_query("SELECT email FROM accounts WHERE email='$EMAIL_INDENT'");
   if(mysql_num_rows($Query) == 0) {
      die("n/a");
   }
}
$coin = "none";
if(isset($_GET['type'])) {
   $coin_type = security($_GET['type']);
   if($coin_type=="btb") { $coin = "btb"; }
   if($coin_type=="btc") { $coin = "btc"; }
   if($coin_type=="ftc") { $coin = "ftc"; }
   if($coin_type=="ltc") { $coin = "ltc"; }
   if($coin_type=="mec") { $coin = "mec"; }
   if($coin_type=="nan") { $coin = "nan"; }
   if($coin!="none") {
      $json_url = 'http://bdrf.info/api_'.$coin.'.php?puk=jCM8kKazKMOcUDyhP80vIYYjy5DdGixnhr&prk=FsDCfGc8tUUDnoyjwezqxHQOJ9lXOiYUz8ScD&act=getbalance&acnt='.$udb_email.'&sid=BDRFM';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $json_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $json_feed = curl_exec($ch);
      curl_close($ch);
      $balance_array = json_decode($json_feed, true);
      $balance = $balance_array['balance'];
      echo $balance;
   }
}
?>