<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";


    	?><table>

      	<tr>

        <td width="15%" valign=top><br>

        <? include("adminnavigation.php"); ?>

        </td>

        <td valign="top" align="center"><br><br> <?

    	echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";



		$userid = $_POST['userid'];

        $query = "insert into adminsolos (userid) values('$userid')";

		

		$count = 0;

		$quantity = $_POST['quantity'];

		while($quantity > $count) {

			$count++;

			mysql_query ($query);

		}

		

        echo "<p><center>".$quantity." blank solo ad has been added to the admins account.</p></center>";

        echo "</td></tr></table>";

include "../footer.php";



?>