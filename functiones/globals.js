// VARIABILI GLOBALI

var GLOBAL_SONGS =      [];
var GLOBAL_INDEX =      0;

var songs =             [];

var LOOP =              0;

var cdr_length =        5;

var top5 =              [];
var top5_length =       5;
var TOP5_INDEX =        0;

var BEGIN_INDEX =       0;


// Funzione che cambia la traccia riprodotta
// L'argomento passato è un elemento canzone, ovvero un array di tre elementi:
// Artista, albume e titolo
function changeTrack(track) {
    document.getElementById('player').src = "./music/"+track[0]+"/"+track[1]+"/"+track[2]+".mp3";
    return true;
}

// Funzione che cambia la cover del brano riprodotto
// L'argomento passato è un elemento canzone
function changeCover(track) {
    document.getElementById('cover').src = "./music/"+track[0]+"/"+track[1]+"/cover.jpg";
}

// Funzione che ritorna l'elemento canzone attualmente in esecuzione
function getCurrentTrack() {
    var track = document.getElementById('player').src.replace('.mp3', '').split('/');
    return [track[4], track[5], track[6]];
}

// Funzione che ritorna la traccia del nuovo elemento cover da creare
function getFutureCover() {
    var cdr = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    return cdr[cdr.length-1];
}

// Funzione che ritorna la traccia del nuovo elemento cover da creare
function getNextCover() {
    var cdr = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    return cdr[cdr.length-2];
}

// Funzione che ritorna la traccia del nuovo elemento cover da creare
function getPrevCover() {
    var top5 = JSON.parse(localStorage.getItem("top5"));
    return top5[TOP5_INDEX];
}

// Funzione che stampa una canzone in un formato leggibile
// Prende come argomento un elemento canzone
function printSong(song) {
    var title = song[2].replace(/_/g, " ");
    var artist = song[0].toLowerCase().split('_').map((s) => s.charAt(0).toUpperCase() + s.substring(1)).join(' ');//.replace(/_/g, " ");
    return "<b>" + title.charAt(0).toUpperCase() + title.slice(1)
        + "</b> - " + artist.charAt(0).toUpperCase() + artist.slice(1);;
}