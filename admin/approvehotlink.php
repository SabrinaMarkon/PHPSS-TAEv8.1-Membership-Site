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

        if($_POST['submit'] == "Delete") {
		
		
		foreach($id as $each) {
		mysql_query ("update hotlinks set added=0 where id=".$each);
		}
		
        echo "<p><center>The Hot Links have been sent back to the users.</p></center>";
		} else {

		foreach($id as $each) {
		mysql_query ("update hotlinks set approved=1, date='".time()."' where id=".$each);
		}
		
        echo "<p><center>The Hot Links have been approved.</p></center>";
		}
		
		
		echo "</td></tr></table>";
include "../footer.php";



?>