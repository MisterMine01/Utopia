Launcher.language = new class {
    constructor() {
        this.isAlreadyOpen = false;
        this.language_intervall = setInterval(function () {
            if (page_loaded !== "launcher/language") {
                Launcher.language.isAlreadyOpen = false;
                return;
            }
            if (Launcher.language.isAlreadyOpen) {
                return;
            }
            Launcher.language.isAlreadyOpen = true;
            var all_lang = ClientApi.Lang();
            var already_created_value = [];
            let already_created = document.getElementById("launcher.language.language_list");
            for (let item of already_created.children) {
                if (all_lang.includes(item.id)) already_created_value.push(item.id);
                else item.remove();
            }
            for (let item of all_lang) {
                if (!already_created_value.includes(item)) {
                    document.getElementById("launcher.language.language_list").appendChild(function () {
                        var div = document.createElement("div");
                        div.id = item;
                        div.className = "launcher.language.language_list.language_data";

                        var label = document.createElement("label");
                        label.innerHTML = item;
                        label.className = "launcher.language.language_list.language_data.name";
                        div.appendChild(label);

                        var button1 = document.createElement("button");
                        button1.value = item;
                        button1.setAttribute("onclick", "Launcher.language.set_lang(this.value)");
                        button1.innerHTML = "use";
                        button1.className = "launcher.language.language_list.language_data.use";
                        div.appendChild(button1);

                        return div;
                    }());
                }
            }
        }, 100);
    }

    set_lang(language) {
        Launcher.save_launcher_data(undefined, language);
    }
}();