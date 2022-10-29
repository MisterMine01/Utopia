<?php
$folder = $_GET["page"];
$index = json_decode(file_get_contents("page/" . $folder . "/index.json"), true);
?>
<!doctype html>
<html>

<head>
    <style>
        body {
            width: 100vw;
            height: 100vh;
            margin: 0;
            border: 0;
            padding: 0;
        }
    </style>

    <?php
    foreach ($index["css"] as $value) {
        $file = "page/" .  $folder . "/" . $value;
        echo "<link rel=\"stylesheet\" href=\"{$file}\">";
    }
    ?>
</head>

<body>
    <div id=<?php echo "\"{$folder}\"" ?>>
        <?php
        echo file_get_contents("page/" . $folder . "/" . $index["page"]);
        ?>
    </div>
</body>

</html>