//FETCH-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
const defaultResponseCallback = function(response) {
    if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
}

const defaultDataCallback = function(data) {
    console.log("Data :\n", data);
}

const defaultErrorCallback = function(error) {
    console.error('Erreur:\n', error);
}

function myFetch(formData, dataCallback, url, method, errorCallback = null, responseCallback = null, contentType = 'application/x-www-form-urlencoded') {
    fetch(url, {
        method: method,
        headers: { 'Content-Type': contentType },
        body: method == 'GET' || method == 'DELETE' ? null : new URLSearchParams(formData).toString() // DELETE et GET n'ont pas de body, les paramètres passent dans l'URL
    })
    .then(response => (responseCallback == null ? defaultResponseCallback(response) : responseCallback(response) ) )
    .then(data => (dataCallback == null ? defaultDataCallback(data) : dataCallback(data) ))
    .catch(error => (errorCallback == null ? defaultErrorCallback(error): errorCallback(error) ));
}

//FETCH-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//DOM-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function createP(text) {
    const p = document.createElement('p');
    p.textContent = text;
    return p

}

function createH1(text) {
    const h1 = document.createElement('h1');
    h1.textContent = text;
    return h1;
}

function createA(textContent, callback) {
    const button = document.createElement('a');
    button.textContent = textContent;
    button.addEventListener('click', function (event) {
        event.preventDefault();
        callback(event);
    });
    return button;
}

//DOM-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function recuperePokemin(idP,affichePokemin) {
    myFetch(null, affichePokemin, 'index.php?route=pokemin&id=' + idP, 'GET');
}

function recupereAttaque(idP,idA,idPd){
    myFetch(null, 'index.php?route=Attaque&pokemin=' + idP +"&attaque="+idA+"&pokeminDefenseur="+idPd, 'GET');
}

function affichePokemin1(data){
    const mainDiv=document.getElementById('pokemin1');
    mainDiv.appendChild(createH1("Nom du Pokemin : " + data.Nom));
    mainDiv.appendChild(createP("Couleur/Race du pokemin : " + data.Couleur));
    mainDiv.appendChild(createP("Cri du pokemin : " + data.Cri));
    mainDiv.appendChild(createP("Type du pokemin : " + data.Type));
    mainDiv.appendChild(createP("PV du pokemin : " + data.PV + "/" + data.PVmax + " PV"));
    mainDiv.appendChild(createP("Attaque 1 : " + data.Attaque1 + " degat de l'attaque " + data.degat + " PV"));
    mainDiv.appendChild(createP("Attaque 2 : " + data.Attaque2 + " degat de l'attaque " + data.degat2 + " PV"));
    mainDiv.appendChild(createP("Attaque soin : " + data.Attaque3 + " soin : " + data.soin + " PV"));

    

}


function affichePokemin2(data){
    const mainDiv=document.getElementById('pokemin2');
    mainDiv.appendChild(createH1("Nom du Pokemin : " + data.Nom));
    mainDiv.appendChild(createP("Couleur/Race du pokemin : " + data.Couleur));
    mainDiv.appendChild(createP("Cri du pokemin : " + data.Cri));
    mainDiv.appendChild(createP("Type du pokemin : " + data.Type));
    mainDiv.appendChild(createP("PV du pokemin : " + data.PV + "/" + data.PVmax + " PV"));
    mainDiv.appendChild(createP("Attaque 1 : " + data.Attaque1 + " degat de l'attaque " + data.degat + " PV"));
    mainDiv.appendChild(createA("Utilisé Attaque 1", function (event) {
        recupereAttaque(2,1, 1);
    }));
    mainDiv.appendChild(createP("Attaque 2 : " + data.Attaque2 + " degat de l'attaque " + data.degat2 + " PV"));
    mainDiv.appendChild(createP("Attaque soin : " + data.Attaque3 + " soin : " + data.soin + " PV"));
    

}




//exemple de create a 
// mainDiv.appendChild(createA("Utilisé Attaque 1", function (event) {
//     recuperePokemin(3,affichePokemin1);
// }));