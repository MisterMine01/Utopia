var ClientApi = new class {
    constructor() {
        this.rcjs = new RcJsApi(JSON.parse(XMLsync("Utopia.json").responseText)["principal_server"] + "client/");
    }
    CreateAccount(username, passw, mail) {
        return this.rcjs.getJsBySystem(
            "CreateAccount", {
                "Username": username,
                "Password": passw,
                "mail-adress": mail
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