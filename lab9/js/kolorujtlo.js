/**
 * kolorujtlo.js - Funkcje do konwersji jednostek i zmiany koloru tła
 */

// Zmienne globalne
var computed = false;
var decimal = 0;

/**
 * convert - Konwertuje wartość z jednej jednostki miary na drugą
 * @param {HTMLFormElement} entryform Formularz zawierający pola input i display
 * @param {HTMLSelectElement} from Lista rozwijana z jednostką źródłową
 * @param {HTMLSelectElement} to Lista rozwijana z jednostką docelową
 * @uses addChar() - wywołuje convert() po dodaniu znaku
 */
function convert(entryform, from, to) {
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

/**
 * addChar - Dodaje znak do pola wejściowego i automatycznie konwertuje wartość
 * @param {HTMLInputElement} input Pole wejściowe
 * @param {string} character Znak do dodania
 * @uses convert() - wywołuje konwersję po dodaniu znaku
 */
function addChar(input, character) {
    if ((character == '.' && decimal == 0) || character != '.') {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character;
        convert(input.form, input.form.measure1, input.form.measure2);
        computed = true;
        if (character == '.') {
            decimal = 1;
        }
    }
}

/**
 * openVothcom - Otwiera nowe okno przeglądarki
 */
function openVothcom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}

/**
 * clear - Czyści formularz konwersji jednostek
 * @param {HTMLFormElement} form Formularz do wyczyszczenia
 */
function clear(form) {
    form.input.value = 0;
    form.display.value = 0;
    decimal = 0;
}

/**
 * changeBackground - Zmienia kolor tła strony
 * @param {string} hexNumber Kolor w formacie heksadecymalnym (np. "#FF0000")
 */
function changeBackground(hexNumber) {
    if (document.body) {
        document.body.style.backgroundColor = hexNumber;
        document.body.style.backgroundImage = 'none';
    }
}