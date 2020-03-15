<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Anglais</title>
    <style>
        body, input, textarea {
            font-family: "Fira Sans", "Source Sans Pro", Helvetica, Arial, sans-serif;
            font-weight: 400;
        }
    </style>
</head>
<body>
<?php

function loadBDD()
{
    $row = 1;
    if (($handle = fopen("bdd.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 256, ";")) !== FALSE) {
            $verbes[] = array(
                'fr' => $data[0],
                'inf' => $data[1],
                'pret' => $data[2],
                'pp' => $data[3]
            );
        }
        fclose($handle);
    }
    return $verbes;
}

function getRandomData($bdd)
{
    $rand = rand(0, count($bdd));
    return $bdd[$rand];
}

function displayBDD()
{
    echo '<table>';
    foreach($verbes as $index => $verbe) {
        echo '<tr>';
        foreach($verbe as $val) {
            echo '<td>'.$val.'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

function displayForm($bdd, $rand = null)
{
    if ($rand == null) $rand = rand(0, count($bdd));
    echo '<form action="#" method="GET">';
    echo '<input name="index" type="hidden" value="'.$rand.'"/>';
    echo '<table><tr><td>'.$bdd[$rand]['fr'].'</td>';
    echo '<td><input name="inf" type="text" /></td>';
    echo '<td><input name="pret" type="text" /></td>';
    echo '<td><input name="pp" type="text" /></td>';
    echo '<td><input type="submit" value="GO" /></td></tr>';
    echo '</table></form>';
}

$bdd = loadBDD();

if ($_GET['index']) {
    $verbe = $bdd[$_GET['index']];
    if ((strtolower($_GET['inf']) == $verbe['inf']) && (strtolower($_GET['pret']) == $verbe['pret']) && (strtolower($_GET['pp']) == $verbe['pp']))
        displayForm($bdd);
    else {
        echo "ERREUR";
        displayForm($bdd, $_GET['index']);
    }
} else displayForm($bdd);

?>
</body>
</html>
