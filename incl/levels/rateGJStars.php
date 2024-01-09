<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();
$gjp2check = isset($_POST['gjp2']) ? $_POST['gjp2'] : $_POST['gjp'];
$gjp = ExploitPatch::remove($gjp2check);
$stars = ExploitPatch::remove($_POST["stars"]);
$levelID = ExploitPatch::remove($_POST["levelID"]);
$accountID = GJPCheck::getAccountIDOrDie();

$query = $db->prepare("UPDATE levels SET ratingVotes=ratingVotes+:stars, totalVotes=totalVotes+1 WHERE starStars='0' AND levelID=:levelID");	
$query->execute([':stars' => $stars, ':levelID'=>$levelID]);

if($query->rowCount() == 0) {
	exit("-1");
}

echo $levelID;