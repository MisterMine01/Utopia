Launcher.main = new class {
    constructor() {
        this.isLoaded = false;
        this.launcher_interval = setInterval(function () {
            if (page_loaded !== "launcher") {
                Launcher.main.isLoaded = false;
                return;
            }
            if (Launcher.main.isLoaded) { return; }
            if (Launcher.battle_use !== null) {
                var server = PBattleApi.ResearchServer(Launcher.battle_use, Launcher.account["Token"], Launcher.account["A-Token"]);
                document.getElementById("launcher.battle_use").innerHTML = "Battle Server: " + server[Launcher.battle_use]["name"];
            }
            if (Launcher.language_use !== null) {
                document.getElementById("launcher.language_use").innerHTML = "Language: " + Launcher.language_use;
            }
            Launcher.main.isLoaded = true;

        }, 100);
    }

    play() {
        if (Launcher.battle_use !== undefined) {
            if (Launcher.language_use !== undefined) {
                change_page("loading");
            }
        }
    }
}();