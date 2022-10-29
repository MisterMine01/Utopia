Launcher.battle = new class {
    constructor(get_id) {
        this.get_id = get_id;
        this.search = "dvzvevezzvzgeaebrebe";
        this.battle_interval = setInterval(function () {
            if (page_loaded !== "launcher/battle") {
                Launcher.battle.search = "dvzvevezzvz";
                return;
            }
            if (Launcher.battle.search == document.getElementById("launcher.battle.new_server").value) {
                return;
            }
            let value = PBattleApi.ResearchServer(
                document.getElementById("launcher.battle.new_server").value,
                Launcher.account["Token"], Launcher.account["A-Token"]);
            Launcher.battle.search = document.getElementById("launcher.battle.new_server").value;
            if (value === undefined) {
                return;
            }
            var already_created_value = [];
            let already_created = document.getElementById("launcher.battle.battle_list");
            for (let i = 0; i < already_created.children.length; i++) {
                if (Object.keys(value).includes(already_created.children[i].id)) {
                    already_created_value.push(already_created.children[i].id);
                } else {
                    already_created.children[i].remove();
                    i--;
                }
            }
            for (let item of Object.keys(value)) {
                if (!already_created_value.includes(item)) {
                    document.getElementById("launcher.battle.battle_list").appendChild(function () {
                        var div = document.createElement("div");
                        div.id = item;
                        div.className = "launcher.battle.battle_list.battle_data";


                        var label = document.createElement("label");
                        label.innerHTML = value[item]["name"];
                        label.className = "launcher.battle.battle_list.battle_data.name";
                        div.appendChild(label);


                        var button1 = document.createElement("button");
                        button1.value = item;
                        button1.setAttribute("onclick", "Launcher.battle.set_server(this.value)");
                        button1.innerHTML = "use";
                        button1.className = "launcher.battle.battle_list.battle_data.use";
                        div.appendChild(button1);

                        return div;
                    }());
                }
            }
        }, 100);
    }

    set_server(battle_server) {
        var value = PBattleApi.ResearchServer("",
            Launcher.account["Token"],
            Launcher.account["A-Token"]);
        if (Object.keys(value).includes(battle_server)) {
            Launcher.save_launcher_data(battle_server);
        }
    }

}("IfBattleServer");