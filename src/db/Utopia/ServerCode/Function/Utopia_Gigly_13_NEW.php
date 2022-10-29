/*
 * Utopia Function has always like parameters:
 * $BattleClass, $PlayerId, $EnemyId, $BoardKey
 * View BattleClass.php
 */
if (count($BattleClass->_Battle[$PlayerId]["Board"])+count($BattleClass->_Battle[$EnemyId]["Board"]) !=0) {
	$ServerData = json_decode(file_get_contents("{$BattleClass->_Battle_Id}/ServerSave.json"), true);
	$ServerData["CreaWin"] = array(
		"att" => -3,
		"def" => 0
	);
	file_put_contents("{$BattleClass->_Battle_Id}/ServerSave.json", json_encode($ServerData));
	$BattleClass->Change_Secondary_Phase("CreaWin");
}