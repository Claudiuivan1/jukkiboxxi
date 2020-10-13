// Shortcut per accedere alla form
var doc = document.registrazione;

// Suffisso utilizzato per gli id dei campi di display d'errore nella form
var invalidSuffix = "Invalid";

// Id dei campi della form di registrazione e del modal di cambio password con indice annesso
var ids = [
    "nome",         //0
    "cognome",      //1
    "eta",          //2
    "email",        //3
    "user",         //4
    "password",     //5
    "cpassword",    //6
    "npassword",    //7
    "cnpassword"    //8
];

// Funzione che valida la form. I risultati delle funzioni vengono memorizzati su variabili e valutati tutti insieme per permettere il
// la visualizzazione simultanea di tutti gli avvisi d'errore
function validateForm(){
    var check_nom = validateNominativo();
    var check_user = validateUsername();
    var check_password = validatePassword(5) && validateBothPasswords(5);
    return check_nom && check_user && check_password;
    //la separazione dei campi permette ai testi d'errore di apparire contemporaneamente
}

// Funzione che valida i campi 'nuova password' e 'conferma nuova password'
function validateNewPassword(){
    return validatePassword(7) && validateBothPasswords(7);
}

// Funzione che visualizza l'avviso di errore associato al campo con id 'id' nel caso in cui le funzioni di validazione intercettino
// un errore
function displayInvalid(id){
    var invid = id+invalidSuffix;
    document.getElementById(invid).removeAttribute("hidden");
}

// Funzione complementare alla precedente che occulta l'avviso di errore associato al campo con id 'id' enl caso in cui le funzioni
// di validazione non intercettino un errore. Questa funzione viene invocata nel momento in cui l'utente, selezionando il campo da
// correggere, toglie il focus da tale campo
function hideInvalid(id){
    var invid = id+invalidSuffix;
    document.getElementById(invid).setAttribute("hidden", true);
}

// Funzione che valuta i valori dei campi 'nome' e 'cognome' verificando che siano stringhe di caratteri validi per un nome (ad esempio,
// un numero in qualsiasi posizione invalida la corretteza del campo)
// Invece di ritornare direttamente, viene introdotta una variabile di ritorno che permette la visualizzazione simultanea degli avvisi di
// errore
function validateNominativo(){
    var nameid = ids[0];
    var nome = document.getElementById(nameid).value;
    var surnameid = ids[1];
    var cognome = document.getElementById(surnameid).value;
    var check = true;
    
    for (var i = 0; i<nome.length; i++){
    	if (!isNaN(parseInt(nome[i]))){
            displayInvalid(nameid);
            check = check && false;
        }
    }
    for (i = 0; i<cognome.length; i++){
    	if (!isNaN(parseInt(cognome[i]))){
            displayInvalid(surnameid);
            check = check && false;
        }
    }
    return check;
}

// Funzione che valida il campo 'user' controllando che la sua lunghezza non sia minore della minima e che il suo primo carattere non sia
// un numero
function validateUsername(){
    var userid = ids[4];
	var user = document.getElementById(userid).value;
    if (user.length < 4 || !isNaN(parseInt(user[0]))){
        displayInvalid(userid);
        return false;
    }
    return true;
}

// Funzione che valida il campo 'password' controllando che la sua lugnhezza sia compresa tra gli estremi e che contenga almeno un 
// carattere alfabetico minuscolo, un carattere alfabetico maiuscolo, un carattere numerico. 
function validatePassword(id){
    if (debug) console.log("validating password");
    var passwordid = ids[id];
    var password = document.getElementById(passwordid).value;
    
    if (password.length < 8){
        if (id==5) displayInvalid(passwordid);
        return false;
    }
    
    var hasInt = false;
    var hasCapital = false;
    var hasIncapital = false;
    for (var i = 0; i < password.length; i++){
    	if (!isNaN(parseInt(password[i]))) hasInt = true;
        if (password[i]>='a' && password[i]<='z') hasIncapital = true;
        if (password[i]>='A' && password[i]<='Z') hasCapital = true;
        if (hasInt && hasCapital && hasIncapital) return true;
    }

    if (id==5) displayInvalid(passwordid);
    return false;
}

// Funzione che controlla che i due campi password, 'password' e 'cpassword, siano uguali. è superfluo ricontrollare la correttezza del
// secondo campo perchè è sufficiente controllare che la loro lunghezza sia uguale e i loro caratteru siano uguali nel significato e
// nell'ordine (infatti i controlli 'primari' già sono stati effettuati sul primo campo)
function validateBothPasswords(id){
    if (debug) console.log("validating both passwords");
	var passwordid = ids[id];
    var password = document.getElementById(passwordid).value;
    var cpasswordid = ids[id+1];
    var cpassword = document.getElementById(cpasswordid).value;
    if (password.length != cpassword.length){
        if (id == 5) displayInvalid(cpasswordid);
        return false;
    }
   	for (var i = 0; i < password.length; i++){
    	if (password[i]!=cpassword[i]){
            if (id == 5) displayInvalid(cpasswordid);
        	return false;
        }
    }
    return true;
}