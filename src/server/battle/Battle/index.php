<?php
error_reporting(E_ERROR);
include "BattleStarter.php";
include "BattleSystem.php";
if (isset($_GET["MM1_jc"])) {
	if ($_GET["MM1_jc"]=="200") {
		echo file_get_contents("ReturnSystem.json");
	}
}

$DATA = json_decode(file_get_contents("ReturnSystem.json"), true);

$reset = array("reset", "restart", "reload");
foreach ($reset as $value) {
	if (isset($_GET[$value]) && $_GET[$value]=="200") {
			echo resetBattle($_GET["BattleId"]);
			break;
	}
}

foreach ($DATA as $key => $value) {
	$test = 0;
	foreach ($value["GET"] as $key0 => $value0) {
		if (isset($_GET[$key0])) {
			if ($_GET[$key0] == $value0) {
				$test=$test+1;
			}
		}
	}
	if ($test==count($value0["GET"])) {
		$test2 = 0;
		foreach ($value["POST"] as $key0) {
			if (isset($_POST[$key0])) {
				$test2 = $test2+1;
			}
		}
		if ($test2==count($value["POST"])) {
			echo $value["PHP_function"]();
		}
	}
}
