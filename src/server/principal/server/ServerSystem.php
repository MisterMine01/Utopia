<?php

include "Server.php";

$api = new ServerApi();

function create_server()
{
    global $api;
    return $api->createServer(
        $_POST["ServerName"],
        $_POST["userToken"],
        $_POST["url"],
        $_POST["Atoken"]
    );
}

function Search() {
    global $api;
    return $api->Search($_POST["Search"], $_POST["userToken"], $_POST["Atoken"]);
}
function GetServerToken() {
    global $api;
    return $api->SearchToken($_POST["tokenSearch"], $_POST["userToken"], $_POST["Atoken"]);
}

function GetServerInfo() {
    global $api;
    return $api->GetServerInfo($_POST["ServerToken"], $_POST["userToken"], $_POST["Atoken"]);
}

function SendServerInfo()
{
    global $api;
    return $api->SendServerInfo(
        $_POST["ServerToken"],
        $_POST["userToken"],
        $_POST["Atoken"],
        json_decode($_POST["newInfo"], true)
    );
}