<?php include "Site/php/begin.php"; ?>
<div>
    <div class="partie">
        <div id="changeParams">
            <h1 id="serverName"></h1>
            <div id="entry" style="display: flex; justify-content: space-around;">
                <div id="part1">
                    <h2>Informations:</h2>
                    <p>ID: <span id="serverId"></span></p>
                    <p>Owner: <span id="serverOwn"></span></p>
                    <p>Nom du serveur <input id="nameServer" type="text"></p>
                    <p>URL <input id="urlServer" type="text"></p>
                    <select id="SelectVisibility">
                        <option value="public">public</option>
                        <option value="private">private</option>
                    </select>
                </div>
                <div id="part2">
                    <button onclick="Account.buttonAddStars()">Add</button>
                    <div id="SearchAdd" style="display:none; flex-direction: column; position: absolute;">
                        <input id="Search"></input>
                        <div id="SearchResult" style="display: flex; flex-direction: column;">
                            <!--<button value="token">username</button>-->
                        </div>
                    </div>
                    <div id="UserStars">
                        <!--<div id="token">
                            <p style="display: inline;">username</p>
                            <select>
                                <option value="Banned">Banned</option>
                                <option value="User">User</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Admin">Admin</option>
                            </select>
                            <button>Delete</button>
                        </div>-->
                    </div>
                </div>
            </div>
            <button onclick="Account.SendParams()">sauvegarder</button>
        </div>
    </div>
</div>
<?php include "Site/php/end.php"; ?>