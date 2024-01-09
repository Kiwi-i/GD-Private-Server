<?php
include "../config/security.php";
include "../incl/lib/connection.php";
require_once "../incl/lib/exploitPatch.php";
require "../incl/lib/generatePass.php";

function doheaderstuff() 
{ 
	$str = "";
	foreach ($_SERVER as $name => $value) 
	{ 
		if (substr($name, 0, 5) == 'HTTP_') 
		{ 
			$str .= "<b>". str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) ."</b>: ";
			$str .= $value . "<br><br>";
		} 
	} 
	return $str;
}

//file_put_contents("./registerlog.html", doheaderstuff());

if(!isset($preactivateAccounts)){
	$preactivateAccounts = true;
}

if(isset($_POST["userName"])){
	//here im getting all the data
	$userName = ExploitPatch::remove($_POST["userName"]);
	$password = ExploitPatch::remove($_POST["password"]);
	$email = ExploitPatch::remove($_POST["email"]);
	$secret = "";
	//checking if username is within the GD length limit
	if(strlen($userName) > 20)
		exit("-4");
	//checking if name is taken
	$query2 = $db->prepare("SELECT count(*) FROM accounts WHERE userName LIKE :userName");
	$query2->execute([':userName' => $userName]);
	$regusrs = $query2->fetchColumn();
	if ($regusrs > 0) {
		echo "-2";
	}else{
		$hashpass = password_hash($password, PASSWORD_DEFAULT);
		$gjp2 = GeneratePass::GJP2hash($password);
		$query = $db->prepare("INSERT INTO accounts (userName, password, email, registerDate, activated, gjp2)
		VALUES (:userName, :password, :email, :time, :activated, :gjp)");
		$query->execute([':userName' => $userName, ':password' => $hashpass, ':email' => $email, ':time' => time(), ':activated' => $preactivateAccounts ? 1 : 0, ':gjp' => $gjp2]);
		echo "1";
	}
}
?>
