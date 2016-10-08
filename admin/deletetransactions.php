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


foreach($id as $each) {
		mysql_query ("delete from transactions where id=".$each);
		}


    echo "The transaction has been deleted!";

    ?><p>Click <a href=customertransactions.php>here</a> to go back<p><?
    echo "</td></tr></table>";
include "../footer.php";
mysql_close($dblink);
?>