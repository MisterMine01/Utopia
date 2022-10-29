Game.Battle = new class {
    constructor() {
        this.game_data = Game.loadbattle.Starter;
        this.PlayerId = Game_data.BS_server.PlayerId;
        var Ennemyid = Object.keys(Game.loadbattle.Starter);
        Ennemyid.splice(Ennemyid.indexOf(this.PlayerId), 1);
        this.EnnemyId = Ennemyid[0];
        this.game_data = {};
        setInterval(function () {
            if (page_loaded === "client/game/battlefield") {
                var data = Game_data.BS_server.GetBattle();
                if (JSON.stringify(Game.Battle.game_data) !== JSON.stringify(data)) {
                    Game.Battle.game_data = data;
                    Game.Battle.reload_battle(data);
                }
            }
        }, 100);
    }

    adding_card(div_id, name, click, class_id) {
        console.log(click)
        var img_data = Game_data.img[name];
        var att_value = Game_data.db["Card"][name]["att"];
        var def_value = Game_data.db["Card"][name]["def"];

        document.getElementById(div_id).appendChild(function () {
            var div = document.createElement("div");
            div.className = class_id;
            div.setAttribute("onclick", click);

            var img = document.createElement("img");
            img.src = "data:image/png;base64," + img_data;
            div.appendChild(img);

            var att = document.createElement("label");
            att.innerHTML = att_value;
            att.className = "card_div.att";
            div.appendChild(att);

            var def = document.createElement("label");
            def.innerHTML = def_value;
            def.className = "card_div.def";
            div.appendChild(def);

            return div;
        }());
    }

    change_card(card_id, att, def) {
        card_id.querySelector(".card_div\\.att").innerHTML = att;
        card_id.querySelector(".card_div\\.def").innerHTML = def;
    }

    reload_Board(board_name, data, onclick_function, card_class) {
        let child = document.getElementById(board_name).children;
        for (var i = 0; i < data.length; i++) {
            if (i > child.length - 1) {
                this.adding_card(board_name, data[i]["Id"],
                    onclick_function + "(" + i + ")", card_class);
                child = document.getElementById(board_name).children;
            }
            switch (data[i]["state"]) {
                case "Dead":
                    child[i].hidden = true;
                    child[i].style.backgroundColor = Game_data.db["State"]["Dead"];
                    break;
                case "Attack":
                    child[i].style.backgroundColor = Game_data.db["State"]["Attack"];
                    break;
                case "OnAttack":
                    child[i].style.backgroundColor = Game_data.db["State"]["OnAttack"];
                    break;
                case "Alive":
                    child[i].hidden = false;
                    child[i].style.backgroundColor = Game_data.db["State"]["Alive"];
                    break;
            }
            if (child[i].querySelector(".card_div\\.att").innerHTML !== data[i]["att"] ||
                child[i].querySelector(".card_div\\.def").innerHTML !== data[i]["def"]) {
                this.change_card(child[i], data[i]["att"], data[i]["def"]);
            }
        }
    }

    reload_battle(new_data) {
        for (let item of document.getElementById("user_hand").children) {
            item.remove();
        }
        for (let item of new_data[this.PlayerId]["Hand"]) {
            this.adding_card("user_hand", item, "Game.Battle.Hand(\"" + item + "\")", "client.field.hand");
        }
        this.reload_Board("user_battlefield", new_data[this.PlayerId]["Board"], "Game.Battle.Board", "client.field.battle");
        this.reload_Board("enemy_battlefield", new_data[this.EnnemyId]["Board"], "Game.Battle.Enemy", "client.field.battle");
        var man = "";
        if (this.PlayerId === new_data["Phase"]["PlayerId"]) {
            man = "Your ";
        } else {
            man = "Ennemy ";
        }

        document.getElementById("field.info.phase").innerHTML = man + new_data["Phase"]["Phase"];
        document.getElementById("field.info.enemy.life").innerHTML = String(new_data[this.EnnemyId]["Life"]) + "PV";
        document.getElementById("field.info.enemy.eclat").innerHTML = String(new_data[this.EnnemyId]["Eclat"]) + "E";
        document.getElementById("field.info.user.life").innerHTML = String(new_data[this.PlayerId]["Life"]) + "PV";
        document.getElementById("field.info.user.eclat").innerHTML = String(new_data[this.PlayerId]["Eclat"]) + "E";
    }

    sending_battle(card_id, board_id, phase_id) {
        if (phase_id.includes(this.game_data["Phase"]["PhaseType"])) {
            if (this.game_data["Phase"]["PlayerId"] === this.PlayerId) {
                if (this.game_data["Phase"]["PhaseUser"] === 0) {
                    Game_data.BS_server.SendBattle(null,
                        JSON.stringify({ "card": card_id, "board_id": board_id }));
                }
            } else if (this.game_data["Phase"]["PhaseUser"] === 1) {
                Game_data.BS_server.SendBattle(null,
                    JSON.stringify({ "card": card_id, "board_id": board_id }));
            }
        }
    }

    Hand(value) {
        this.sending_battle(value, 0, [0, 3, 5, 6]);
    }
    Board(value) {
        this.sending_battle(value, 2, [2, 4, 5, 6]);
    }
    Enemy(value) {
        this.sending_battle(value, 1, [1, 3, 4, 6]);
    }
    Pass() {
        Game_data.BS_server.SendBattle("Pass", null);
    }
}();