class BattleApi {
    constructor(Server) {
        this.rcjs = new RcJsApi(Server);
    }

    GetServId() {
        return this.rcjs.getJsBySystem(
                "GetServId"
                );
    }

    GetBddVersion() {
        return this.rcjs.getJsBySystem(
                "GetBddVersion"
                );
    }

    GetBdd() {
        return this.rcjs.getJsBySystem(
                "GetBdd"
                );
    }

    GetImage(idImage, language) {
        return this.rcjs.getJsBySystem(
                "GetImage",
                {
                    "idImage": idImage,
                    "language": language
                }
        );
    }

    GetFont() {
        return this.rcjs.getJsBySystem(
                "GetFont"
                );
    }

    IfBattleServer() {
        return this.rcjs.getJsBySystem(
                "IfBattleServer"
                );
    }
}