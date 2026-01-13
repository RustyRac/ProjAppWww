<?php
/**
 * required_once.php - Przykładowy plik demonstrujący użycie require_once()
 */

/**
 * requiredHelper - Zwraca komunikat potwierdzający dołączenie pliku
 * @return string Komunikat
 */
function requiredHelper() {
    return 'Funkcja z required_once.php';
}

echo 'Plik required_once.php został załadowany (require_once).<br/>';
?>

