<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";
    $query = "select * from pages";
	$result = mysql_query ($query)
	     or die ("Insert failed");

    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td valign="top" align="center"><br><br> <?
    echo "<font size=2 face='$fonttype' color='$fontcolour'><p><center>";

    while ($line = mysql_fetch_array($result)) {
        $index = $line["id"];
        $name = $line["name"];
        $htmlcode = $line["htmlcode"];

        echo "<b>" . $name . "</b>";
    	?>
    	<center>
		<form method="POST" action="editpagenow.php">
        <input type="hidden" name="index" value="<? echo $index; ?>">
		<textarea rows=10 cols=50 name="htmlcode"><? echo $htmlcode; ?></textarea><br>
		<input type="submit" value="Save Changes">
		</form>
		</center>
    	<?
    	}
    ?>
    </center>
    </td>
    </tr>
    </table>
	<?
include "../footer.php";
mysql_close($dblink);
?>