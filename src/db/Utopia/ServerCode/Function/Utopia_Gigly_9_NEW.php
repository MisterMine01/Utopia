/*
 * Utopia Function has always like parameters:
 * $BattleClass, $PlayerId, $EnemyId, $BoardKey
 * View BattleClass.php
 */
foreach ($BattleClass->_Battle[$PlayerId]["Board"] as $key=>$value) {
	if ($value["state"] != "Died") {
		if (!in_array(array($PlayerId, $key), $BattleClass->_Battle[$PlayerId]["Board"][$BoardKey]["tags"]["secondary"][0])) {
			$BattleClass->_Battle[$PlayerId]["Board"][$key]["att"] -= 2;
			$BattleClass->_Battle[$PlayerId]["Board"][$key]["def"] -= 2;
			$BattleClass->_Battle[$PlayerId]["Board"][$BoardKey]["tags"]["secondary"][0][] = array($PlayerId, $key);
		}
	}
}
foreach ($BattleClass->_Battle[$EnemyId]["Board"] as $key=>$value) {
	if ($value["state"] != "Died") {
		if (!in_array(array($EnemyId, $key), $BattleClass->_Battle[$EnemyId]["Board"][$BoardKey]["tags"]["secondary"][0])) {
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["att"] -= 2;
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["def"] -= 2;
			$BattleClass->_Battle[$EnemyId]["Board"][$BoardKey]["tags"]["secondary"][0][] = array($EnemyId, $key);
		}
	}
}
/*
foreach ($BattleClass->_Battle[$BattleClass->_Enemy_Id]["Board"] as $key=>$value) {
	if ($value["state"] != "Died") {
		if (!in_array("S_tags9", $value["tags"]["secondary"])) {
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["att"] -= 2;
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["def"] -= 2;
			$BattleClass->_Battle[$EnemyId]["Board"][$key]["tags"]["secondary"][] = "S_tags9";
		}
	}
}*/