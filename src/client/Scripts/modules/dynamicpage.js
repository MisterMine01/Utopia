function relPathToAbs(sRelPath) {
    var nUpLn, sDir = "", sPath = location.pathname.replace(/[^\/]*$/, sRelPath.replace(/(\/|^)(?:\.?\/+)+/g, "$1"));
    for (var nEnd, nStart = 0; nEnd = sPath.indexOf("/../", nStart), nEnd > -1; nStart = nEnd + nUpLn) {
        nUpLn = /^\/(?:\.\.\/)*/.exec(sPath.slice(nEnd))[0].length;
        sDir = (sDir + sPath.substring(nStart, nEnd)).replace(new RegExp("(?:\\\/+[^\\\/]*){0," + ((nUpLn - 1) / 3) + "}$"), "/");
    }
    return sDir + sPath.substr(nStart);
}

var page_loaded = "";

function change_page(page_name) {
    console.log("change page to " + page_name);
    let data = JSON.parse(XMLsync("page/" + page_name + "/index.json").responseText);
    var div = document.createElement("div")
    div.id = page_name;
    div.innerHTML =  XMLsync("page/" + page_name + "/" + data["page"]).responseText
    document.body.innerHTML = div.outerHTML;

    for (let item of data["css"]) {
        console.log(data);
        var css = document.createElement("link");
        css.rel = "stylesheet";
        css.href = "page/" + page_name + "/" + item;
        document.head.appendChild(css);
    }

    var newscript = [];
    var newabspathjs = [];

    for (let item of data["js"]) {
        var script = document.createElement("script");
        script.src = "page/" + page_name + "/" + item;
        script.className = "dynamic_script";
        newscript.push(script);
        newabspathjs.push(relPathToAbs(script.src));
    }

    var oldscript = [];
    var oldabspathjs = [];

    for (let item of document.getElementsByClassName("dynamic_script")) {
        oldscript.push(item);
        oldabspathjs.push(relPathToAbs(item.src));
    }

    var indexed = [];
    for (let item of oldabspathjs) {
        if (newabspathjs.includes(item)) {
            indexed.push(newabspathjs.indexOf(item));
        }
    }

    for (let item of newscript) {
        if (!indexed.includes(newscript.indexOf(item))) {
            setTimeout(function () {
                document.head.appendChild(item); }, newscript.indexOf(item) * 50);
        }
    }

    page_loaded = page_name;
}