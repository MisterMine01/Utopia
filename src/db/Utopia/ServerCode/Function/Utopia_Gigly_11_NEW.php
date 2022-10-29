/*
 * Utopia Function has always like parameters:
 * $BattleClass, $PlayerId, $EnemyId, $BoardKey
 * View BattleClass.php
 */
$ServerData = json_decode(file_get_contents("{$BattleClass->_Battle_Id}/ServerSave.json"), true);
$gain = 0;
foreach ($BattleClass->_Battle[$PlayerId]["Board"] as $key => $value) {
	if ($value["state"]!="Dead") {
		$gain += 1;
	}
}
$gain-=2;
if (count($BattleClass->_Battle[$PlayerId]["Board"])+count($BattleClass->_Battle[$EnemyId]["Board"]) !=0) {
	$ServerData["Damage_status"] = $gain*2;
	file_put_contents("{$BattleClass->_Battle_Id}/ServerSave.json", json_encode($ServerData));
	$BattleClass->Change_Secondary_Phase("Damage");
}