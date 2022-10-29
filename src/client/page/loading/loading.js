async function Download(battle_server, language) {
    console.log(battle_server);
    var server = PBattleApi.ResearchServer(battle_server, Launcher.account["Token"], Launcher.account["A-Token"]);
    console.log(server);
    Game_data.Battle_server = new BattleApi(server[battle_server]["url"]);
    var server_version = Game_data.Battle_server.GetBddVersion()[0];
    if ((await localforage.getItem("Utopia.DB.[" + battle_server + "].version") !== server_version) ||
            ((await localforage.getItem("Utopia.DB.[" + battle_server + "].img"))[language] == null)) {
        var db = Game_data.Battle_server.GetBdd();
        localforage.setItem("Utopia.DB.[" + battle_server + "].Bdd", db);
        var db_image = [];
        db_image[language] = new Object();
        Object.keys(db["Card"]).forEach(function (value, index, array) {
            console.log(value);
            db_image[language][value] = Game_data.Battle_server.GetImage(value, language)["Image"];
        });

        var db_exist = await localforage.getItem("Utopia.DB.[" + battle_server + "].img");
        if (db_exist != null) {
            Object.keys(db_exist).forEach(function (value, index, array) {
                db_image[value] = db_exist[value];
            })
        }
        await localforage.setItem("Utopia.DB.[" + battle_server + "].img", db_image);
        await localforage.setItem("Utopia.DB.[" + battle_server + "].version", server_version);
    }
    var img = await localforage.getItem("Utopia.DB.[" + battle_server + "].img");
    var db = await localforage.getItem("Utopia.DB.[" + battle_server + "].Bdd");
    Game_data.battle_name = Launcher.battle_use;
    Game_data.language = Launcher.language_use;
    Game_data.img = img[Game_data.language];
    console.log(Game_data.img);
    Game_data.db = db;
    change_page("client");
}
Download(Launcher.battle_use, Launcher.language_use);