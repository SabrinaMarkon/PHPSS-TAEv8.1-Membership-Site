<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";

$userid = $_POST['userid'];

    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td  valign="top" align="center"><br><br> <?

    	echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>"; ?>
	        <center>
            <p>Upgrade (or downgrade) a member</p>
	        <form method="POST" action="upgradenow.php">
	        Userid:<br><input type="text" name="userid" maxLength="50" size="40" value=""><br>
            <select name="memtype">
			<option value="PRO"><?php echo $lowerlevel ?></option>
	        <option value="JV MEMBER"><?php echo $middlelevel ?></option>
            <option value="SUPER JV"><?php echo $toplevel ?></option>
	        </select>
			<br>
            <input type="submit" value="Save">
	        </form>
	        </center>
            </td>
     </tr>
     </table>
	    <?
include "../footer.php";
mysql_close($dblink);
?>