<?php
include "../control.php";
include "../header.php";
include "../style.php";

if($userid != "")
   	{  // members only stuff!

		include("navigation.php");
        include("../banners.php");
        echo "<font size=2 face='$fonttype' color='$fontcolour'><p><center>";
		unset($_SESSION["uname"]);
		unset($_SESSION["pw"]);
        echo "<font size=3 face='$fonttype' color='$fontcolour'>";
		?>
        <center>
		<p>You are now logged out. Please <a href="../index.php">click here</a> to log back in.</p></font>
        </center>
        <?

    echo "</font></td></tr></table></font>";
	}
else
  { ?>

  <font size=3 face="<? echo $fonttype; ?>" color="<? echo $fontcolour; ?>"><p><b><center>You must be logged in to access this site. Please <a href="../index.php">click here</a> to login.</p></b></font>


  <? }

include "../footer.php";
mysql_close($dblink);
?>