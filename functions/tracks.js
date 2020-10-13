// Funzione che initializza il local storage memorizzando, secondo lo standard JSON, tutte le canzoni memorizzate nell'array songs
// Contestualmente carica le cover e i titoli dei brani, sfruttando innerHTML
function initLS(songs) {
    localStorage["coda_di_riproduzione"] = JSON.stringify(songs);
    var top5 = JSON.parse(localStorage.getItem("top5"));
    if(!top5)
        localStorage["top5"] = JSON.stringify([["","",""],["","",""],["","",""],["","",""],["","",""]]);
    var cdr = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    changeCover(cdr[cdr_length - 1]);
    var nextTrack = getNextCover();
    document.getElementById("next-cover").src = "./music/"+nextTrack[0]+"/"+nextTrack[1]+"/cover.jpg";
    var first_song = dequeueSong();
    changeTrack(first_song);
    document.getElementById("main_song").innerHTML = printSong(first_song);
    if(LOOP) {
        var newSong = getCurrentTrack(); 
    } else {
        var newSong = GLOBAL_SONGS[GLOBAL_INDEX];  
        GLOBAL_INDEX++;
    }       
    enqueueSong(newSong); 
    displaySongs();
}

// Funzione che inizializza la coda di riproduzione
// Sfrutta un array globale e un indice di riferimento per scegliere le canzoni da estrarre
function initCdr() {
    var cdr = [];
    var count = 0;
    while(GLOBAL_INDEX < 5 && GLOBAL_INDEX < GLOBAL_SONGS.length) {
        cdr.unshift(GLOBAL_SONGS[GLOBAL_INDEX]);
        GLOBAL_INDEX++;
        count++;
    }
    while(count < 5) {
        cdr.unshift(["","",""]);
        count++;
    }
    console.log(cdr);
    return cdr;
}

// Funzione che visualizza le ultime cinque canzoni riprodotte
// Tale funzione è disponibile solo per utenti registrati
function displayTopSongs() {
    top5 = JSON.parse(localStorage.getItem("top5"));
    var five_songs = document.getElementsByTagName("li");
    for (var i = 0; i < five_songs.length; i++) {
        if (debug) console.log("Prec content" + five_songs[i].value);
        five_songs[i].innerHTML = top5[i];
        if (debug) console.log("Next content" + five_songs[i].value);
    }
    return;
}

// Funzione che visualizza le canzoni in coda di riproduzione
function displaySongs() {
    var cdr = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    var five_songs = document.getElementById("cdr").getElementsByTagName("li");
    for (var i = 0; i < five_songs.length; i++)
        five_songs[i].innerHTML = printSong(cdr[cdr_length - 1 - i]);
    return;
}

// Inserisce l'ultima canzone riprodotta nella top5, ovvero gli ultimi ascolti
function enqueueLastSong(song) {
    top5 = JSON.parse(localStorage.getItem("top5"));
    for (var i = 0; i < top5_length - 1; i++) {
        top5[top5_length - i - 1] = top5[top5_length - i - 2];
    }
    top5[0] = song;
    localStorage["top5"] = JSON.stringify(top5);
    TOP5_INDEX = 0;
    return;
}

// Estrae la canzone meno recente inserita in top5 
// Non la rimuove fisicamente dall'array
function dequeueLastSong() {
    top5 = JSON.parse(localStorage.getItem("top5"));
    var res = top5[TOP5_INDEX];
    TOP5_INDEX++;
    return res;
}

// Inserisce una canzone in coda alla coda di riproduzione
function enqueueSong(song) {
    coda_di_riproduzione = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    coda_di_riproduzione[0] = song;
    localStorage["coda_di_riproduzione"] = JSON.stringify(coda_di_riproduzione);
    return;
}

// Inserisce una canzone in testa alla coda di riproduzione
function enqueueSongReverse(song) {
    coda_di_riproduzione = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    for (var i = 0; i < cdr_length-1; i++) {
        coda_di_riproduzione[i] = coda_di_riproduzione[i+1];
    }
    coda_di_riproduzione[cdr_length-1] = song;
    localStorage["coda_di_riproduzione"] = JSON.stringify(coda_di_riproduzione);
    return;
}

// Estrae una cazone dalla coda di riproduzione
function dequeueSong() {
    coda_di_riproduzione = JSON.parse(localStorage.getItem("coda_di_riproduzione"));
    var res = coda_di_riproduzione[cdr_length - 1];
    coda_di_riproduzione[cdr_length - 1] = ["", "", ""];
    for (var i = 0; i < cdr_length; i++)
        coda_di_riproduzione[cdr_length - i - 1] = coda_di_riproduzione[cdr_length - i - 2];
    coda_di_riproduzione[0] = ["", "", ""];
    localStorage["coda_di_riproduzione"] = JSON.stringify(coda_di_riproduzione);
    return res;
}