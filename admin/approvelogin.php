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
		mysql_query ("update loginads set added=0 where id=".$each);
		}
		
        echo "<p><center>The login ads have been sent back to the users.</p></center>";
		} else {

		foreach($id as $each) {
		mysql_query ("update loginads set approved=1 where id=".$each);
		}
		
        echo "<p><center>The login ads have been approved.</p></center>";
		}
		
		
		echo "</td></tr></table>";

include "../footer.php";

?>