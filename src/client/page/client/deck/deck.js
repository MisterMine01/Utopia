Game.deck = new class {
    constructor() {
        this.deck = "";
        setInterval(function () {
            if (page_loaded === "client/deck") {
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
                                button1.setAttribute("onclick", "Game.deck.use_deck(this.value)");
                                button1.innerHTML = "use";
                                button1.className = "client.deck.all_deck.deck_data.use";
                                div.appendChild(button1);


                                var button2 = document.createElement("button");
                                button2.value = item;
                                button2.setAttribute("onclick", "Game.deck.delete_deck(this.value)");
                                button2.innerHTML = "DELETE";
                                button2.className = "client.deck.all_deck.deck_data.delete";
                                div.appendChild(button2);
                                return div;
                            }());
                        }
                    }
                });
            }
        }, 100);
    }
    use_deck(value) {
        this.deck = value;
        change_page("client/deck/creator");
    }

    delete_deck(name) {
        localforage.getItem("Utopia.[" + Game_data.battle_name + "].Deck", function (err, value) {
            if (Object.keys(value).includes(name)) {
                delete value[name];
                localforage.setItem("Utopia.[" + Game_data.battle_name + "].Deck", value);
            }
        });
    }

    create_deck() {
        this.deck = "0_RELOAD_0";
        change_page("client/deck/creator");
    }
}();