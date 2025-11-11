<?php
$nr_indeksu = '175291';
$nrGrupy = '2';
$date = date('N');
echo 'Adam Ornacki '.$nr_indeksu.' grupa '.$nrGrupy.'<br/><br/>';
?>
<?php
session_start();

if (!isset($_SESSION['visits'])) {
$_SESSION['visits'] = 0;
}
$_SESSION['visits']++;

include 'included.php';
require_once 'required_once.php';
require_once 'required_once.php';

$name = isset($_GET['name']) ? $_GET['name'] : 'Gość';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$message = isset($_POST['message']) ? $_POST['message'] : '';
}

echo '<br/><strong>Dane z $_GET</strong>: name = ' . htmlspecialchars($name) . '<br/>';
echo '<strong>Dane z $_POST</strong>: message = ' . htmlspecialchars($message) . '<br/>';
echo '<strong>$_SESSION visits</strong>: ' . $_SESSION['visits'] . '<br/>';

if ($_SESSION['visits'] === 1) {
echo 'Pierwsze odwiedzenie<br/>';
} elseif ($_SESSION['visits'] < 5) {
echo '<br/>więcej niż 5 odwiedzin<br/>';
} else {
echo 'Dużo odwiedzin!<br/>';
}

switch ($date) {
case 6:
case 7:
echo 'Weekend!<br/>';
break;
case 1:
echo 'Poniedziałek<br/>';
break;
default:
echo 'Dzień roboczy<br/>';
}

$i = 1;
echo '<br/>Pętla while: ';
while ($i <= 3) {
echo $i . ' ';
$i++;
}
echo '<br/>';

echo 'Pętla for: ';
for ($j = 1; $j <= 5; $j++) {
echo $j . ' ';
}
echo '<br/><br/>';

if (function_exists('requiredHelper')) {
echo requiredHelper() . '<br/>';
}
?>

<a href="?name=Adam">Ustaw name przez $_GET</a>
<br/><br/>
<form method="post">
<label>Wiadomość (POST): <input type="text" name="message" /></label>
<button type="submit">Wyślij</button>
</form>