<?php
include "../control.php";
include "../header.php";
include "../style.php";

if($userid != "")
   	{  // members only stuff!

		include("navigation.php");
        include("../banners2.php");

		$query = "delete from members where userid='".$userid."'";
		$result = mysql_query ($query)
			or die ("Delete failed");

mysql_query("INSERT INTO transactions VALUES ('id', '".$userid."','Member Deleted Self','".time()."','')");

        echo "Successfully deleted from Database.";
        echo "</td></tr></table>";

	}

else
  { ?>

  <p>You must be logged in to access this site. Please <a href="../login.php">click here</a> to login.</p>

  <? }

include "../footer.php";
mysql_close($dblink);
?>