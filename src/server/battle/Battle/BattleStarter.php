<?php
function load_battle($BattleId) {
    if (!is_dir($BattleId)) {
        mkdir($BattleId);
        file_put_contents("{$BattleId}/BattleSave.json", json_encode(array()));
        file_put_contents("{$BattleId}/SS.json", json_encode(array("System"=>"0")));
        file_put_contents("{$BattleId}/wait.json", json_encode(array()));
		file_put_contents("{$BattleId}/ServerSave.json", json_encode(array()));
    }
}


function start() {
    load_battle($_POST["BattleId"]);
	$SS = json_decode(file_get_contents("{$_POST["BattleId"]}/SS.json"), true);
	if ($SS["System"]=="0") {
		$SS["System"] = "1";
		file_put_contents("{$_POST["BattleId"]}/SS.json", json_encode($SS));
		$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
		$wait[$_POST["PlayerId"]] = array("ClientVersion"=>$_POST["ClientVersion"], "BddVersion"=>$_POST["BddVersion"]);
		file_put_contents("{$_POST["BattleId"]}/wait.json", json_encode($wait));
		return json_encode(array("System"=>"Wait"));
	} elseif ($SS["System"]=="1") {
		$SS["System"] = "2";
		$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
		if (isset($wait[$_POST["PlayerId"]])) {
			return json_encode(array("System"=>"Wait"));
		} else {
			file_put_contents("{$_POST["BattleId"]}/SS.json", json_encode($SS));
			$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
			$wait[$_POST["PlayerId"]] = array("ClientVersion"=>$_POST["ClientVersion"], "BddVersion"=>$_POST["BddVersion"]);
			file_put_contents("{$_POST["BattleId"]}/wait.json", json_encode($wait));
			return json_encode(array("System"=>"Wait"));
		}
	} else {
		$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
		if (isset($wait[$_POST["PlayerId"]])) {
			return json_encode(array("System"=>"Wait"));
		} else {
			return json_encode(array("System"=>"Party Full"));
		}
	}
}
function wait() {
	$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
	if (count($wait)==2) {
		if (isset($wait[$_POST["PlayerId"]])) {
			$keys = array_keys($wait);
			if ($wait[$keys[0]]["ClientVersion"]==$wait[$keys[1]]["ClientVersion"]) {
				if ($wait[$keys[0]]["BddVersion"]==$wait[$keys[1]]["BddVersion"]) {
					if (isset($wait[$keys[0]]["Random"])==False) {
						$wait[$keys[0]]["Random"] = rand(0,1000);
						$wait[$keys[1]]["Random"] = rand(0,1000);
						if ($wait[$keys[0]]["Random"]==$wait[$keys[1]]["Random"]) {
							return wait();
						} else {
							file_put_contents("{$_POST["BattleId"]}/wait.json", json_encode($wait));
							if ($wait[$keys[0]]["Random"]>$wait[$keys[1]]["Random"]) {
								if ($keys[0]==$_POST["PlayerId"]) {
									return json_encode(array("Turn"=>"Begin"));
								} else {
									return json_encode(array("Turn"=>"Waiting"));
								}
							} else {
								if ($keys[0]==$_POST["PlayerId"]) {
									return json_encode(array("Turn"=>"Waiting"));
								} else {
									return json_encode(array("Turn"=>"Begin"));
								}
							}
						}
					} else {
						if ($wait[$keys[0]]["Random"]>$wait[$keys[1]]["Random"]) {
							if ($keys[0]==$_POST["PlayerId"]) {
								return json_encode(array("Turn"=>"Begin"));
							} else {
								return json_encode(array("Turn"=>"Waiting"));
							}
						} else {
							if ($keys[0]==$_POST["PlayerId"]) {
								return json_encode(array("Turn"=>"Waiting"));
							} else {
								return json_encode(array("Turn"=>"Begin"));
							}
						}
					}
				} else {
					return json_encode(array("Error"=>"BddVersion"));
				}
			} else {
				return json_encode(array("Error"=>"ClientVersion"));
			}
		}		
	} else {
		return json_encode(array("Error"=>"Wait"));
	}
}
function Ennemi_Id($Id) {
	$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
	$Ennemi = array_keys($wait);
	if ($Ennemi[0]==$Id) {
		return $Ennemi[1];
	} else {
		return $Ennemi[0];
	}
}

function DeckSend() {
	$wait = json_decode(file_get_contents("{$_POST["BattleId"]}/wait.json"), true);
	if (isset($wait[$_POST["PlayerId"]])) {
		$deck = json_decode($_POST["Deck"], true);
		shuffle($deck);
		$Ennemi = Ennemi_Id($_POST["PlayerId"]);
		file_put_contents("{$_POST["BattleId"]}/{$_POST["PlayerId"]}_Deck.json", json_encode($deck));
		while (is_file("{$_POST["BattleId"]}/{$Ennemi}_Deck.json")==False) {}
		if ($wait[$_POST["PlayerId"]]["Random"]>$wait[$Ennemi]["Random"]) {
			
			$wait[$_POST["PlayerId"]]["nbDraw"] = 7;
			$hand = array();
			
			$wait[$Ennemi]["nbDraw"] = 7;
			$ennemiDeck = json_decode(file_get_contents("{$_POST["BattleId"]}/{$Ennemi}_Deck.json"), true);
			$handEnnemi = array();
			
			for ($i=0; $i<7; $i++) {
				$handEnnemi[] = $ennemiDeck[$i];
				$hand[] = $deck[$i];
			}
			
			$Battle = array(
					$_POST["PlayerId"]=>array(
						"Hand"=>$hand,
						"Board"=>array(),
						"Life"=>20,
						"Eclat"=>1,
						"Eclatperturn"=>1
					),
					$Ennemi=>array(
						"Hand"=>$handEnnemi,
						"Board"=>array(),
						"Life"=>20,
						"Eclat"=>0,
						"Eclatperturn"=>1
					),
					"Phase"=>array(
						"PlayerId"=>$_POST["PlayerId"],
						"Phase"=>"Main",
						"Turn"=>0,
                        "PhaseType"=>0,
						"PhaseUser"=>0
					)
			);
			file_put_contents("{$_POST["BattleId"]}/BattleSave.json", json_encode($Battle));
			file_put_contents("{$_POST["BattleId"]}/ServerSave.json", json_encode(array("phase" => array())));
			
			$return = array(
							$_POST["PlayerId"]=>array(
								"Hand"=>$hand,
								"Board"=>array(),
								"Life"=>20,
								"Eclat"=>1,
								"Eclatperturn"=>1
							),
							$Ennemi=>array(
								"Hand"=>count($Battle[$Ennemi]["Hand"]),
								"Board"=>array(),
								"Life"=>20,
								"Eclat"=>0,
								"Eclatperturn"=>1
							),
							"Phase"=>array(
								"PlayerId"=>$_POST["PlayerId"],
								"Phase"=>"Main",
                                "Turn"=>0,
                                "PhaseType"=>0,
								"PhaseUser"=>0
							)
					);
			file_put_contents("{$_POST["BattleId"]}/wait.json", json_encode($wait));
			return json_encode($return);
		} else {
			sleep(5);
			$Battle = json_decode(file_get_contents("{$_POST["BattleId"]}/BattleSave.json"), true);
			$Battle[$Ennemi]["Hand"] = count($Battle[$Ennemi]["Hand"]);
			return json_encode($Battle);
		}
	}
}


function resetBattle() {
    if (is_dir($_GET["BattleId"])) {
        if ($handle = opendir($_GET["BattleId"])) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    unlink("{$_GET["BattleId"]}/{$entry}");
                }
            }
            closedir($handle);
        }
        rmdir($_GET["BattleId"]);
		return json_encode(array("reset"=>"Accept"));
    }
}

?>