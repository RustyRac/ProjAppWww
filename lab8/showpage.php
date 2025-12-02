<?php
function PokazPodstrone($id)
{
    global $conn;

    $id_clear = mysqli_real_escape_string($conn, htmlspecialchars($id, ENT_QUOTES, 'UTF-8'));


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
