var ClientApi = new class {
    constructor() {
        this.rcjs = new RcJsApi(JSON.parse(XMLsync("Gigly.json").responseText)["Server"] + "client/");
    }
    CreateAccount(username, passw) { //, mail) {
        return this.rcjs.getJsBySystem(
            "CreateAccount", {
                "Username": username,
                "Password": passw //,
                    //"mail-adress": mail
            }
        );
    }
    AutoConnectAccount(username, Atoken) {
        return this.rcjs.getJsBySystem(
            "AutoConnectAccount", {
                "Username": username,
                "A-Token": Atoken
            }
        );
    }
    ConnectAccount(username, passw) {
        return this.rcjs.getJsBySystem(
            "ConnectAccount", {
                "Username": username,
                "Password": passw
            }
        );
    }
    ChangePassword(Username, old_password, new_password) {
        return this.rcjs.getJsBySystem(
            "ChangePassword", {
                "Username": Username,
                "old_password": old_password,
                "new_password": new_password
            }
        );
    }
    GetImage(token) {
        return this.rcjs.getJsBySystem(
            "GetImage", {
                "Token": token
            }
        );
    }
    GetName(token) {
        return this.rcjs.getJsBySystem(
            "GetName", {
                "Token": token
            }
        );
    }
    SearchByName(username) {
        return this.rcjs.getJsBySystem(
            "SearchByName", {
                "Username": username
            }
        );
    }
    SendImage(token, atoken, img) {
        return this.rcjs.getJsBySystem(
            "SendImage", {
                "Token": token,
                "A-Token": atoken,
                "Img": img
            }
        );
    }
    IfPrincipalServer() {
        return this.rcjs.getJsBySystem(
            "IfPrincipalServer"
        );
    }
    Lang() {
        return this.rcjs.getJsBySystem(
            "Lang"
        );
    }
}();