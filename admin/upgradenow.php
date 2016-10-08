<?php
include "admincontrol.php";
include "../header.php";
include "../style.php";

$userid = $_POST['userid'];
$memtype = $_POST['memtype'];

    	?><table>
      	<tr>
        <td width="15%" valign=top><br>
        <? include("adminnavigation.php"); ?>
        </td>
        <td valign="top" align="center"><br><br> <?
    	echo "<font size=2 face='$fonttype' color='$fontcolour'><p><b><center>";

	       if ($memtype == "SUPER JV") {


				upgrade_superjv($userid,"no","");
                echo $userid." has been upgraded successfully.";


	        } elseif ($memtype == "JV MEMBER") {


				upgrade_jv($userid,"no","");
                echo $userid." has been upgraded successfully.";


	        }


            else {


	            $query3 = "update members set memtype='$memtype' where userid='".$userid."'";


	            $result3 = mysql_query ($query3);


                echo $userid." has been downgraded successfully.";


            }    echo "</td></tr></table>";
include "../footer.php";
mysql_close($dblink);
?>