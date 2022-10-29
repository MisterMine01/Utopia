<?php

function get_file($url)
{
    $Agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36';
    $header = "Content-type: application/x-www-form-urlencoded\r\n" . "Accept-language: en\r\n";
    return file_get_contents(
        $url,
        false,
        stream_context_create(
            array(
                "http" => array(
                    "user-agent" => $Agent,
                    "header" => $header,
                    "method" => "GET"
                )
            )
        )
    );
}

function get_properties($key)
{
    return json_decode(file_get_contents("../properties.json"), true)[$key];
}

function test_key($api_key)
{
    return $api_key == get_file(get_properties("PrincipalServer") . $api_key);
}

function setId()
{
    if (!test_key($_POST["ApiKey"])) {
        return json_encode(array("Error" => "Not Api"));
    }
    $properties = json_decode(file_get_contents("../properties.json"), true);
    $properties["ServerId"] = $_POST["newId"];
    file_put_contents("../properties.json", json_encode($properties));
    return json_encode(array("Begin"));
}

function launchClient()
{
    if (!test_key($_POST["ApiKey"])) {
        return json_encode(array("Error" => "Not Api"));
    }
    $data = json_decode(file_get_contents(__DIR__ . "/../operative.json"), true);
    $data[] = array(
        "Token" => $_POST["Token"],
        "B-Token" => $_POST["B-Token"],
        "B-Death" => time() + 300
    );
    file_put_contents(__DIR__ . "/../operative.json", json_encode($data));
    return json_encode(array("Begin"));
}
