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