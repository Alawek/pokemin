//FETCH-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
const defaultResponseCallback = function (response) {
    if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
}

const defaultDataCallback = function (data) {
    console.log("Data :\n", data);
}

const defaultErrorCallback = function (error) {
    console.error('Erreur:\n', error);
}

function myFetch(formData, dataCallback, url, method, errorCallback = null, responseCallback = null, contentType = 'application/x-www-form-urlencoded') {
    fetch(url, {
        method: method,
        headers: { 'Content-Type': contentType },
        body: method == 'GET' || method == 'DELETE' ? null : new URLSearchParams(formData).toString() // DELETE et GET n'ont pas de body, les paramètres passent dans l'URL
    })
        .then(response => (responseCallback == null ? defaultResponseCallback(response) : responseCallback(response)))
        .then(data => (dataCallback == null ? defaultDataCallback(data) : dataCallback(data)))
        .catch(error => (errorCallback == null ? defaultErrorCallback(error) : errorCallback(error)));
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

function createInput(type, name, placeholder, value, id = "", classes = "") {
    const input = document.createElement('input');
    input.type = type;
    input.name = name;
    input.id = id;
    input.className = classes;
    if (placeholder != null) {
        input.placeholder = placeholder;
    }
    if (value != null) {
        input.value = value;
    }
    return input;
}

function createList(arraylist, idElement = null) {
    const ul = document.createElement('ul');
    if (idElement) ul.id = idElement;
    let i;
    for (i = 0; i < arraylist.length; i++) {
        let li = document.createElement('li');
        li.textContent = arraylist[i];
        console.log(li);
        ul.appendChild(li);
    }
    return ul


}

function createButton(type, text) {
    const button = document.createElement('button');
    button.type = type;
    button.textContent = text;
    return button;
}

function prepareModal() {
    document.getElementById('myModal').style.display = "block";
    const container = document.getElementById('modalFormContainer');
    container.innerHTML = ''; // Conchita Martinez, fée du logis
    return container;
}

function closeModal() {
    cleanAndCloseModal();
}

function cleanAndCloseModal() {
    document.getElementById('modalFormContainer').innerHTML = '';
    document.getElementById('myModal').style.display = "none";
}



//DOM-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// inscription________________________________________________
class EmailAlreadyExistsError extends Error {
    constructor() {
        super();
    }
}

class PseudoAlreadyExistsError extends Error {
    constructor() {
        super();
    }
}

function doRegister() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$.!%*?&])[A-Za-z\d@.$!%*?&]{8,}$/;
    const pwdcRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$.!%*?&])[A-Za-z\d@.$!%*?&]{8,}$/;
    const pseudoRegex = /^[a-zA-Z0-9_-]{3,20}$/;
    const container = prepareModal();

    // Création du formulaire
    const form = document.createElement('form');
    form.id = 'registerForm';
    form.appendChild(createInput('text', 'email', 'Email', null, "emailInput"));
    form.appendChild(createInput('password', 'pwd', 'Mot de passe', null, "pwdInput"));
    form.appendChild(createInput('password', 'pwdc', 'Vérification Mot de passe', null, "pwdcInput"));
    form.appendChild(createInput('hidden', 'route', null, 'Compte'));
    form.appendChild(createInput('text', 'pseudo', 'Pseudo', null, "pseudoInput"));
    form.appendChild(createButton('submit', 'Créer le compte'));

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Désactiver le comportement par défaut

        const email = document.getElementById("emailInput").value;
        const password = document.getElementById("pwdInput").value;
        const pseudo = document.getElementById("pseudoInput").value;
        const pwdc = document.getElementById("pwdcInput").value;

        if (document.getElementById('errorList')) document.getElementById('errorList').remove();
        let error = [];

        if (!emailRegex.test(email)) {
            error.push("Le format de l'Email n'est pas bon");
            document.getElementById("emailInput").style.backgroundColor = "red";
        }
        if (!pwdRegex.test(password)) {
            error.push("Le format du mot de passe n'est pas bon");
            document.getElementById("pwdInput").style.backgroundColor = "red";
        }
        if (!pseudoRegex.test(pseudo)) {
            error.push("Le format du pseudo n'est pas bon");
            document.getElementById("pseudoInput").style.backgroundColor = "red";

        }

        if (!pwdcRegex.test(pwdc)) {
            error.push("Le format du mot de passe n'est pas bon");
            document.getElementById("pwdcInput").style.backgroundColor = "red";
        }
        if (email === '') {
            error.push("Le champ Email est vide");
            document.getElementById("emailInput").style.backgroundColor = "red";
        }
        if (password === '') {
            error.push("Le champ Mot de Passe est vide");
            document.getElementById('pwdInput').style.backgroundColor = "red";
        }

        if (error.length > 0) {
            form.appendChild(createList(error, "errorList"));
            return false;
        }

        // Côme
        const dataCallback = function (data) {
            alert("Compte créé avec succès, vous pouvez vous identifier");
            closeModal();
            accueil();
        };

        const responseCallback = function (response) {
            if (!response.ok) {
                switch (response.status) {
                    case 498:
                        if (response.statusText.endsWith(" Compte.login_UNIQUE")) {
                            throw new EmailAlreadyExistsError();
                        } else if (response.statusText.endsWith(" Compte.pseudo_UNIQUE")) {
                            throw new PseudoAlreadyExistsError();
                        }
                    default: throw new Error('Network response was not ok ' + response.statusText);
                }
            }
            return response.json();
        };

        const errorCallback = function (error) {
            if (error instanceof EmailAlreadyExistsError) {
                alert("L'email est déjà utilisé");
            } else if (error instanceof PseudoAlreadyExistsError) {
                alert("Le pseudo est déjà utilisé");
            } else {
                console.error('Création de compte', error);
            }
        }

        myFetch(new FormData(form), dataCallback, 'index.php', 'POST', errorCallback, responseCallback);
    });

    container.appendChild(form);
}

// Inscription________________________________________________

// Connexion/deconnexion_______________________________________________________________________________________________________________________________________________________
function doLogin() {
    const container = prepareModal();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Création du formulaire
    const form = document.createElement('form');
    form.id = 'loginForm';

    // Champs du formulaire
    form.appendChild(createInput('text', 'email', 'Email', null, "emailInput"));
    form.appendChild(createInput('password', 'pwd', 'Mot de passe', null, "pwdInput"));
    form.appendChild(createInput('hidden', 'route', null, 'Login'));
    form.appendChild(createButton('submit', 'Se connecter'));

    // Gestion de la soumission du formulaire
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (document.getElementById('errorList')) document.getElementById('errorList').remove();

        const email = document.getElementById("emailInput").value.trim();
        const password = document.getElementById("pwdInput").value.trim();
        let error = [];

        // Validation
        document.getElementById("emailInput").style.backgroundColor = "";
        document.getElementById("pwdInput").style.backgroundColor = "";

        if (!emailRegex.test(email)) {
            error.push("Le format de l'email est incorrect.");
            document.getElementById("emailInput").style.backgroundColor = "red";
        }
        if (email === "") {
            error.push("Le champ email est vide.");
            document.getElementById("emailInput").style.backgroundColor = "red";
        }
        if (password === "") {
            error.push("Le champ mot de passe est vide.");
            document.getElementById("pwdInput").style.backgroundColor = "red";
        }

        // Affichage des erreurs si nécessaire
        if (error.length > 0) {
            form.appendChild(createList(error, "errorList"));
            return;
        }

        // Requête AJAX via myFetch
        myFetch(
            new FormData(form),
            function (data) {
                if (data?.success) {
                    // Connexion réussie
                    sessionStorage.setItem("userId", data.idCompte);
                    sessionStorage.setItem("email", data.email);
                    sessionStorage.setItem("role", data.role.nom);
                    sessionStorage.setItem("pseudo", data.pseudo);

                    closeModal();
                    initCombat();
                } else {
                    const err = ["Identifiants incorrects."];
                    form.appendChild(createList(err, "errorList"));
                }
            },
            'index.php',
            'POST'
        );
    });

    container.appendChild(form);
}

function doLogout() {
    myFetch(null, () => { initCombat(); }, 'index.php?route=Logout', 'GET');
}

// Je vais aller chercher l'état de la session et voir ce que j'affiche
function manageLoginArea() {
    myFetch(null, afficheLoginZone, 'index.php?route=Session', 'GET');
}

function afficheLoginZone(sessionInfo) {
    console.log('sessionInfo:', sessionInfo);
    const loginArea = document.getElementById('loginArea');
    loginArea.innerHTML = '';
    if (sessionInfo.isLogged) {
        loginArea.appendChild(createA('Logout', doLogout));

    } else {
        loginArea.appendChild(createA('Login', doLogin));
        loginArea.appendChild(createA("S'inscrire", doRegister));
    }
}


function afficherUtilisateurConnecte() {
    console.log(sessionStorage);
    const pseudo = sessionStorage.getItem("pseudo");
    if (pseudo) {
        document.getElementById("user-info").textContent = `Connecté en tant que : ${pseudo}`;
    }
}

// Connexion/Deconnexion__________________________________________________________________________________________________________________________________________________________________
// Pokemin________________________________________________________________________________________________________________________________________________________
function recuperePokemin(idP) {
    myFetch(null, affichePokemin1, 'index.php?route=PokeminInstance&id=' + idP, 'GET');

}

function recuperePokemin2(idP) {
    myFetch(null, affichePokemin2, 'index.php?route=PokeminInstance&id=' + idP, 'GET');

}

function recupereAttaque(idInstance) {
    myFetch(null, afficherBoutonsAttaque, 'index.php?route=AttaquePokemin&idInstance=' + idInstance, 'GET');
}




function affichePokemin1(data) {
    const mainDiv = document.getElementById('pokemin1');
    mainDiv.innerHTML = "";
    mainDiv.appendChild(createH1("Nom du Pokemin : " + data.nom));
    mainDiv.appendChild(createP("Niveau : " + data.niveau));
    mainDiv.appendChild(createP("Type du pokemin : " + data.pokeminBase.idType1));
    mainDiv.appendChild(createP("PV du pokemin : " + data.pv + "/" + data.pvMax + " PV"));
    mainDiv.appendChild(createP("Mana : " + data.mana + "/" + data.manaMax + " mana"));
    const divAttaques = document.createElement('div');
    divAttaques.id = "boutons-attaque";
    mainDiv.appendChild(divAttaques);
    recupereAttaque(data.idInstance);


}


function affichePokemin2(data) {
    console.log(data);
    const secDiv = document.getElementById('pokemin2');
    secDiv.innerHTML = "";
    secDiv.appendChild(createH1("Nom du Pokemin : " + data.nom));
    secDiv.appendChild(createP("Niveau : " + data.niveau));
    secDiv.appendChild(createP("Type du pokemin : " + data.pokeminBase.idType1));
    secDiv.appendChild(createP("PV du pokemin : " + data.pv + "/" + data.pvMax + " PV"));
    secDiv.appendChild(createP("Mana : " + data.mana + "/" + data.manaMax + " mana"));
    // mainDiv.appendChild(createP("Attaque 1 : " + data.Attaque1 + " degat de l'attaque " + data.degat + " PV"));
    // mainDiv.appendChild(createP("Attaque 2 : " + data.Attaque2 + " degat de l'attaque " + data.degat2 + " PV"));
    // mainDiv.appendChild(createP("Attaque soin : " + data.Attaque3 + " soin : " + data.soin + " PV"));


}

function afficherBoutonsAttaque(attaques) {
    console.log("🎯 Attaques reçues :", attaques);
    const divAttaques = document.getElementById('boutons-attaque');
    divAttaques.innerHTML = "";

    attaques.forEach(attaque => {
        const bouton = document.createElement('button');
        bouton.textContent = attaque.attaque.nom + " (" + attaque.mana + " mana)";
        bouton.addEventListener('click', () => {
            executerAttaque(attaque, 4); // ID du pokemin cible
        });
        divAttaques.appendChild(bouton);
    });
}

function executerAttaque(attaque, cibleId) {
    const lanceur = attaque.pokeminInstance;
    const coutMana = attaque.mana;
    const degats = attaque.attaque.degat;
    const modif = attaque.pokeminInstance.intelligence;

    if (lanceur.mana < coutMana) {
        alert(`${lanceur.nom} n'a pas assez de mana pour utiliser ${attaque.attaque.nom} !`);
        return;
    }

    const nouveauMana = lanceur.mana - coutMana;

    myFetch(null, function (cible) {
        const valeurDegats = Math.round(degats * (1 + modif / 10));
        const nouveauPv = Math.max(0, cible.pv - valeurDegats);

        console.log(`🧮 Dégâts infligés à ${cible.nom} : ${valeurDegats}, PV restants : ${nouveauPv}`);

        const form = new FormData();
        form.append("route", "MajCbt");
        form.append("idLanceur", lanceur.idInstance);
        form.append("manaRestant", nouveauMana);
        form.append("idCible", cible.idInstance);
        form.append("pvRestant", nouveauPv);

        myFetch(form, function () {
            recuperePokemin(lanceur.idInstance);
            recuperePokemin2(cible.idInstance);
            recupereAttaque(lanceur.idInstance);

            // KO ?
            if (nouveauPv === 0) {
                setTimeout(() => alert(`${cible.nom} est KO ! ${lanceur.nom} a gagné !`), 100);
                soignerPokemin(lanceur.idInstance);
                soignerPokemin(cible.idInstance);
                return;
            }

            //  Contre-attaque de la cible
            myFetch(null, function (attaquesCible) {
                const attaqueRiposte = attaquesCible[Math.floor(Math.random() * attaquesCible.length)];
                console.log("fergregeggergg" + attaqueRiposte)
                if (!attaqueRiposte) return;

                const coutManaRiposte = attaqueRiposte.mana;
                const degatsRiposte = attaqueRiposte.attaque.degat;
                const modifRiposte = cible.intelligence;
                const manaRestantCible = cible.mana - coutManaRiposte;

                if (manaRestantCible < 0) {
                    console.log(`${cible.nom} n'a pas assez de mana pour riposter.`);
                    return;
                }

                const degatsInfliges = Math.round(degatsRiposte * (1 + modifRiposte / 10));
                const pvRestantLanceur = Math.max(0, lanceur.pv - degatsInfliges);

                const riposteForm = new FormData();
                riposteForm.append("route", "MajCbt");
                riposteForm.append("idLanceur", cible.idInstance);
                riposteForm.append("manaRestant", manaRestantCible);
                riposteForm.append("idCible", lanceur.idInstance);
                riposteForm.append("pvRestant", pvRestantLanceur);
                alert(`${cible.nom} a utilisé ${attaqueRiposte.attaque.nom} sur ${lanceur.nom} et lui a infligé ${degatsInfliges} dégats !`);

                myFetch(riposteForm, function () {
                    recuperePokemin(lanceur.idInstance);
                    recuperePokemin2(cible.idInstance);
                    recupereAttaque(cible.idInstance);

                    if (pvRestantLanceur === 0) {
                        setTimeout(() => alert(`${lanceur.nom} est KO ! ${cible.nom} a riposté avec succès !`), 100);
                        soignerPokemin(lanceur.idInstance);
                        soignerPokemin(cible.idInstance);
                        return;
                    }
                }, "index.php", "POST");

            }, 'index.php?route=AttaquePokemin&idInstance=' + cible.idInstance, 'GET');

        }, "index.php", "POST");

    }, 'index.php?route=PokeminInstance&id=' + cibleId, 'GET');
}

function soignerPokemin(idInstance) {
    myFetch(null, function (pokemin) {
        pokemin.pv = pokemin.pvMax;
        pokemin.mana = pokemin.manaMax;

        const form = new FormData();
        form.append("route", "MajCbt");
        form.append("idLanceur", pokemin.idInstance);
        form.append("manaRestant", pokemin.mana);
        form.append("idCible", pokemin.idInstance);
        form.append("pvRestant", pokemin.pv);

        myFetch(form, function () {
            if (pokemin.idInstance === 1) {
                recuperePokemin(pokemin.idInstance);
            } else {
                recuperePokemin2(pokemin.idInstance);
            }
            alert(`${pokemin.nom} a été soigné !`);
        }, "index.php", "POST");
    }, "index.php?route=pokemininstance&id=" + idInstance, "GET");
}




function initCombat(idPokemin1 = 1, idPokemin2 = 4) {
    myFetch(null, function (data) {
        if (data.isLogged) {

            recuperePokemin(idPokemin1);
            recupereAttaque(idPokemin1);
            recuperePokemin2(idPokemin2);
            afficherUtilisateurConnecte(); // Affiche le pseudo
            const healBtn = createButton('button', 'Soigner les Pokemin');
            healBtn.addEventListener('click', () => {
                soignerPokemin(1); // ou remplace par idPokemin1
                soignerPokemin(4); // ou remplace par idPokemin2
            });

            document.getElementById('user-info').appendChild(healBtn);

            manageLoginArea()
            console.log("✅ Utilisateur connecté, initialisation du combat...");

        } else {
            manageLoginArea()
            console.warn("⚠️ Utilisateur non connecté.");
            const mainDiv = document.getElementById('pokemin1');
            const secDiv = document.getElementById('pokemin2');
            const sessionDiv = document.getElementById("user-info");
            mainDiv.innerHTML = "";
            secDiv.innerHTML = "";
            sessionDiv.innerHTML = "";
        }
    }, 'index.php?route=Session', 'GET');
}


// Pokemin__________________________________________________________________________________________________________________________________________________________________________________

