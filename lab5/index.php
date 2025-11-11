<?php


error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);


if($_GET['idp'] == '') $strona = 'html/glowna.html';
if($_GET['idp'] == 'Gry w serii') $strona = 'html/gry.html';
if($_GET['idp'] == 'Formaty walk') $strona = 'html/battles.html';
if($_GET['idp'] == 'Typy pokemonów') $strona = 'html/types.html';
if($_GET['idp'] == 'Turnieje') $strona = 'html/turnieje.html';
if($_GET['idp'] == 'Timedate') $strona = 'html/timedate.html';
if($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';
if ($_GET['idp'] == 'Timedate') {
    
};
if (!file_exists($strona)) {
    $strona = 'html/glowna.html';
}
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
            <li><a href="index.php?idp="> Strona główna</a></li>
            <li><a href="index.php?idp=Gry w serii"> Gry w serii</a></li>
            <li><a href="index.php?idp=Formaty walk"> Formaty walk</a></li>
            <li><a href="index.php?idp=Typy pokemonów"> Typy pokemonów</a></li>
            <li><a href="index.php?idp=Turnieje"> Turnieje</a></li>
            <li><a href="index.php?idp=Timedate"> Czas i data</a></li>
            <li><a href="index.php?idp=filmy"> Filmy</a></li>
        </ul>
    </div>

    
    <div class='content'>

    <?php include($strona); ?>
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