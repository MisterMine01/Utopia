<?php

class Battle
{
	public $_Battle;
	public $_Player_Id;
	public $_Enemy_Id;
	public $_Card_Sys;
	public $_Battle_Id;
	public $_phase;

	function __construct($Player_Id, $BattleId)
	{
		$this->_Battle_Id = $BattleId;
		$this->_Battle = json_decode(file_get_contents("{$this->_Battle_Id}/BattleSave.json"), true);
		$this->BddFolder = json_decode(file_get_contents("../properties.json"), true)["BddFolder"];
		$this->_Card_Sys = json_decode(file_get_contents("../{$this->BddFolder}/data.json"), true)["Card"];
		$this->_Player_Id = $Player_Id;
		$this->_phase = json_decode(file_get_contents("../{$this->BddFolder}/PrimaryPhase.json"), true);
		foreach (json_decode(file_get_contents("../{$this->BddFolder}/SecondaryPhase.json"), true) as $key => $value) {
			$this->_phase[$key] = $value;
		}

		$keys = array_keys(json_decode(file_get_contents("{$this->_Battle_Id}/wait.json"), true));
		if ($keys[0] == $this->_Player_Id) {
			$this->_Enemy_Id = $keys[1];
		} else {
			$this->_Enemy_Id = $keys[0];
		}
	}

	function pioche($PlayerId)
	{
		$wait = json_decode(file_get_contents("{$this->_Battle_Id}/wait.json"), true);
		$deck = json_decode(file_get_contents("{$this->_Battle_Id}/{$PlayerId}_Deck.json"), true);
		$wait[$PlayerId]["nbDraw"] += 1;
		$this->_Battle[$PlayerId]["Hand"][] = $deck[$wait[$PlayerId]["nbDraw"]];
		file_put_contents("{$this->_Battle_Id}/wait.json", json_encode($wait));
	}

	function Add_Card($Card_Id)
	{
		unset($this->_Battle[$this->_Player_Id]["Hand"][array_keys($this->_Battle[$this->_Player_Id]["Hand"], $Card_Id)[0]]);
		$this->_Battle[$this->_Player_Id]["Hand"] = array_values($this->_Battle[$this->_Player_Id]["Hand"]);
		$this->_Battle[$this->_Player_Id]["Eclat"] = $this->_Battle[$this->_Player_Id]["Eclat"] - intval($this->_Card_Sys[$Card_Id]["price"]);
		$this->_Battle[$this->_Player_Id]["Board"][] = array(
			"state" => "Alive",
			"att" => intval($this->_Card_Sys[$Card_Id]["att"]),
			"def" => intval($this->_Card_Sys[$Card_Id]["def"]),
			"Id" => $Card_Id,
			"tags" => array(
				"primary" => $this->_Card_Sys[$Card_Id]["tags"]["primary"],
				"secondary" => $this->_Card_Sys[$Card_Id]["tags"]["secondary"]
			)
		);
		$this->Load_Card_Function("new", $Card_Id, $this->_Player_Id, count($this->_Battle[$this->_Player_Id]["Board"])-1);
	}

	function Load_Card_Function($func_type, $Card_Id, $PlayerId, $Board_key)
	{
		include_once "1_Server_Function.php";
		include_once "../{$this->BddFolder}/0_User_Function.php";
		if ($PlayerId == $this->_Enemy_Id) {
			$this->_Card_Sys[$Card_Id]["func"][$func_type]($this, $PlayerId, $this->_Player_Id, $Board_key);
		} else {
			$this->_Card_Sys[$Card_Id]["func"][$func_type]($this, $PlayerId, $this->_Enemy_Id, $Board_key);
		}
	}

	function Test_Card_Die()
	{
		$data = array();
		foreach ($this->_Battle as $key => $value) {
			if ($key != "Phase") {
				foreach ($this->_Battle[$key]["Board"] as $key1 => $value1) {
					if ($this->_Battle[$key]["Board"][$key1]["def"] <= 0 and $this->_Battle[$key]["Board"][$key1]["state"] != "Dead") {
						$this->_Battle[$key]["Board"][$key1]["state"] = "Dead";
						$this->Load_Card_Function("died", $this->_Battle[$key]["Board"][$key1]["Id"], $key, $key1);
					}
				}
			}
		}
	}

	function Phase_Sys($Card_Id = null, $System = null)
	{
		if (($Card_Id == null and $System == null) or ($Card_Id != null and $System != null)) {
			return false;
		} else {
			return true;
		}
	}

	function SaveBattle()
	{
		file_put_contents("{$this->_Battle_Id}/BattleSave.json", json_encode($this->_Battle));
	}

	function BattleReturn()
	{
		$Battle = json_decode(file_get_contents("{$this->_Battle_Id}/BattleSave.json"), true);
		$Battle[$this->_Enemy_Id]["Hand"] = count($Battle[$this->_Enemy_Id]["Hand"]);
		return $Battle;
	}

	function BattleSend($Card_Id = null, $System = null)
	{
		foreach ($this->_phase as $key => $value) {
			if ($this->_Battle["Phase"]["Phase"] == $key) {
				$function = $value["function"];
				$user_play = $value["Phase_User"];
			}
		}
		if (
			($user_play == 0 && $this->_Battle["Phase"]["PlayerId"] == $this->_Player_Id)
			|| ($user_play == 1 && $this->_Battle["Phase"]["PlayerId"] != $this->_Player_Id)
		) {
			include_once "../{$this->BddFolder}/Primary_Phase.php";
			include_once "../{$this->BddFolder}/Secondary_Phase.php";
			$function($this, $Card_Id, $System);
			foreach ($this->_Battle[$this->_Enemy_Id]["Board"] as $key => $value) {
				if ($value["state"] != "Dead") {
					$this->Load_Card_Function("eachSend", $value["Id"], $this->_Enemy_Id, $key);
				}
			}
			foreach ($this->_Battle[$this->_Player_Id]["Board"] as $key => $value) {
				if ($value["state"] != "Dead") {
					$this->Load_Card_Function("eachSend", $value["Id"], $this->_Player_Id, $key);
				}
			}
			$this->Test_Card_Die();
		}
	}

	function Change_Primary_Phase($phase_name)
	{
		if (in_array($phase_name, array_keys($this->_phase))) {
			$this->_Battle["Phase"]["Phase"] = $phase_name;
			$this->_Battle["Phase"]["PhaseType"] = $this->_phase[$phase_name]["type"];
			$this->_Battle["Phase"]["PhaseUser"] = $this->_phase[$phase_name]["Phase_User"];
			$ServerData = json_decode(file_get_contents("{$this->_Battle_Id}/ServerSave.json"), true);
			$ServerData["phase"] = [];
			file_put_contents("{$this->_Battle_Id}/ServerSave.json", json_encode($ServerData));
		}
	}

	function Change_Secondary_Phase($phase_name)
	{
		$Sphase_data = json_decode(file_get_contents("../{$this->BddFolder}/SecondaryPhase.json"), true);
		if (in_array($phase_name, array_keys($Sphase_data))) {
			$ServerData = json_decode(file_get_contents("{$this->_Battle_Id}/ServerSave.json"), true);
			$ServerData["phase"][] = $this->_Battle["Phase"]["Phase"];
			$this->_Battle["Phase"]["Phase"] = $phase_name;
			$this->_Battle["Phase"]["PhaseType"] = $Sphase_data[$phase_name]["type"];
			$this->_Battle["Phase"]["PhaseUser"] = $Sphase_data[$phase_name]["Phase_User"];
			file_put_contents("{$this->_Battle_Id}/ServerSave.json", json_encode($ServerData));
		}
	}

	function Finish_Secondary_Phase()
	{
		$ServerData = json_decode(file_get_contents("{$this->_Battle_Id}/ServerSave.json"), true);
		if (count($ServerData["phase"]) != 0) {
			$this->_Battle["Phase"]["Phase"] = $ServerData["phase"][count($ServerData) - 1];
			$this->_Battle["Phase"]["PhaseType"] = $this->_phase[$ServerData["phase"][count($ServerData) - 1]]["type"];
			$this->_Battle["Phase"]["PhaseUser"] = $this->_phase[$ServerData["phase"][count($ServerData) - 1]]["Phase_User"];
			unset($ServerData["phase"][-1]);
			file_put_contents("{$this->_Battle_Id}/ServerSave.json", json_encode($ServerData));
		}
	}

	function Change_Turn()
	{
		$this->Change_Primary_Phase("Main");
		if ($this->_Battle["Phase"]["PlayerId"] == $this->_Player_Id) {
			foreach ($this->_Battle[$this->_Player_Id]["Board"] as $key => $value) {
				$this->Load_Card_Function("endTurn", $value["Id"], $this->_Player_Id, $key);
			}
			$this->_Battle["Phase"]["Turn"] = $this->_Battle["Phase"]["Turn"] + 1;
			$this->_Battle["Phase"]["PlayerId"] = $this->_Enemy_Id;
			$this->_Battle[$this->_Enemy_Id]["Eclat"] += $this->_Battle[$this->_Enemy_Id]["Eclatperturn"] + round($this->_Battle["Phase"]["Turn"] / 2.0, 0, PHP_ROUND_HALF_DOWN);
			$this->pioche($this->_Enemy_Id);
			foreach ($this->_Battle[$this->_Enemy_Id]["Board"] as $key => $value) {
				$this->Load_Card_Function("startTurn", $value["Id"], $this->_Enemy_Id, $key);
			}
		} else {

			foreach ($this->_Battle[$this->_Enemy_Id]["Board"] as $key => $value) {
				$this->Load_Card_Function("endTurn", $value["Id"], $this->_Enemy_Id, $key);
			}

			$this->_Battle["Phase"]["Turn"] = $this->_Battle["Phase"]["Turn"] + 1;
			$this->_Battle["Phase"]["PlayerId"] = $this->_Player_Id;
			$this->_Battle[$this->_Player_Id]["Eclat"] += $this->_Battle[$this->_Player_Id]["Eclatperturn"] + round($this->_Battle["Phase"]["Turn"] / 2.0, 0, PHP_ROUND_HALF_DOWN);
			$this->pioche($this->_Player_Id);


			foreach ($this->_Battle[$this->_Player_Id]["Board"] as $key => $value) {
				$this->Load_Card_Function("startTurn", $value["Id"], $this->_Player_Id, $key);
			}
		}
	}
}
