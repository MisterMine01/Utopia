<?php
include "BattleClass.php";

function GetBattle() {
	$data = new Battle($_POST["PlayerId"], $_POST["BattleId"]);
	return json_encode($data->BattleReturn());
}


function SendBattle() {
	$data = new Battle($_POST["PlayerId"], $_POST["BattleId"]);
	$key = array();
	if (isset($_POST["CardId"])) {
		$key["Card_Id"] = json_decode($_POST["CardId"], true);
	} else {
		$key["Card_Id"] = null;
	}
	if (isset($_POST["System"])) {
		if ($_POST["System"]=="null") {
			$key["System"] = json_decode($_POST["System"], true);
		} else {
			$key["System"] = $_POST["System"];
		}
	} else {
		$key["System"] = null;
	}
	$data->BattleSend($key["Card_Id"], $key["System"]);
	$data->SaveBattle();
	return json_encode(array());
}

?>