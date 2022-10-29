<?php
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
//error_reporting(E_ERROR);
include "Utopia.php";
if (isset($_GET["MM1_jc"])) {
	if ($_GET["MM1_jc"]=="200") {
		echo file_get_contents("ReturnSystem.json");
	}
}

$DATA = json_decode(file_get_contents("ReturnSystem.json"), true);

foreach ($DATA as $key => $value) {
	$test = 0;
	foreach ($value["GET"] as $key0 => $value0) {
		if (isset($_GET[$key0])) {
			if ($_GET[$key0] == $value0) {
				$test=$test+1;
			}
		}
	}
	if ($test==count($value["GET"])) {
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
?>