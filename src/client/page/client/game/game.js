Game.loadDeck = new class {
    constructor() {
        this.deck = "";
        setInterval(function () {
            if (page_loaded === "client/game") {
                localforage.getItem("Utopia.[" + Game_data.battle_name + "].Deck", function (err, value) {
                    var already_created_value = [];
                    let already_created = document.getElementById("client.deck.deck_list");
                    for (let item of already_created.children) {
                        if (Object.keys(value).includes(item.id)) {
                            already_created_value.push(item.id);
                        } else {
                            item.remove();
                        }
                    }
                    for (let item of Object.keys(value)) {
                        if (!already_created_value.includes(item)) {
                            document.getElementById("client.deck.deck_list").appendChild(function () {
                                var div = document.createElement("div");
                                div.id = item;
                                div.className = "client.deck.all_deck.deck_data";


                                var label = document.createElement("label");
                                label.innerHTML = item;
                                label.className = "client.deck.all_deck.deck_data.name";
                                div.appendChild(label);


                                var button1 = document.createElement("button");
                                button1.value = item;
                                button1.setAttribute("onclick", "Game.loadDeck.use_deck(this.value)");
                                button1.innerHTML = "use";
                                button1.className = "client.deck.all_deck.deck_data.use";
                                div.appendChild(button1);

                                return div;
                            }());
                        }
                    }
                });
            }
        }, 100);
    }
    use_deck(deck) {
        var that = this;
        localforage.getItem("Utopia.[" + Game_data.battle_name + "].Deck", function (err, value) {
            that.deck = value[deck];
            change_page("client/game/loading");
        });
    }
}();