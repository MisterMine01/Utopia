<?php include "Site/php/begin.php"; ?>
<div>
    <div class="partie">
        <style>
            #yourServer div {
                width: 100%;
                margin-bottom: 5px;
                background-color: dimgrey;
                border-radius: 10%;
            }
        </style>
        <div id="create">
            <h1>Créer un serveur</h1>
            <p>Nom du serveur <input id="nameServer" type="text"></p>
            <p>URL <input id="urlServer" type="text"></p>
            <button onclick="Account.AddBattle()">Envoyer</button>
        </div>
        <br>
        <div id="yourServer">
            <!--<div>
                <p>Name: Gigly.Utopia.Server2</p>
                <p>Url: https://g.g/s</p>
            </div>-->
        </div>
    </div>
</div>
<?php include "Site/php/end.php"; ?>