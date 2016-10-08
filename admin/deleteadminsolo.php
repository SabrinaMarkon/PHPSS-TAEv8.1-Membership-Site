<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";

$id = mysql_real_escape_string($_POST['id']);

    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td valign="top" align="center"><br><br> <?
    echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";

    $query1 = "delete from adminsolos where id='".$id."'";
	$result1 = mysql_query ($query1);

    $query2 = "delete from adminlinks where adid='".$id."'";
	$result2 = mysql_query ($query2);

    $query3 = "delete from adminclicks where adid='".$id."'";
	$result3 = mysql_query ($query3);

    echo "The solo ad has been deleted!";

    ?><p>Click <a href=viewalladminsolos.php>here</a> to go back<p><?
    echo "</td></tr></table>";
include "../footer.php";
mysql_close($dblink);
?>