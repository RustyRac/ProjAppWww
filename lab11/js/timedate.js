/**
 * timedate.js - Wyświetlanie aktualnej daty i czasu na stronie
 */

/**
 * gettheDate - Pobiera i wyświetla aktualną datę w formacie MM/DD/YY
 * @uses startclock() - wywołuje gettheDate() przy starcie zegara
 */
function gettheDate() {
    Todays = new Date();
    TheDate = "" + (Todays.getMonth() + 1) + "/" + Todays.getDate() + "/" + (Todays.getYear() - 100);
    document.getElementById("data").innerHTML = TheDate;
}

// Zmienne globalne do zarządzania timerem
var timerID = null;
var timerRunning = false;

/**
 * stopclock - Zatrzymuje działanie zegara
 * @uses startclock() - wywołuje stopclock() przed uruchomieniem zegara
 */
function stopclock() {
    if (timerRunning) {
        clearTimeout(timerID);
    }
    timerRunning = false;
}

/**
 * startclock - Uruchamia zegar wyświetlający datę i czas
 * @uses stopclock() - zatrzymuje poprzedni zegar
 * @uses gettheDate() - wyświetla datę
 * @uses showtime() - uruchamia wyświetlanie czasu
 */
function startclock() {
    stopclock();
    gettheDate();
    showtime();
}

/**
 * showtime - Pobiera i wyświetla aktualny czas w formacie 12-godzinnym
 * Funkcja rekurencyjnie wywołuje samą siebie co sekundę
 * @uses startclock() - wywołuje showtime() przy starcie
 * @uses stopclock() - zatrzymuje timer
 */
function showtime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    
    // Formatowanie czasu do formatu 12-godzinnego
    var timevalue = "" + ((hours > 12) ? hours - 12 : hours);
    timevalue += ((minutes < 10) ? ":0" : ":") + minutes;
    timevalue += ((seconds < 10) ? ":0" : ":") + seconds;
    timevalue += (hours >= 12) ? " P.M." : " A.M.";
    
    document.getElementById("zegarek").innerHTML = timevalue;
    
    // Rekurencyjne wywołanie co sekundę
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
}