Game.loadbattle = new class {
    constructor() {
        setInterval(function() {
            if (page_loaded === "client/game/loading") {
                if (Game.loadDeck.deck !== "") {
                    var deck = Game.loadDeck.deck;
                    Game.loadDeck.deck = "";
                    Game.loadbattle.load_game("B1", deck);
                } else {
                    if (this.Starter !== undefined) {
                        change_page("client/game/battlefield");
                    }
                }
            }
        }, 100);
    }

    async load_game(battlefield_id, deck) {
        console.log(deck);
        var deck_decoded = [];
        for (let item of Object.keys(deck)) {
            for (var i = 0; i < deck[item]; i++) {
                deck_decoded.push(item);
            }
        }
        var account = await Launcher.gigly.getItem("Client.Account")
        var client_token = account["Token"];
        var server = PBattleApi.ResearchServer(Game_data.battle_name, client_token, account["A-Token"]);
        server = server[Game_data.battle_name];
        console.log(client_token);
        Game_data.BS_server = new BattleSystemApi(server["url"] + "Battle/", battlefield_id, client_token);
        var Bdd_version = await localforage.getItem("Utopia.DB.[" + Game_data.battle_name + "].version");
        var data = Game_data.BS_server.Start(1, Bdd_version);
        if (data["System"] !== "Wait") {
            return;
        }
        do {
            data = Game_data.BS_server.WaitPlayer();
        } while (data["Error"] === "Wait");
        if (Object.keys(data).includes("Error")) {
            return;
        }
        console.log(deck_decoded);
        this.Starter = Game_data.BS_server.SendDeck(deck_decoded);
        change_page("client/game/battlefield");
    }
}();