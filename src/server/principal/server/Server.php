<?php

include "pcjs.php";
include_once "../gigly.php";
include_once "../database.php";

class ServerApi
{
    protected $_ServerFile;

    public function __construct()
    {
        $this->_ServerFile = new MyDB();
    }

    public function connectionServer(PCJSAPI $pc_js, $name, $value)
    {
        $key = create_token(25);
        file_put_contents($key, $key);
        $value["ApiKey"] = $key;
        $data = $pc_js->getJsBySystem($name, $value);
        unlink($key);
        return $data;
    }

    public function createServer($serverName, $userToken, $url, $Atoken)
    {
        $user = $this->TestConnection($userToken, $Atoken);
        if (is_string($user)) {
            return $user;
        }
        $res = $this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/sql/get_url.sql"),
                [$url]
            )
        );
        if (count($res) != 0) {
            return json_encode(array("Error" => "Url already exist"));
        }
        $id = create_token(50);
        $pc = new PCJSAPI($url . "/PrincipalApi//");
        $data = $this->connectionServer(
            $pc,
            "SetId",
            array(
                "newId" => "Gigly." . $user[1] . "." . $serverName
            )
        );
        if (key_exists("Error", $data)) return json_encode(array("Error" => "Server Didn't Want Me"));

        $pc = new PCJSAPI($url);
        $data = $pc->getJsBySystem("IfBattleServer");

        if ($data["ServId"] != "Gigly." . $user[1] . "." . $serverName) {
            return json_encode(array("Error" => "Name not good"));
        }
        $this->_ServerFile->execute(
            file_get_contents(__DIR__ . "/sql/add_server.sql"),
            [
                $id,
                $serverName,
                $url,
                $this->_ServerFile->decode_result(
                    $this->_ServerFile->execute(
                        file_get_contents(__DIR__ . "/sql/get_visibility.sql"),
                        ["private"]
                    )
                )[0][0], $userToken
            ]
        );
        return json_encode(array("Begin"));
    }

    protected function TestConnection($userToken, $Atoken)
    {
        $user = $this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/../client/sql/connection_token.sql"),
                [$userToken, $Atoken]
            )
        );
        if (count($user) == 0) {
            return json_encode(array("Error" => "Can't connect to your account"));
        }
        return $user[0];
    }

    public function Search($Search, $userToken, $Atoken)
    {
        $user = $this->TestConnection($userToken, $Atoken);
        if (is_string($user)) {
            return $user;
        }

        $d = array();
        foreach ($this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/sql/search_server.sql"),
                [$user[0], $user[0], $user[0]]
            )
        ) as $value) {
            $name = "Gigly." . $value[1] . "." . $value[2];
            if (
                $Search == "" ||
                is_string(stristr(strtolower($name), strtolower($Search))) ||
                $Search == $value[0]
            ) {
                $d[$value[0]] = array("name" => $name, "url" => $value[4]);
            }
        }
        return json_encode($d);
    }

    public function SearchToken($token, $userToken, $Atoken)
    {
        $user = $this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/../client/sql/get_Gtoken.sql"),
                [$token]
            )
        )[0];
        return $this->Search($user[1], $userToken, $Atoken);
    }

    public function GetServerInfo($serverToken, $userToken, $Atoken)
    {
        $user = $this->TestConnection($userToken, $Atoken);
        if (is_string($user)) {
            return $user;
        }
        $server = $this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/sql/get_server.sql"),
                [$serverToken, $userToken, $userToken, $userToken]
            )
        );
        if (count($server) == 0) {
            return json_encode(array("Error" => "No Permission"));
        }
        $stars = $this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/sql/get_stars.sql"),
                [$serverToken]
            )
        );
        $d = array();
        foreach ($stars as $value) {
            $d[$value[0]] = $value[1];
        }
        return json_encode(array(
            "id" => $server[0][0],
            "name" => $server[0][2],
            "owner" => $server[0][1],
            "url" => $server[0][4],
            "visibility" => $server[0][3],
            "others" => $d
        ));
    }

    
    public function SendServerInfo($serverToken, $userToken, $Atoken, $newInfo)
    {
        $user = $this->TestConnection($userToken, $Atoken);
        if (is_string($user)) {
            return $user;
        }
        $server = $this->_ServerFile->decode_result(
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/sql/is_admin.sql"),
                [$serverToken, $userToken, $userToken, $userToken]
            )
        );
        if (count($server) == 0) {
            return json_encode(array("Error" => "No Permission"));
        }
        if (
            $newInfo["url"] != $server[0][2] ||
            $newInfo["name"] != $server[0][1]
        ) {
            $pc = new PCJSAPI($newInfo["url"] . "/PrincipalApi//");
            $data = $this->connectionServer(
                $pc,
                "SetId",
                array(
                    "newId" => "Gigly." . $server[0][3] . "." . $newInfo["name"]
                )
            );
            if (key_exists("Error", $data)) return json_encode(array("Error" => "Server Didn't Want Me"));

            $pc = new PCJSAPI($newInfo["url"]);
            $data = $pc->getJsBySystem("IfBattleServer");
            if ($data["ServId"] != "Gigly." . $server[0][3] . "." . $newInfo["name"]) {
                return json_encode(array("Error" => "Name not good"));
            }
        }
        $this->_ServerFile->execute(
            file_get_contents(__DIR__ . "/sql/change_server.sql"),
            [$newInfo["name"], $newInfo["url"], $newInfo["visibility"], $serverToken]
        );
        $this->_ServerFile->execute(
            file_get_contents(__DIR__ . "/sql/delete_stars.sql"),
            [$serverToken]
        );
        foreach ($newInfo["others"] as $id => $right) {
            $this->_ServerFile->execute(
                file_get_contents(__DIR__ . "/sql/add_stars.sql"),
                [$id, $serverToken, $right]
            );
        }
        return json_encode(array("Begin"));
    }
}
