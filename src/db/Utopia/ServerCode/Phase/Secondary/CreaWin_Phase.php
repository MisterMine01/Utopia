/*
 *Utopia phase has always like parameters:
 * $Battle_Classes, $Card_Id=null, $System=null
 */
if ($Battle_Classes->Phase_Sys($Card_Id, $System)) {
	if ($Card_Id != null) {
		$ServerData = json_decode(file_get_contents("{$Battle_Classes->_Battle_Id}/ServerSave.json"), true);
		if ($Card_Id["board_id"] == 1) {
			$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"][$Card_Id["card"]]["def"] += $ServerData["CreaWin"]["def"];
			$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"][$Card_Id["card"]]["att"] += $ServerData["CreaWin"]["att"];
		} else {
			
			$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["def"] += $ServerData["CreaWin"]["def"];
			$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["att"] += $ServerData["CreaWin"]["att"];
		}
		unset($ServerData["CreaWin"]);
		file_put_contents("{$Battle_Classes->_Battle_Id}/ServerSave.json", json_encode($ServerData));
		$Battle_Classes->Finish_Secondary_Phase();
	}
}