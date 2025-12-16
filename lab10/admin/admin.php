<?php
/**
 * admin.php – Panel administracyjny CMS
 * - zarządzanie podstronami
 * - zarządzanie kategoriami produktów
 */

require_once("../cfg.php");

/* =========================
   POŁĄCZENIE Z BAZĄ
========================= */
$servername = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'moja_strona';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

/* =========================
   FORMULARZ LOGOWANIA
========================= */
function FormularzLogowania()
{
    return '
    <div style="max-width:400px; margin:auto;">
        <h2>Panel CMS</h2>
        <form method="post">
            Email:<br>
            <input type="text" name="login_email"><br><br>
            Hasło:<br>
            <input type="password" name="login_pass"><br><br>
            <input type="submit" name="login" value="Zaloguj">
        </form>
    </div>';
}

/* =========================
   AUTORYZACJA
========================= */
if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['login'])) {
        if ($_POST['login_email'] === $login && $_POST['login_pass'] === $pass) {
            $_SESSION['logged_in'] = true;
            header("Location: admin.php");
            exit;
        } else {
            echo "<p style='color:red;'>Błędne dane logowania</p>";
        }
    }
    echo FormularzLogowania();
    exit;
}

/* =====================================================
   ======== ZARZĄDZANIE PODSTRONAMI (CMS) ===============
===================================================== */

/* LISTA PODSTRON */
function ListaPodstron($conn)
{
    echo "<h2>Podstrony</h2>";
    echo "<a href='admin.php?action=dodaj'>➕ Dodaj podstronę</a><br><br>";

    $result = $conn->query("SELECT * FROM page_list ORDER BY id ASC");

    while ($row = $result->fetch_assoc()) {
        echo "
        <div style='border:1px solid #ccc; padding:10px; margin-bottom:10px'>
            <b>ID:</b> {$row['id']}<br>
            <b>Tytuł:</b> {$row['page_title']}<br>
            <a href='admin.php?action=edytuj&id={$row['id']}'>Edytuj</a> |
            <a href='admin.php?action=usun&id={$row['id']}' 
               onclick=\"return confirm('Usunąć podstronę?')\">Usuń</a>
        </div>";
    }
}

/* EDYTUJ PODSTRONĘ */
function EdytujPodstrone($conn, $id)
{
    if (isset($_POST['save'])) {
        $title   = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $status  = isset($_POST['status']) ? 1 : 0;

        $conn->query("UPDATE page_list 
                      SET page_title='$title', page_content='$content', status='$status' 
                      WHERE id=$id");

        echo "<p style='color:green;'>Zapisano zmiany</p>";
    }

    $row = $conn->query("SELECT * FROM page_list WHERE id=$id")->fetch_assoc();

    echo "
    <h2>Edytuj podstronę</h2>
    <form method='post'>
        Tytuł:<br>
        <input type='text' name='title' value='{$row['page_title']}' style='width:400px'><br><br>

        Treść:<br>
        <textarea name='content' rows='10' cols='80'>{$row['page_content']}</textarea><br><br>

        <label>
            <input type='checkbox' name='status' ".($row['status'] ? "checked" : "").">
            Aktywna
        </label><br><br>

        <input type='submit' name='save' value='Zapisz'>
    </form>";
}

/* DODAJ PODSTRONĘ */
function DodajPodstrone($conn)
{
    if (isset($_POST['add'])) {
        $title   = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $status  = isset($_POST['status']) ? 1 : 0;

        $conn->query("INSERT INTO page_list (page_title, page_content, status)
                      VALUES ('$title', '$content', '$status')");

        echo "<p style='color:green;'>Dodano podstronę</p>";
    }

    echo "
    <h2>Dodaj podstronę</h2>
    <form method='post'>
        Tytuł:<br>
        <input type='text' name='title' style='width:400px'><br><br>

        Treść:<br>
        <textarea name='content' rows='10' cols='80'></textarea><br><br>

        <label>
            <input type='checkbox' name='status'> Aktywna
        </label><br><br>

        <input type='submit' name='add' value='Dodaj'>
    </form>";
}

/* USUŃ PODSTRONĘ */
function UsunPodstrone($conn, $id)
{
    $conn->query("DELETE FROM page_list WHERE id=$id");
    echo "<p style='color:red;'>Usunięto podstronę</p>";
}

/* =====================================================
   ========= ZARZĄDZANIE KATEGORIAMI ===================
===================================================== */

/* LISTA KATEGORII */
function ListaKategorii($conn)
{
    echo "<h2>Kategorie produktów</h2>";
    echo "<a href='admin.php?action=dodaj_kategorie'>➕ Dodaj kategorię</a><br><br>";

    $matki = $conn->query("SELECT * FROM categories WHERE matka=0");

    while ($m = $matki->fetch_assoc()) {
        echo "<b>📁 {$m['nazwa']}</b>
              <a href='admin.php?action=usun_kategorie&id={$m['id']}'>❌</a><br>";

        $dzieci = $conn->query("SELECT * FROM categories WHERE matka={$m['id']}");
        while ($d = $dzieci->fetch_assoc()) {
            echo "&nbsp;&nbsp;&nbsp;↳ {$d['nazwa']}
                  <a href='admin.php?action=usun_kategorie&id={$d['id']}'>❌</a><br>";
        }
        echo "<br>";
    }
}

/* DODAJ KATEGORIĘ */
function DodajKategorie($conn)
{
    if (isset($_POST['add_cat'])) {
        $nazwa = $conn->real_escape_string($_POST['nazwa']);
        $matka = (int)$_POST['matka'];

        $conn->query("INSERT INTO categories (nazwa, matka) VALUES ('$nazwa', '$matka')");
        echo "<p style='color:green;'>Dodano kategorię</p>";
    }

    echo "
    <h2>Dodaj kategorię</h2>
    <form method='post'>
        Nazwa:<br>
        <input type='text' name='nazwa'><br><br>

        Kategoria nadrzędna:<br>
        <select name='matka'>
            <option value='0'>— kategoria główna —</option>";

    $result = $conn->query("SELECT * FROM categories WHERE matka=0");
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['nazwa']}</option>";
    }

    echo "</select><br><br>
        <input type='submit' name='add_cat' value='Dodaj'>
    </form>";
}

/* USUŃ KATEGORIĘ */
function UsunKategorie($conn, $id)
{
    $conn->query("DELETE FROM categories WHERE matka=$id");
    $conn->query("DELETE FROM categories WHERE id=$id");
    echo "<p style='color:red;'>Usunięto kategorię</p>";
}

/* =========================
   MENU
========================= */
echo "<h1>Panel administracyjny</h1>";
echo "<a href='admin.php'>Podstrony</a> | ";
echo "<a href='admin.php?action=kategorie'>Kategorie</a> | ";
echo "<a href='admin.php?logout=1'>Wyloguj</a>";
echo "<hr>";

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

/* =========================
   ROUTER
========================= */
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'edytuj':
        EdytujPodstrone($conn, (int)$_GET['id']);
        break;

    case 'dodaj':
        DodajPodstrone($conn);
        break;

    case 'usun':
        UsunPodstrone($conn, (int)$_GET['id']);
        ListaPodstron($conn);
        break;

    case 'kategorie':
        ListaKategorii($conn);
        break;

    case 'dodaj_kategorie':
        DodajKategorie($conn);
        break;

    case 'usun_kategorie':
        UsunKategorie($conn, (int)$_GET['id']);
        ListaKategorii($conn);
        break;

    default:
        ListaPodstron($conn);
}
?>
