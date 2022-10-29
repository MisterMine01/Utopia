const toBase64 = file => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
});

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

var Account = new class {
    constructor() {
        this.open = false;
        this.paramsAddOpen = false;
        this.search = "";
        this.gigly = localforage.createInstance({ name: "Gigly" });
        this.gigly.getItem("Client.Account", function (err, value) {
            document.getElementById("avatar_div").onclick = function () { location.href = 'connect.php'; };
            if (value != undefined) {
                var data = ClientApi.AutoConnectAccount(value["UserName"], value["A-Token"]);
                if (!Object.keys(data).includes("Error")) {
                    Account.gigly.setItem("Client.Account", data);
                    document.getElementById("avatar_div").onclick = function () { Account.viewConnect(); };
                    Account.token = value["Token"];
                    Account.Atoken = value["A-Token"];
                    Account.reloadBattle();
                } else { Account.gigly.removeItem("Client.Account"); }
            }
            Account.rechargeImg();
        });
    }
    AddBattle() {
        PBattleApi.CreateServer(document.getElementById("nameServer").value, this.token, this.Atoken, document.getElementById("urlServer").value);
        this.reloadBattle();
    }
    SendParams() {
        Account.serverData["visibility"] = document.getElementById("SelectVisibility").value;
        Account.serverData["name"] = document.getElementById("nameServer").value;
        Account.serverData["url"] = document.getElementById("urlServer").value;
        Account.serverData["others"] = {};

        for (let i of document.getElementById("UserStars").children) {
            Account.serverData["others"][i.id] = i.children[1].value;
        }

        PBattleApi.SendServerInfo(Account.serverData["id"], this.token, this.Atoken, Account.serverData);
        location.href = "Server.php";
    }

    reloadBattle() {
        var loc = location.href.split("/");
        loc = loc[loc.length - 1];
        loc = loc.split("?")
        loc = loc[0];
        console.log(loc)
        if (loc == "Server.php") {
            var div = document.getElementById("yourServer");
            div.innerHTML = "";
            var allYour = PBattleApi.GetServerByToken(this.token, this.token, this.Atoken);
            for (let [key, value] of Object.entries(allYour)) {
                Account.ServerDiv(div, value["name"], value["url"], key);
            }
        } else if (loc == "ServerParams.php") {

            var serverId = findGetParameter("id");
            var allYour = PBattleApi.GetServerInfo(serverId, this.token, this.Atoken);
            Account.serverData = allYour;
            document.getElementById("serverName").innerHTML = allYour["name"];
            document.getElementById("serverOwn").innerHTML = allYour["owner"];
            document.getElementById("serverId").innerHTML = allYour["id"]
            document.getElementById("nameServer").value = allYour["name"];
            document.getElementById("urlServer").value = allYour["url"];
            document.getElementById("SelectVisibility").value = allYour["visibility"];

            for (let [key, value] of Object.entries(allYour["others"])) {
                this.addStars(key, value)
            }
        }
    }
    buttonAddStars() {
        if (this.paramsAddOpen) {
            clearInterval(this.intervall);
            document.getElementById("SearchAdd").style = "display: none";
            this.paramsAddOpen = false;
        } else {
            document.getElementById("SearchAdd").style = "display: flex; flex-direction: column; position: absolute;";
            this.intervall = setInterval(function () {
                var get = document.getElementById("Search").value;
                if (get == Account.search) {
                    return;
                }
                var res = ClientApi.SearchByName(get);
                Account.search = get;
                var search = document.getElementById("SearchResult");
                search.innerHTML = "";
                for (let [name, token] of Object.entries(res)) {
                    search.appendChild(function () {
                        var but = document.createElement("button");
                        but.innerHTML = name;
                        but.value = token;
                        but.onclick = function () { Account.buttonAddStars(); Account.addStars(this.value) };

                        return but;
                    }());
                }
            }, 100);
            this.paramsAddOpen = true;
        }
    }

    addStars(key, value = null) {
        var name = ClientApi.GetName(key);
        if (Object.keys(name).includes("Error")) {
            name = "???";
        } else {
            name = name["Username"];
        }
        document.getElementById("UserStars").appendChild(function () {
            var div = document.createElement("div");
            div.id = key;

            var naming = document.createElement("p");
            naming.innerHTML = name;
            naming.style = "display: inline;";
            div.appendChild(naming);

            var right = document.createElement("select");
            for (let i of ["Banned", "User", "Moderator", "Admin"]) {
                var opt = document.createElement("option");
                if (value != null) opt.value = i;
                opt.innerHTML = i;
                right.appendChild(opt);
            }
            right.value = value;
            div.appendChild(right)

            var but = document.createElement("button");
            but.setAttribute("onclick", "Account.deleteStars(\"" + key + "\")");
            but.innerHTML = "Delete";
            div.appendChild(but);

            return div;
        }());
    }
    deleteStars(key) {
        for (let i of document.getElementById("UserStars").children) {
            if (i.id == key) {
                i.remove();
            }
        }
    }
    ServerDiv(parent, name, url, id) {
        var div = document.createElement("div");
        var pName = document.createElement("p");
        pName.innerHTML = name;
        div.appendChild(pName);
        var pUrl = document.createElement("p");
        pUrl.innerHTML = url;
        div.appendChild(pUrl);
        var button = document.createElement("button");
        button.innerHTML = "change";
        button.onclick = function () { location.href = "ServerParams.php?id=" + id; };
        div.appendChild(button);

        parent.appendChild(div);
    }
    viewConnect() {
        if (this.open) {
            document.getElementById("listA").style = "display: none;";
            this.open = false;
        } else {
            document.getElementById("listA").style = "display: flex";
            this.open = true;
        }
    }
    rechargeImg(id = "avatar_img") {
        this.gigly.getItem("Client.Account", function (err, value) {
            var img_data = ClientApi.GetImage(value["Token"])
            if (img_data == -1 || img_data == null) {
                document.getElementById(id).src = "img/connect.jpg";
            } else { document.getElementById(id).src = "data:image/png;base64," + img_data; }
        });
    }
    async changeImg(file) {
        if (file.size > 2097152) { return; }
        var base64 = await toBase64(file);
        var account = await this.gigly.getItem("Client.Account");
        ClientApi.SendImage(account["Token"], account["A-Token"], base64.split(",")[1]);
    }
    async changePassw() {
        var oldpass = document.getElementById("oldPass").value;
        var newpass = document.getElementById("newPass").value;
        if (newpass != document.getElementById("newPass2").value) { return; }
        var account = await this.gigly.getItem("Client.Account");
        var data = ClientApi.ChangePassword(account["UserName"], oldpass, newpass);
    }
    deco() {
        this.gigly.removeItem("Client.Account");
        location.href = "index.php";
    }
    createButton() {
        var type = document.getElementById("type").value;
        var user = document.getElementById("user").value;
        var pass = document.getElementById("pass").value;
        if (type == "create") {
            if (pass != document.getElementById("pass2").value) { return; }
            var data = ClientApi.CreateAccount(user, pass);
        } else { var data = ClientApi.ConnectAccount(user, pass); }
        if (data["Error"] == undefined) {
            this.gigly.setItem("Client.Account", data);
            location.href = "index.php";
        }
    }
}();