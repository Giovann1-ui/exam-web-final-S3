- [x] page dashboard (GIOVANNI)
    - [x] liste des villes avec les besoins
        - [x] fonction pour récupérer les villes et leurs besoins depuis la base de données
        - [x] controller besoinVille
        - [x] modele besoinVille
    - [x] les dons attribués chaque ville
    - [x] bouton vers la page de gestion des dons
    - [x] bouton vers la pges d'ajout d'un don

- [x] page de gestion des dons (TSOA)
    - [x] liste des dons
    - [x] bouton vers une page pour ajouter un don
    
- [ ] page pour ajouter un don (MANA)
    - [x] formulaire pour ajouter un don
    - [x] validation du formulaire
    - [ ] fonction pour ajouter un don à la base de données
    - [ ] fonction qui attribue automatiquement le don à une ville si le besoin correspond
    - [ ] redirection vers la page de gestion des dons après l'ajout

- [ ] page des besions restants par ville (GIOVANNI)
    - [ ] bouton a côté de chaque ville pour acheter les besoins restants avec les dons en argent
    - ! Si on essaye d'acheter du riz alors qu'il y a encore du riz dans le stock, on affiche une erreur

- [ ] page de simulation (MANA)
    - [ ] bouton simuler qui permet de voir le résulat (simuler distribution pour visualier ou va les dons)
    - [ ] bouton validation qui dispatch vraiment les dons

- [x] Créer la classe `RecapModel`.
  - [x] **Méthode `getTotalBesoins()`** : Requête SQL `SUM(quantite * prix_unitaire)` sur `besoins_ville` + `besoins`.
     - [x] **Méthode `getSatisfait()`** :
        - [x] Somme des distributions validées.
        - [x] Somme des achats effectués (incluant les frais x%).
  - [x] **Méthode `getRecapData()`** : Retourne un tableau associatif avec `total`, `satisfait`, et `restant` (Total - Satisfait).

  - [x] Créer la méthode pour la route `/recap` : appelle `Flight::render('recap.php')`.
    - [x] **Méthode `getRecapJSON()`** :
      - [x] Instancier `RecapModel`.
      - [x] Récupérer les données du modèle.
      - [x] **json_encode** : Renvoyer les données via `Flight::json($data)`.

  - [ ] Recap.php **Structure HTML (Bootstrap)** :
    - [ ] Créer 3 conteneurs (Cards) :
      - [ ] Besoins totaux.
      - [ ] Satisfaits.
      - [ ] Restants.
      - [ ] Donner un **ID unique** à chaque zone de texte (ex: `id="total-montant"`, `id="satisfait-montant"`, `id="restant-montant"`).
      - [ ] Ajouter un bouton "Actualiser".

  - [ ] **Fonction JS `refreshData()`** :
    - [ ] Déclenchée par le clic sur le bouton "Actualiser".
    - [ ] Utiliser `fetch('/api/recap')` (Route Flight vers le controller).
    - [ ] Dans le `.then()`, parser le JSON.
    - [ ] Mettre à jour le contenu HTML des cartes via `innerHTML` ou `innerText`.
  - [ ] **Auto-load** : Ajouter l'appel à la fonction au chargement (`window.onload`).