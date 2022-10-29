var Launcher = new class {
    constructor() {
        this.battle_use = "";
        this.language_use = "";
        this.save_launcher_data();
        this.gigly = localforage.createInstance({ name: "Gigly" });
        this.account = null
        this.gigly.getItem("Client.Account", function(err, value) {
            Launcher.account = value;
        });
    }

    save_launcher_data(battle_server = undefined, language = undefined) {
        var that = this;
        localforage.getItem("Utopia.Launcher.main", function(err, value) {
            if (value === null) {
                var data = { "ServerUsed": null, "LanguageUsed": null };
            } else {
                var data = value;
            }
            if (battle_server !== undefined) {
                data["ServerUsed"] = battle_server;
            }
            if (language !== undefined) {
                data["LanguageUsed"] = language;
            }
            that.battle_use = data["ServerUsed"];
            that.language_use = data["LanguageUsed"];
            localforage.setItem("Utopia.Launcher.main", data);
        });
    }
}();