<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";
$id = $_POST['id'];
    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td  valign="top" align="center"><br><br> <?
        echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";



        $query = "update buttons set status=0 where id=".$id;
		$result = mysql_query ($query)
	     	or die ("Query failed");
        echo "<p><center>The button banner ad has been sent back to the user.</p></center>";
		        echo "</td></tr></table>";
include "../footer.php";

?>