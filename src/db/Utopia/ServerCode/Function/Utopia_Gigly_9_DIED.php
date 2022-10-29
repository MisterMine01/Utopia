/*
 * Utopia Function has always like parameters:
 * $BattleClass, $PlayerId, $EnemyId, $BoardKey
 * View BattleClass.php
 */
foreach ($BattleClass->_Battle[$PlayerId]["Board"][$BoardKey]["tags"]["secondary"][0] as $key => $value) {
	$BattleClass->_Battle[$value[0]]["Board"][$value[1]]["att"] += 2;
	$BattleClass->_Battle[$value[0]]["Board"][$value[1]]["def"] += 2;
	
}
/*
foreach ($BattleClass->_Battle[$PlayerId]["Board"] as $key=>$value) {
	if ($value["state"] != "Died") {
		if (in_array("S_tags9", $value["tags"]["secondary"])) {
			$BattleClass->_Battle[$PlayerId]["Board"][$key]["att"] += 2;
			$BattleClass->_Battle[$PlayerId]["Board"][$key]["def"] += 2;
			unset($BattleClass->_Battle[$PlayerId]["Board"][$key]["tags"]["secondary"]
				[array_keys($BattleClass->_Battle[$PlayerId]["Board"][$key]["tags"]["secondary"], "S_tags9")[0]]);
		}
	}
}
foreach ($BattleClass->_Battle[$EnemyId]["Board"] as $key=>$value) {
	if ($value["state"] != "Died") {
		if (in_array("S_tags9", $value["tags"]["secondary"])) {
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["att"] += 2;
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["def"] += 2;
			unset($BattleClass->_Battle[$EnemyId]["Board"][$key]["tags"]["secondary"]
				[array_keys($BattleClass->_Battle[$EnemyId]["Board"][$key]["tags"]["secondary"], "S_tags9")[0]]);
		}
	}
}*/