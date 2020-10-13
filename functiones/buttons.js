// Funzione che inserisce in loop la traccia in esecuzione
// Attiva o disattiva il flag globale LOOP
function loop() {
    var track = getCurrentTrack();
    // Disabilito/abilito i pulsanti di next e prev track
    // Cambio il flag LOOP
    if(LOOP)
        loopDisable();
    else
        loopEnable(track);
    var ftrack = getFutureCover();
    displaySongs();
    var first_song = getCurrentTrack();
    playPause();
    changeTrack(first_song);
    playPause();
    document.getElementById("next-cover").src = "./music/"+ftrack[0]+"/"+ftrack[1]+"/cover.jpg";
}

// Funzione che abilita il loop
function loopEnable(track) {
    LOOP = 1;
    document.getElementById("prev-button").style.display = "none";
    document.getElementById("next-button").style.display = "none";
    document.getElementById("shuffle-button").style.display = "none";
    document.getElementById("loop-button").classList.add("bg-success");
    document.getElementById("prev-cover").style.display = "none";
    var cdr = [];
    for (var i = 0; i < 5; i++)
        cdr[i] = track;
    localStorage["coda_di_riproduzione"] = JSON.stringify(cdr);
}

// Funzione che disabilita il loop
function loopDisable() {
    LOOP = 0;
    if(BEGIN_INDEX)
        document.getElementById("prev-button").style.display = "block";
    document.getElementById("next-button").style.display = "block";
    document.getElementById("shuffle-button").style.display = "block";
    document.getElementById("loop-button").classList.remove("bg-success");
    document.getElementById("prev-cover").style.display = "none";
    GLOBAL_INDEX = 0;
    songs = initCdr();
    initLS(songs);
}

// Funzione che mette in pausa il riproduttore musicale
function pauseTrack() {
    document.getElementById("player").pause();
}
           
// Funzione che avvia la riproduzione della canzone
function playTrack() {
    document.getElementById("player").play();
}

// Funzione realtiva al pulsante Play/Pause
// Esegue la funzione giusta in relazione allo stato di riproduzione
// Esegue lo switch dell'icona sul pulsante
function playPause() {
    var audio = document.getElementById("player");
    var playPauseIcon = document.getElementById("play-pause");
    if(audio.paused) {
        playTrack();
        playPauseIcon.style.backgroundImage = "url('./img/icons/pause.svg')";
    } else {
        pauseTrack();
        playPauseIcon.style.backgroundImage = "url('./img/icons/play.svg')";
    }
    return true;
}

// Funzione che modifica il volume del riproduttore musicale
// Prende in argomento l'attuale valore per consentire la modifica dell'icona
function changeVolume(amount) {
    var playerVolume = document.getElementById("player");
    var iconVolume = document.getElementById("volume-icon");
    playerVolume.volume = amount;
    if(amount == 0)
        iconVolume.src = "./img/icons/volume_mute.svg";
    else if(amount > 0.5)
        iconVolume.src = "./img/icons/volume_up.svg";
    else
        iconVolume.src = "./img/icons/volume_down.svg";
    return true;
}

// Funzione che mette in esecuzione la prossima canzone della coda di riproduzione
function nextTrack() {
    var audio = document.getElementById("player");
    // Estraggo la traccia corrente e la inserisco nella top5
    var currTrack = getCurrentTrack();
    enqueueLastSong(currTrack);
    // Estraggo la prima canzone della coda di riproduzione
    var track = dequeueSong();
    // Cambio canzone e la avvio
    playPause();
    changeTrack(track);
    playPause();
    // Cambio la cover
    changeCoverAnimation(getFutureCover());
    // Stampo il tiolo della canzone nel campo relativo al brano in riproduzione
    document.getElementById("main_song").innerHTML = printSong(track);
    // Creo il nuovo elemento canzone da mettere in coda e lo aggiungo
    if(LOOP) {
        var newSong = getCurrentTrack();
    } else {
        var newSong = GLOBAL_SONGS[GLOBAL_INDEX];  
        GLOBAL_INDEX++;
    }      
    enqueueSong(newSong); 
    // Ri-stampo a video la coda di riproduzione aggiornata
    displaySongs();

    document.getElementById("prev-button").style.display = "inline-block";
    BEGIN_INDEX++;
}

// Funzione che mette in esecuzione l'ultima canzone ascoltata
function prevTrack() {
    BEGIN_INDEX--;
    // Estraggo la traccia corrente e la inserisco nella coda di riproduzione
    var currTrack = getCurrentTrack();
    enqueueSongReverse(currTrack);
    // Estraggo la prima canzone della coda di riproduzione
    var track = dequeueLastSong();
    // Cambio canzone e la avvio
    playPause();
    changeTrack(track);
    playPause();
    // Cambio la cover
    changeCoverAnimationReverse(getPrevCover());
    // Stampo il tiolo della canzone nel campo relativo al brano in riproduzione
    document.getElementById("main_song").innerHTML = printSong(track);
    // Creo il nuovo elemento canzone da mettere in coda e lo aggiungo
    if(!LOOP) {  
        GLOBAL_INDEX--;
    }      
    // Ri-stampo a video la coda di riproduzione aggiornata
    displaySongs();

    if(TOP5_INDEX == top5_length-1 || !BEGIN_INDEX)
        document.getElementById("prev-button").style.display = "none";
}