<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";
unset($_SESSION["Adminid"]);
unset($_SESSION["Password"]);
?>
<p>You are logged out now. Click <a href=login.php>here</a> to log back in!</p> <?
include "../footer.php";
?>