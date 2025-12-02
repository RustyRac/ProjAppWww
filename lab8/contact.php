<?php

function WyslijMailNaKontakt($emailOdbiorcy, $temat, $wiadomosc) {
    $headers = "From: kontakt@twojadomena.pl\r\n";
    $headers .= "Reply-To: kontakt@twojadomena.pl\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if(mail($emailOdbiorcy, $temat, $wiadomosc, $headers)) {
        return true;
    } else {
        return false;
    }
}


function PrzypomnijHaslo($email) {

    $login = "przykladowy_login";
    $haslo = "przykladowe_haslo";

    $temat = "Przypomnienie hasła";
    $wiadomosc = "Twój login: $login\nTwoje hasło: $haslo";

    return WyslijMailNaKontakt($email, $temat, $wiadomosc);
}


function PokazKontakt() {
    echo '<h2>Formularz kontaktowy</h2>';
    echo '<form method="post" action="">';
    echo 'Twój email: <input type="email" name="email" required><br>';
    echo 'Temat: <input type="text" name="temat" required><br>';
    echo 'Wiadomość: <textarea name="wiadomosc" required></textarea><br>';
    echo '<input type="submit" name="wyslijKontakt" value="Wyślij">';
    echo '</form>';

    echo '<h2>Przypomnij hasło</h2>';
    echo '<form method="post" action="">';
    echo 'Twój email: <input type="email" name="emailPrzypomnienie" required><br>';
    echo '<input type="submit" name="wyslijHaslo" value="Wyślij hasło">';
    echo '</form>';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['wyslijKontakt'])) {
        $email = $_POST['email'];
        $temat = $_POST['temat'];
        $wiadomosc = $_POST['wiadomosc'];

        if (WyslijMailNaKontakt($email, $temat, $wiadomosc)) {
            echo "<p>Wiadomość została wysłana.</p>";
        } else {
            echo "<p>Błąd wysyłki wiadomości.</p>";
        }
    }

    if (isset($_POST['wyslijHaslo'])) {
        $email = $_POST['emailPrzypomnienie'];

        if (PrzypomnijHaslo($email)) {
            echo "<p>Hasło zostało wysłane na podany email.</p>";
        } else {
            echo "<p>Błąd wysyłki hasła.</p>";
        }
    }
}


PokazKontakt();
?>