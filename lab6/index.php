<?php

$servername = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'moja_strona';


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die('<b>Przerwane połączenie:</b> ' . $conn->connect_error);
}


$conn->set_charset("utf8mb4");

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once("showpage.php");


if($_GET['idp'] == '') $strona = 'html/glowna.html';
if($_GET['idp'] == 'Gry w serii') $strona = 'html/gry.html';
if($_GET['idp'] == 'Formaty walk') $strona = 'html/battles.html';
if($_GET['idp'] == 'Typy pokemonów') $strona = 'html/types.html';
if($_GET['idp'] == 'Turnieje') $strona = 'html/turnieje.html';
if($_GET['idp'] == 'Timedate') $strona = 'html/timedate.html';
if($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';
if ($_GET['idp'] == 'Timedate') {
    
};

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona z menu bocznym</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body<?php if($_GET['idp'] == 'Timedate') echo ' onload="startclock()" style="background-image: none;"'; ?>>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="index.php?idp=glowna"> Strona główna</a></li>
            <li><a href="index.php?idp=gry"> Gry w serii</a></li>
            <li><a href="index.php?idp=battles"> Formaty walk</a></li>
            <li><a href="index.php?idp=types"> Typy pokemonów</a></li>
            <li><a href="index.php?idp=Turnieje"> Turnieje</a></li>
            <li><a href="index.php?idp=Timedate"> Czas i data</a></li>
            <li><a href="index.php?idp=filmy"> Filmy</a></li>
        </ul>
    </div>

    
    <div class='content'>

    <?php

    $page = isset($_GET['idp']) && $_GET['idp'] != '' ? $_GET['idp'] : 'Strona główna';

    echo PokazPodstrone($page);
    ?>
</div>



    <!-- FOOTER -->
    <footer>
        <p>Adam Ornacki</p>
        <p>175291</p>
    </footer>
    
    <?php if($_GET['idp'] == 'Timedate'): ?>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <?php endif; ?>

</body>
</html>