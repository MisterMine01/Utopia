/*
 * Utopia Function has always like parameters:
 * $BattleClass, $PlayerId, $EnemyId, $BoardKey
 * View BattleClass.php
 */
$gain = 0;
foreach ($BattleClass->_Battle[$PlayerId]["Board"] as $key => $value) {
	if ($value["state"]!="Dead") {
		$gain += $value["def"];
	}
}
$BattleClass->_Battle[$PlayerId]["Life"] += $gain;