Game.deck.creator = new class {
    constructor() {
        this.deck = {};
        setInterval(function () {
            if (page_loaded === "client/deck/creator") {
                var data = Game.deck.deck;
                var already_created_value = [];
                let already_created = document.getElementById("client.deck.creator.all_card");
                for (let item of already_created.children) {
                    if (Object.keys(Game_data.db["Card"]).includes(item.id)) {
                        already_created_value.push(item.id);
                    } else {
                        item.remove();
                    }
                }
                for (let item of Object.keys(Game_data.db["Card"])) {
                    if (!already_created_value.includes(item)) {
                        console.log(item);
                        Game.deck.creator.create_card(document.getElementById("client.deck.creator.all_card"), item, "Game.deck.creator.add_card('" + item + "')");
                    }
                }
                if (data !== "") {
                    Game.deck.creator.deck = {};
                    if (data !== "0_RELOAD_0") {
                        console.log(Game.deck.deck);
                        localforage.getItem("Utopia.[" + Game_data.battle_name + "].Deck", function (err, value) {
                            console.log(Game.deck.deck);
                            for (let item of Object.keys(value[data])) {
                                for (let i = 0; i < value[data][item]; i++) {
                                    console.log(item);
                                    Game.deck.creator.add_card(item);
                                }
                            }
                            document.getElementById("client.deck.creator.deck_name").value = data;
                        });
                    }
                    Game.deck.deck = "";

                }
            }
        }, 1000);
    }

    create_deck() {
        var name = document.getElementById("client.deck.creator.deck_name").value;
        if (name === "") {
            return;
        }
        var deck = this.deck;
        localforage.getItem("Utopia.[" + Game_data.battle_name + "].Deck", function (err, value) {
            if (value === null) {
                var data = {};
            } else {
                var data = value;
            }
            data[name] = deck;
            localforage.setItem("Utopia.[" + Game_data.battle_name + "].Deck", data);
        });
        change_page("client");
    }

    delete_card(name) {
        if (this.deck[name] === 1) {
            delete this.deck[name];
            document.getElementById(name + ".counter").remove();
        } else {
            this.deck[name] -= 1;
        }
        this.reload();
    }

    add_card(name) {
        console.log(name);
        if (name in this.deck) {
            this.deck[name] += 1;
        } else {
            this.deck[name] = 1;
            document.getElementById("client.deck.creator.deck").appendChild(function (that) {
                var div = document.createElement("div");
                div.id = name + ".counter";
                div.className = "client.deck.creator.card_counter";

                var label = document.createElement("label");
                label.id = "[" + name + "].label";
                label.className = "client.deck.creator.card_counter.label";
                div.appendChild(label);

                that.create_card(div, name, "Game.deck.creator.delete_card('" + name + "')");

                return div;
            }(this));
        }
        this.reload();
    }

    reload() {
        for (let item in this.deck) {
            console.log("[" + item + "].label");
            console.log(document.getElementById("[" + item + "].label"));
            document.getElementById("[" + item + "].label").innerHTML = this.deck[item];
        }
    }

    create_card(div, name, click) {
            var img_data = Game_data.img[name];

            var att_value = Game_data.db["Card"][name]["att"];
            var def_value = Game_data.db["Card"][name]["def"];

            div.appendChild(function () {
                var div = document.createElement("div");
                div.className = "client.deck.creator.card";
                div.id = name;
                console.log(click);
                div.setAttribute("onclick", click);

                var img = document.createElement("img");
                img.src = "data:image/png;base64," + img_data;
                img.className = "client.deck.creator.card.img";
                div.appendChild(img);

                var att = document.createElement("label");
                att.innerHTML = att_value;
                att.className = "client.deck.creator.card.att";
                div.appendChild(att);

                var def = document.createElement("label");
                def.innerHTML = def_value;
                def.className = "client.deck.creator.card.def";
                div.appendChild(def);

                return div;
            }());
    }
}();

