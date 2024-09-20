const words = [
    'chat', 'chien', 'maison', 'arbre', 'voiture', 'rue', 'pomme', 'fleur', 'bureau', 'porte',
    'fenetre', 'table', 'chaise', 'lampe', 'stylo', 'papier', 'livre', 'téléphone', 'ordinateur', 'clé',
    'montagne', 'rivière', 'plage', 'ville', 'campagne', 'jardin', 'plante', 'poulet', 'boeuf', 'poisson',
    'pain', 'fromage', 'vin', 'café', 'thé', 'bouteille', 'verre', 'assiette', 'fourchette', 'cuillère',
    'lampe', 'rideau', 'canapé', 'lit', 'matelas', 'couverture', 'oreiller', 'tapis', 'carte', 'route',
    'avion', 'train', 'bateau', 'bus', 'métro', 'vélo', 'motocyclette', 'moto', 'coeur', 'amour',
    'famille', 'ami', 'collègue', 'professeur', 'élève', 'enfant', 'adulte', 'sport', 'musique', 'film',
    'jeu', 'ordinateur', 'internet', 'site', 'application', 'message', 'réseau', 'ordinateur', 'programme',
    'code', 'clé', 'cadenas', 'journal', 'magazine', 'article', 'roman', 'poème', 'novella', 'scène',
    'théâtre', 'concert', 'exposition', 'musée', 'galerie', 'peinture', 'sculpture', 'photo', 'image',
    'dessin', 'illustration', 'cartoon', 'affiche', 'panneau', 'sign', 'enseign', 'bannière', 'écran'
];

function getRandomCombination() {
    const word1 = words[Math.floor(Math.random() * words.length)];
    const word2 = words[Math.floor(Math.random() * words.length)];
    const word3 = words[Math.floor(Math.random() * words.length)];
    return `${word1}-${word2}-${word3}`;
}

function updateUsername() {
    const usernameField = document.getElementById('register_form_username');
    const usernameMessage = document.getElementById('usernameMessage');

    usernameField.value = getRandomCombination();
    usernameMessage.textContent = ''; // Clear the message when generating a new username
    usernameField.style.borderColor = ''; // Clear the border color

    checkUsername(usernameField.value); // Immediately check the new random username
}

let debounceTimeout;

document.addEventListener('DOMContentLoaded', () => {
    const usernameField = document.getElementById('register_form_username');
    const usernameMessage = document.getElementById('usernameMessage'); // Récupère le span

    usernameField.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        const username = this.value;

        if (username.length >= 1) { // Vérifie que le nom d'utilisateur est suffisamment long
            debounceTimeout = setTimeout(() => {
                checkUsername(username);
            }, 500); // Délai de 500ms pour éviter des requêtes excessives
        } else {
            usernameMessage.textContent = 'Le nom d\'utilisateur doit contenir au moins 1 caractères.';
            usernameMessage.style.color = 'red';
        }
    });
});

function checkUsername(username) {
    fetch(`/check_username/${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(data => {
            const usernameField = document.getElementById('register_form_username');
            const usernameMessage = document.getElementById('usernameMessage'); // Récupère le span

            if (data.available === false) {  // Nom d'utilisateur déjà pris
                usernameMessage.textContent = 'Ce nom d\'utilisateur est déjà pris.';
                usernameMessage.style.color = 'red';
                usernameField.style.borderColor = 'red';
            } else {  // Nom d'utilisateur disponible
                usernameMessage.textContent = 'Ce nom d\'utilisateur est disponible.';
                usernameMessage.style.color = 'green';
                usernameField.style.borderColor = 'green';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification du nom d\'utilisateur :', error);
        });
}

