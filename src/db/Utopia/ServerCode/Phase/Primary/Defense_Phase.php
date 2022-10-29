/*
 *Utopia phase has always like parameters:
 * $Battle_Classes, $Card_Id=null, $System=null
 */
foreach ($Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"] as $key => $value) {
	if ($value["state"]=="OnAttack") {
		break;
	}
}
if ($Battle_Classes->Phase_Sys($Card_Id, $System)) {
	if ($Card_Id!=null) {
		if ($Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["state"] != "Alive") {
			return;
		}
		$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"][$key]["def"] -= $Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["att"];
		$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["def"] -= $value["att"];
		$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"][$key]["state"] = "Alive";
	} else {
		if ($System=="Pass") {
			$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Life"] -= intval($value["att"]);
			$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"][$key]["state"] = "Alive";
		}
	}
	$reattack = 0;
	foreach ($Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"] as $key => $value) {
		if ($value["state"] =="Attack") {
			$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"][$key]["state"] = "OnAttack";
			$reattack = 1;
			break;
		}
	}
	if ($reattack == 0) {
		$Battle_Classes->Change_Turn();
	}
}