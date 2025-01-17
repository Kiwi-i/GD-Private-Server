<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();
$accountID = GJPCheck::getAccountIDOrDie();
$levelID = ExploitPatch::remove($_POST["levelID"]);
$scores = [];
$scores['time'] = ExploitPatch::number($_POST["time"]);
$scores['points'] = ExploitPatch::number($_POST["points"]);
$uploadDate = time();
$lvlstr = '';
$mode = ExploitPatch::number($_POST['mode']) == 1 ? 'points' : 'time';
//UPDATING SCORE
$query2 = $db->prepare("SELECT {$mode} FROM platscores WHERE accountID = :accountID AND levelID = :levelID");
$query2->execute([':accountID' => $accountID, ':levelID' => $levelID]);
$oldPercent = $query2->fetchColumn();
if($query2->rowCount() == 0) {
	$query = $db->prepare("INSERT INTO platscores (accountID, levelID, {$mode}, timestamp) VALUES (:accountID, :levelID, :{$mode}, :timestamp)");
} else {
	if(($mode == "time" AND $oldPercent > $scores['time'] AND $scores['time'] > 0) OR ($mode == "points" AND $oldPercent < $scores['points'] AND $scores['points'] > 0)) {
		$query = $db->prepare("UPDATE platscores SET {$mode}=:{$mode}, timestamp=:timestamp WHERE accountID=:accountID AND levelID=:levelID");
	} else {
		$query = $db->prepare("SELECT count(*) FROM platscores WHERE {$mode}=:{$mode} AND timestamp=:timestamp AND accountID=:accountID AND levelID=:levelID");
	}
}
$query->execute([':accountID' => $accountID, ':levelID' => $levelID, ":{$mode}" => $scores[$mode], ':timestamp' => $uploadDate]);
//GETTING SCORES
if(!isset($_POST["type"])){
	$type = 1;
}else{
	$type = $_POST["type"];
}
switch($type){
	case 0:
		$friends = $gs->getFriends($accountID);
		$friends[] = $accountID;
		$friends = implode(",",$friends);
		$query2 = $db->prepare("SELECT * FROM platscores WHERE levelID = :levelID AND accountID IN ($friends) ORDER BY {$mode} DESC");
		$query2args = [':levelID' => $levelID];
		break;
	case 1:
		$query2 = $db->prepare("SELECT * FROM platscores WHERE levelID = :levelID ORDER BY {$mode} DESC");
		$query2args = [':levelID' => $levelID];
		break;
	case 2:
		$query2 = $db->prepare("SELECT * FROM platscores WHERE levelID = :levelID AND timestamp > :time ORDER BY {$mode} DESC");
		$query2args = [':levelID' => $levelID, ':time' => $uploadDate - 604800];
		break;
	default:
		return -1;
		break;
}
$query2->execute($query2args);
$result = $query2->fetchAll();
$x = 0;
foreach ($result as &$score) {
	$extID = $score["accountID"];
	$query2 = $db->prepare("SELECT userName, userID, icon, color1, color2, color3, iconType, special, extID, banned FROM users WHERE extID = :extID");
	$query2->execute([':extID' => $extID]);
	$user = $query2->fetchAll();
	$user = $user[0];
	if($user["banned"] != 0) continue;
	$x++;
	$time = date("d/m/Y G.i", $score["timestamp"]);
	$scoreType = $score[$mode];
	$lvlstr .= "1:{$user['userName']}:2:{$user['userID']}:9:{$user['icon']}:10:{$user['color1']}:11:{$user['color2']}:14:{$user['iconType']}:15:{$user['color3']}:16:{$extID}:3:{$scoreType}:6:{$x}:42:{$time}|";
}
echo substr($lvlstr, 0, -1);
?>
