<?php
//error_reporting(0);
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();

$gjp2check = isset($_POST['gjp2']) ? $_POST['gjp2'] : $_POST['gjp'];
if(!isset($gjp2check) OR !isset($_POST["rating"]) OR !isset($_POST["levelID"]) OR !isset($_POST["accountID"])){
	exit("-1");
}
$gjp = ExploitPatch::remove($gjp2check);
$rating = ExploitPatch::remove($_POST["rating"]);
$levelID = ExploitPatch::remove($_POST["levelID"]);
$modReq = ExploitPatch::remove($_POST["mode"]);
$id = GJPCheck::getAccountIDOrDie();
$doModeratorVoteReset = false;
if($modReq == '1') {
	if($gs->checkPermission($id, "actionRateDemon") == true){
		$doModeratorVoteReset = true;
	} else {
		exit("-1");
	}
}
$auto = 0;
$demon = 0;
switch($rating){
	case 1:
		$dmn = 1;
		$dmnname = "Easy";
		break;
	case 2:
		$dmn = 2;
		$dmnname = "Medium";
		break;
	case 3:
		$dmn = 3;
		$dmnname = "Hard";
		break;
	case 4:
		$dmn = 4;
		$dmnname = "Insane";
		break;
	case 5:
		$dmn = 5;
		$dmnname = "Extreme";
		break;
}
$timestamp = time();
if($doModeratorVoteReset == true) {
	$query = $db->prepare("UPDATE levels SET demonVotes=:demon, totalDemonVotes='1' WHERE starDemon='1' AND levelID=:levelID");	
	$query->execute([':demon' => $dmn, ':levelID'=>$levelID]);

	if($query->rowCount() == 0) {
		exit("-1");
	}

	$query = $db->prepare("INSERT INTO modactions (type, value, value3, timestamp, account) VALUES ('10', :value, :levelID, :timestamp, :id)");
	$query->execute([':value' => $dmnname, ':timestamp' => $timestamp, ':id' => $id, ':levelID' => $levelID]);
} else {
	$query = $db->prepare("UPDATE levels SET demonVotes=demonVotes+:demon, totalDemonVotes=totalDemonVotes+'1' WHERE levelID=:levelID AND starDemon='1'");	
	$query->execute([':demon' => $dmn, ':levelID'=>$levelID]);

	if($query->rowCount() == 0) {
		exit("-1");
	}
}
echo $levelID;
?>
