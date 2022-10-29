<?php

function test_connection($token, $b_token) {
	$data = json_decode(file_get_contents("operative.json"), true);
	foreach ($data as $key=>$value) {
		if (
			$token == $value["Token"] &&
			$b_token == $value["B-Token"] &&
			$value["B-Death"] <= time()) {
				$data[$key]["B-Token"] = time()+300;
				file_put_contents("operative.json", json_encode($data));
				return true;
		}
	}
	return false;
}

function ping() {
	if (test_connection($_POST["Token"], $_POST["B-Token"])) {
		return json_encode(array("Begin"));
	}
	return json_encode(array("Error"=>"You're not allowed"));
}

function get_properties($key) {
    return json_decode(file_get_contents("properties.json"), true)[$key];
}

function GetServerId() {
	return json_encode(array("ServId"=>get_properties("ServerId")));
}
function GetVersion() {
    $Folder = get_properties("BddFolder");
	return file_get_contents("{$Folder}/v.json");
}
function SendUtopia() {
    $Folder = get_properties("BddFolder");
	return file_get_contents("{$Folder}/data.json");
}
function SendImage() {
	$Folder = get_properties("BddFolder");
	$data = json_decode(SendUtopia(), true);
	if (in_array($_POST["idImage"], array_keys($data["Card"]))) {
		$card_data = $data["Card"][$_POST["idImage"]];
		$rarity_data = $data["Rarity"][$card_data["rarity"]];
		$name = $card_data["name"][$_POST["language"]];
		$desc = $card_data["description"][$_POST["language"]];
		$price = $card_data["price"];
		
		$layers = array();
		$head_index = null;
		foreach ($rarity_data as $value) {
			if ($value == "head") {
				$head = "{$Folder}/head/{$_POST["idImage"]}.png";
				if (file_exists($head)) {
					$layers[] = imagecreatefrompng($head);
				} else {
					$layers[] = imagecreatefrompng("{$Folder}/head/error.png");
				}
				$head_index = count($layers)-1;
			} else {
				$layers[] = imagecreatefrompng("{$Folder}/rarity/{$value}");
			}
		}
		for ($i = 1; $i != count($layers); $i++) {
			if ($i == $head_index) {
				if ((imagesx($layers[0]) == imagesx($layers[$i])) and (imagesy($layers[0]) == imagesy($layers[$i]))) {
					imagecopy($layers[0], $layers[$i], 0, 0, 0, 0, 1200, 1700);
				} else {
					imagecopy($layers[0], $layers[$i], 100, 104, 0, 0, 1000, 800);
				}
			} else {
				imagecopy($layers[0], $layers[$i], 0, 0, 0, 0, 1200, 1700);
			}
		}
		$image_loaded = $layers[0];
		function addtext($image, $x, $y, $size, $angle, $color, $font, $text) {
			$font = realpath($font);
			$text_box = imagettfbbox($size, $angle, $font, $text);
			$x = ($x) - (($text_box[2]-$text_box[0])/2);
			$y = ($y) + (($text_box[7]-$text_box[1])/2);
			imagettftext($image, $size, $angle, $x, $y, $color, $font, $text);
		}
		addtext($image_loaded, 600, 105, 50, 0, imagecolorallocate($image_loaded, 0, 0, 0), "{$Folder}/font.ttf", $name); //nom
		addtext($image_loaded, 600, 1529, 60, 0, imagecolorallocate($image_loaded, 0, 0, 0), "{$Folder}/font.ttf", "{$price}E");
		addtext($image_loaded, 600, 1230, 50, 0, imagecolorallocate($image_loaded, 0, 0, 0), "{$Folder}/font.ttf", implode("\n", explode("=_=", $desc)));
		//header ('Content-Type: image/png');
		ob_start();
		imagepng($image_loaded);
		$content = ob_get_contents();
		ob_end_clean();
		return json_encode(array("Image"=>base64_encode($content)));
	} else {
		return json_encode(array("Error" => "Card Not Exist"));
	}
}
?>