/*
 *Utopia phase has always like parameters:
 * $Battle_Classes, $Card_Id=null, $System=null
 */
if ($Battle_Classes->Phase_Sys($Card_Id, $System)) {
	if ($Card_Id!=null) {
		if ($Battle_Classes->_Card_Sys[$Card_Id["card"]]["price"] <= $Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Eclat"]) {
			if (in_array($Card_Id["card"], $Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Hand"])) {
				$Battle_Classes->Add_Card($Card_Id["card"]);
			}
		}
	} else {
		if ($System=="Pass") {
			if (count($Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"])==0) {
				$Battle_Classes->Change_Turn();
			} else {
				if (($Battle_Classes->_Battle["Phase"]["Turn"]!=0) and ($Battle_Classes->_Battle["Phase"]["Turn"]!=1)) {
					$Battle_Classes->Change_Primary_Phase("Battle");
				} else {
					$Battle_Classes->Change_Turn();
				}
			}
		}
	}
}