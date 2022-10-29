var Account = new class {
    constructor() {
        this.gigly = localforage.createInstance({ name: "Gigly" });
    }

    pass_to_create() {
        change_page("account/create");
    }

    pass_to_connect() {
        change_page("account/connection");

    }

    create_account() {
        if (document.getElementsByName("account.password.input").value === document.getElementsByName("account.password2.input").value) {
            var request = ClientApi.CreateAccount(document.getElementById("account.name.input").value,
                document.getElementById("account.password.input").value);
            if (Object.keys(request).includes("Error")) {
                document.getElementById("account.error").innerHTML = request["Error"];
            } else {
                this.gigly.setItem("Client.Account", request);
                change_page("launcher");
            }
        } else {
            document.getElementById("account.error").innerHTML = "password not equivalent";
        }
    }

    connect_account() {
        var request = ClientApi.ConnectAccount(document.getElementById("account.name.input").value,
            document.getElementById("account.password.input").value);
        if (Object.keys(request).includes("Error")) {
            document.getElementById("account.error").innerHTML = request["Error"];
        } else {
            this.gigly.setItem("Client.Account", request);
            change_page("launcher");
        }
    }
}();