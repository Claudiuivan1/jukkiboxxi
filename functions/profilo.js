// Funzione attivata al click su un elemento della top5
// Evidenzia tale elemento
function changeTop(id){
    var i = id[3]-1;
    document.getElementById("top_cover").src = "../music/"+top5[i][0]+"/"+top5[i][1]+"/cover.jpg";
    document.getElementById("curr_top_song").innerHTML = printSong(top5[i]);
}

// Inizializza la top5 (Ultimi ascolti)
// Evidenzia la canzone ascoltata più di recente
function initTopFive() {
    top5 = JSON.parse(localStorage.getItem("top5"));
    for (var i = 0; i < 5; i++){
        var id = "top"+(i+1)+"_list";
        document.getElementById(id).innerHTML = printSong(top5[i]);
        document.getElementById(id).setAttribute("onclick", "changeTop(this.id);");
    }
    document.getElementById("top_cover").src = "../music/"+top5[0][0]+"/"+top5[0][1]+"/cover.jpg";
    document.getElementById("curr_top_song").innerHTML = printSong(top5[0]);
}

// Funzione che resetta i campi nuova password
function reset() {
    document.getElementById("npassword").value = "";
    document.getElementById("cnpassword").value = "";
}

// Funzione che controlla che il genere cambiato sia valido e diverso dal precedente
function changeGenre() {
    if (document.getElementsByTagName("select")[0].value != "")
        return true;
    return false;
}