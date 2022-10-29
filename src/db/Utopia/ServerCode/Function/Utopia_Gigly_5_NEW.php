/*
 * Utopia Function has always like parameters:
 * $BattleClass, $PlayerId, $EnemyId, $BoardKey
 * View BattleClass.php
 */
$gain = 0;
foreach ($BattleClass->_Battle[$PlayerId]["Board"] as $key => $value) {
	if ($value["state"]!="Dead") {
		$gain += 1;
	}
}
$BattleClass->_Battle[$PlayerId]["Eclatperturn"] += $gain;