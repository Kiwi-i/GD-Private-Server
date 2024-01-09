<?php
	function chkarray($source, $default = 0)
	{
		if ($source == "") {
			$target = $default;
		} else {
			$target = $source;
		}
		return $target;
	}
	$url = "https://gd.zerodestination.cf/gdps/getGJComments21.php";
	$post = ['gameVersion' => '22', 'binaryVersion' => '38', 'uuid'=>'1', 'total'=>'3', 'accountID' => '1', 'mode'=>'0', 'page'=>'0', 'levelID'=>'2', 'secret' => 'Wmfd2893gb7'];
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Cookie:gd=1'
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
	$result = curl_exec($ch);
	echo $result."<br><br><h1>ORIGINAL RESPONSE</h1><br>";
	curl_close($ch);
	if ($result == "" or $result == "-1" or $result == "No no no") {
		if ($result == "") {
			echo "An error has occured while connecting to the server.";
		} else if ($result == "-1") {
			echo "This level doesn't exist.";
		} else {
			echo "RobTop doesn't like you or something...";
		}
		echo "<br>Error code: $result";
	} else if (1 == 1) {
		$rawlists = explode('#', $result)[0];
		$lists = explode('|', $rawlists);

		foreach($lists as $listdata) {
			$resultarray = explode(':', $listdata);
			$levelarray = array();
			$x = 1;
			foreach ($resultarray as &$value) {
				if ($x % 2 == 0) {
					$levelarray["a$arname"] = $value;
				} else {
					$arname = $value;
				}
				$x++;
			}
			foreach($levelarray as $key => $value) {
				echo "<b>".$key.":</b> ".$value."<br>";
			}
			echo "<h2>END OF LIST</h2><br><br>";
		}
	}
?>