<?php
/**
 * admin/admin.php - Panel administracyjny CMS do zarządzania podstronami
 */

require_once("../cfg.php");

// Konfiguracja połączenia z bazą danych
$servername = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'moja_strona';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die('<b>Przerwane połączenie:</b> ' . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

/**
 * FormularzLogowania - Generuje HTML formularza logowania
 * @return string HTML formularza
 */
function FormularzLogowania()
{
    return '
    <div class="logowanie" style="max-width:400px; margin:auto;">
        <h1 class="heading">Panel CMS</h1>
        <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
            <table class="logowanie">
                <tr>
                    <td>[email]</td>
                    <td><input type="text" name="login_email" /></td>
                </tr>
                <tr>
                    <td>[haslo]</td>
                    <td><input type="password" name="login_pass" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="x1_submit" value="Zaloguj" /></td>
                </tr>
            </table>
        </form>
    </div>
    ';
}

// System autoryzacji - sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['x1_submit'])) {
        if ($_POST['login_email'] === $login && $_POST['login_pass'] === $pass) {
            $_SESSION['logged_in'] = true;
            header("Location: admin.php");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>Błędny login lub hasło!</p>";
            echo FormularzLogowania();
            exit();
        }
    }
    echo FormularzLogowania();
    exit();
}


/**
 * ListaPodstron - Wyświetla listę wszystkich podstron z linkami do edycji i usuwania
 * @param mysqli $conn Połączenie z bazą danych
 */
function ListaPodstron($conn)
{
    $sql = "SELECT * FROM page_list ORDER BY id ASC";
    $result = $conn->query($sql);

    echo "<h2>Lista podstron</h2>";
    echo "<a href='admin.php?action=dodaj'>➕ Dodaj nową podstronę</a><br><br>";

    while ($row = $result->fetch_assoc()) {
        echo "
        <div style='padding:10px; border:1px solid #ccc; margin-bottom:10px;'>
            <b>ID:</b> {$row['id']}<br>
            <b>Tytuł:</b> {$row['page_title']}<br>
            <a href='admin.php?action=edytuj&id={$row['id']}'>Edytuj</a> |
            <a href='admin.php?action=usun&id={$row['id']}' onclick=\"return confirm('Na pewno usunąć?');\">Usuń</a>
        </div>
        ";
    }
}



/**
 * EdytujPodstrone - Wyświetla formularz edycji podstrony i obsługuje zapisywanie zmian
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $id ID podstrony do edycji
 */
function EdytujPodstrone($conn, $id)
{
    if (isset($_POST['save'])) {
        $title   = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $status  = isset($_POST['status']) ? 1 : 0;

        $sql = "UPDATE page_list SET page_title='$title', page_content='$content', status='$status' WHERE id='$id'";
        $conn->query($sql);

        echo "<p style='color:green;'>Zapisano zmiany!</p>";
    }

    $sql = "SELECT * FROM page_list WHERE id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "
    <h2>Edytuj podstronę</h2>
    <form method='post'>
        Tytuł:<br>
        <input type='text' name='title' value='{$row['page_title']}' style='width:400px;'><br><br>

        Treść strony:<br>
        <textarea name='content' rows='10' cols='80'>{$row['page_content']}</textarea><br><br>

        <label>
            <input type='checkbox' name='status' ".($row['status'] ? "checked" : "").">
            Strona aktywna
        </label><br><br>

        <input type='submit' name='save' value='Zapisz zmiany'>
    </form>
    ";
}



/**
 * DodajNowaPodstrone - Wyświetla formularz dodawania nowej podstrony
 * @param mysqli $conn Połączenie z bazą danych
 */
function DodajNowaPodstrone($conn)
{
    if (isset($_POST['add'])) {
        $title   = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $status  = isset($_POST['status']) ? 1 : 0;

        $sql = "INSERT INTO page_list (page_title, page_content, status)
                VALUES ('$title', '$content', '$status')";
        $conn->query($sql);

        echo "<p style='color:green;'>Dodano nową podstronę!</p>";
    }

    echo "
    <h2>Dodaj nową podstronę</h2>
    <form method='post'>
        Tytuł:<br>
        <input type='text' name='title' style='width:400px;'><br><br>

        Treść strony:<br>
        <textarea name='content' rows='10' cols='80'></textarea><br><br>

        <label>
            <input type='checkbox' name='status'> Strona aktywna
        </label><br><br>

        <input type='submit' name='add' value='Dodaj podstronę'>
    </form>
    ";
}



/**
 * UsunPodstrone - Usuwa podstronę z bazy danych na podstawie ID
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $id ID podstrony do usunięcia
 */
function UsunPodstrone($conn, $id)
{
    $sql = "DELETE FROM page_list WHERE id='$id'";
    $conn->query($sql);

    echo "<p style='color:red;'>Usunięto podstronę!</p>";
}



// Interfejs panelu administracyjnego
echo "<h1>Panel administracyjny</h1>";
echo "<a href='admin.php'>Lista podstron</a> | ";
echo "<a href='admin.php?action=dodaj'>Dodaj podstronę</a> | ";
echo "<hr>";

// Router - obsługa różnych akcji panelu
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'edytuj':
        EdytujPodstrone($conn, $_GET['id']);
        break;

    case 'dodaj':
        DodajNowaPodstrone($conn);
        break;

    case 'usun':
        UsunPodstrone($conn, $_GET['id']);
        ListaPodstron($conn);
        break;

    default:
        ListaPodstron($conn);
}

?>
