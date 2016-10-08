<?php 
if(!isset($_SESSION))
{
session_start();
}
include "../config.php";
if ($_REQUEST["Adminid"])
{
$_SESSION["Adminid"] = $_REQUEST["Adminid"];
$_SESSION["Password"] = $_REQUEST["Password"];
}
if(($_SESSION["Password"] != $adminpw) || ($_SESSION["Adminid"] != $adminid))
	{
	unset($_SESSION["Adminid"]);
	unset($_SESSION["Password"]);
	include "../header.php";
	include "../style.php"; 
    echo "Error. Wrong Admin Login.";
	include "../footer.php";
    exit;
	}
extract($_REQUEST);
?>