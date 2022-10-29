var temp = localforage.createInstance({ name: "Gigly" });
temp.getItem("Client.Account", function(err, value) {
    if (value === null) {
        change_page("account/connection");
    } else {
        let req = ClientApi.AutoConnectAccount(value["UserName"], value["A-Token"]);
        if (Object.keys(req).includes("Error")) {
            change_page("account/connection");
        } else {
            temp.setItem("Client.Account", req);
            change_page("launcher");
        }
    }
});