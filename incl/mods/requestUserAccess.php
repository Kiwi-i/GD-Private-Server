<?php
chdir(dirname(__FILE__));
//error_reporting(0);
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php"; //this is connection.php too
$gs = new mainLib();

$accountID = GJPCheck::getAccountIDOrDie();

$permState = $gs->getMaxValuePermission($accountID,"modBadgeLevel"); // gets their current mod level
// checks mod badge level so it knows what to show
if($permState <= 0) { // Fail for non-mods
	exit("-1");
}		   
if ($permState == 3){ // Give moderator message for lb mods
	exit("1");
} else if ($permState > 3){ // Default to elder mod for anything else
	exit("2");
}
echo $permState; 
?>
