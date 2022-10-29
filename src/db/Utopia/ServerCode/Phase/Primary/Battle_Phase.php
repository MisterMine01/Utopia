/*
 *Utopia phase has always like parameters:
 * $Battle_Classes, $Card_Id=null, $System=null
 */
if ($Battle_Classes->Phase_Sys($Card_Id, $System)) {
	if ($Card_Id!=null) {
		if ($Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["state"] == "Attack") {
			$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["state"] = "Alive";
		} else {
			$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$Card_Id["card"]]["state"] = "Attack";
		}
	} else {
		if ($System=="Pass") {
			$ifattack = 0;
			foreach ($Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"] as $key=>$value) {
				if ($value["state"]=="Attack") {
					$ifattack = 1;
				}
			}
			if ($ifattack==0) {
				$Battle_Classes->Change_Turn();
			} else {
				$def = 0;
				foreach ($Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Board"] as $key => $value) {
					if ($value["state"]=="Alive") {
						foreach ($Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"] as $key1 => $value1) {
							if ($value1["state"]=="Attack") {
								$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$key1]["state"] = "OnAttack";
								break;
							}
						}
						$Battle_Classes->Change_Primary_Phase("Defense");
						$def = 1;
						break;
					}
				}
				if ($def==0) {
					foreach ($Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"] as $key => $value) {
						if ($value["state"] == "Attack") {
							$Battle_Classes->_Battle[$Battle_Classes->_Enemy_Id]["Life"] -= intval($value["att"]);
							$Battle_Classes->_Battle[$Battle_Classes->_Player_Id]["Board"][$key]["state"] = "Alive";
						}
					}
					$Battle_Classes->Change_Turn();
				}
			}
		}
	}
}