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
        <td valign="top" align="center"><br><br> <?
    echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";

    $query1 = "delete from botnav where id='".$id."'";
	$result1 = mysql_query ($query1);

    echo "The bottom navigation link has been deleted!";

    ?><p>Click <a href=viewallbotnav.php>here</a> to go back<p><?
    echo "</td></tr></table>";
include "../footer.php";
mysql_close($dblink);
?>