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
   <title>BDRF Wallet System</title>
   <link rel="icon" type="image/png" href="images/favicon.png">
   <link rel="stylesheet" type="text/css" href="style_default.css">
   <script type="text/javascript" src="jquery-1.3.1.min.js" ></script>
   <script type="text/javascript" src="jquery.timers-1.1.2.js" ></script>
   <script type="text/javascript">
      $(function() {
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
         $("#chatroom").load("ajax_chat.php");
         $('#chatroom').scrollTop($('#chatroom')[0].scrollHeight);
      }, 500);
      setInterval(function () {
         $("#chatroom").load("ajax_chat.php");
         $('#chatroom').scrollTop($('#chatroom')[0].scrollHeight);
      }, 1500);
   </script>
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
                  <td align="center">BDRF Wallet System</td>
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
            <b style="font-size: 11px;">BDRF Wallet System Home:</b>
            <div style="margin-top: 10px;"></div>
            <div id="chatroom" style="width: 100%; height: 400px; background: #FFFFFF; border-top: 1px solid #d8d8d8; border-left: 1px solid #d8d8d8; border-right: 1px solid #d8d8d8; border-left: 0px none #d8d8d8; overflow-y: scroll;">
            <div class="innerchat">
            <center><img src="images/loading.gif" border="0"></center>
            </div>
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