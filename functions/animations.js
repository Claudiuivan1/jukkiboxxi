// Funzione che anima le cover facendole scorrere al momento del cambiamento della traccia
// Funzione associata alla pressione del tasto "next"
function changeCoverAnimation(track) {
    // Creo nuovo elemento cover con classe e posizione associati
    var nextCover = document.createElement("img");
    nextCover.id = "animate-next";
    nextCover.classList.add("cover-box");
    nextCover.classList.add("cover-box-next");
    nextCover.src = "./music/"+track[0]+"/"+track[1]+"/cover.jpg";

    var curNextCover = document.getElementById("next-cover");
    var curCover = document.getElementById("cover");
    var curPrevCover = document.getElementById("prev-cover");

    // Inserisco il nuovo elemento cover
    curNextCover.parentNode.append(nextCover);

    // Animazioni in JQuery
    $("#animate-next").animate({
        left: "-=170",
    }, 50, function() {});

    $("#prev-cover").animate({
        left: "-290",
    }, 50, function() {});

    var newLeft = $('#cover-box').width() / 2 - 80;
    $("#next-cover").animate({
        left: newLeft,
    }, 50, function() {});

    $("#cover").animate({
        left: "-120",
    }, 50, function() {});

    // Scambio tra loro le classi
    curPrevCover.remove();
    curCover.id = "prev-cover"; 
    curNextCover.id = "cover";
    nextCover.id = "next-cover";
}

// Funzione che anima le cover facendole scorrere al momento del cambiamento della traccia
// Funzione associata alla pressione del tasto "prev"
function changeCoverAnimationReverse(track) {
    // Creo nuovo elemento cover con classe e posizione associati
    var prevCover = document.createElement("img");
    prevCover.id = "animate-prev";
    prevCover.classList.add("cover-box");
    prevCover.classList.add("cover-box-prev");
    prevCover.src = "./music/"+track[0]+"/"+track[1]+"/cover.jpg";
    if(!BEGIN_INDEX)
        prevCover.style.display = "none";

    var curNextCover = document.getElementById("next-cover");
    var curCover = document.getElementById("cover");
    var curPrevCover = document.getElementById("prev-cover");

    // Inserisco il nuovo elemento cover
    curPrevCover.parentNode.append(prevCover);

    // Animazione in JQuery
    $("#animate-prev").animate({
        left: "+=170",
    }, 50, function() {});

    var newLeft = $('#cover-box').width() / 2 - 80;
    $("#prev-cover").animate({
        left: newLeft,
    }, 50, function() {});

    newLeft = $('#cover-box').width() + 130;
    $("#next-cover").animate({
        left: newLeft,
    }, 50, function() {});

    newLeft = $('#cover-box').width() - 40;
    $("#cover").animate({
        left: newLeft,
    }, 50, function() {});

    prevCover.id = "prev-cover";
    curPrevCover.id = "cover";
    curCover.id = "next-cover"; 
    curNextCover.remove();
}