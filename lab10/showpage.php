<?php
/**
 * showpage.php - Funkcja do pobierania treści podstron z bazy danych
 */

/**
 * PokazPodstrone - Pobiera treść podstrony z bazy danych
 * @param string $id Tytuł strony do wyświetlenia
 * @return string Zawartość HTML strony lub '[nie_znaleziono_strony]'
 * @global mysqli $conn Połączenie z bazą danych
 */
function PokazPodstrone($id)
{
    global $conn;

    // Zabezpieczenie przed SQL Injection i XSS
    $id_clear = mysqli_real_escape_string($conn, htmlspecialchars($id, ENT_QUOTES, 'UTF-8'));

    // Pobranie treści strony z bazy danych (tylko aktywne strony)
    $query  = "SELECT * FROM page_list WHERE page_title = '$id_clear' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $web = $row['page_content'];
    } else {
        $web = '[nie_znaleziono_strony]';
    }

    return $web;
}
?>
