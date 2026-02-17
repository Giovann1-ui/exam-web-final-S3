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
    
- [x] page pour ajouter un don (MANA)
    - [x] formulaire pour ajouter un don
    - [x] validation du formulaire
    - [x] fonction pour ajouter un don à la base de données
    - [x] fonction qui attribue automatiquement le don à une ville si le besoin correspond
    - [x] redirection vers la page de gestion des dons après l'ajout

- [x] page des besoins restants par ville (similaire a celle dans dashboard) (GIOVANNI)
    - [x] creation de table
        - [x] frais_achat_besoin
        - [x] achats_besoins
    - [x] lister les villes avec les besoins restants à acheter
    - [x] ligne de besoin cliquable qui redirige vers la page de saisie des achats
    - [x] petite indication de comment acheter (ex : cliquer sur le besoin pour acheter avec les dons en argent)
    - ! normalement, on utilise par ordre d'arrive l'argent
    - [x] Si on essaye d'acheter du riz alors qu'il y a encore du riz dans le stock, on affiche une erreur

 - [x] page de saisie des achats (GIOVANNI)
    - [x] afficher les informations du besoin (ville, nom du besoin, type de besoin, prix unitaire) pour aider à la saisie
    - [x] afficher argent à utiliser pour combler le besoin(ex : 1000 Ar)
    - [x] afficher argent total dispo
    - [x] formulaire pour saisir un achat
        - [x] quantite (preremplie avec la quantité du besoin restant)
    - [x] validation du formulaire
    - [x] fonction pour ajouter un achat à la base de données
    - [x] redirection vers la page liste des achats après l'ajout

- [x] page liste des achats (GIOVANNI)
    - [x] creer vue v_historique_achats_besoins
    - [x] liste des achats effectués par l'argent des dons
    - !! Colonnes suggérées : Date de l'achat, Ville, Article acheté, Quantité, Prix unitaire, Frais appliqués, et Total payé
    - [x] possibilité de filtrer par ville (ou par date)

- [x] page de simulation (MANA)
    - [x] saisie de dons, pas directement dans la fonctionnalite de dispatch "immediat". Juste insertion dans la table 'dons'
    - [x] bouton simuler qui permet de voir le résulat (simuler distribution pour visualier ou va les dons)
    - [x] bouton validation qui dispatch vraiment les dons

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

  - [x] Recap.php **Structure HTML (Bootstrap)** :
    - [x] Créer 3 conteneurs (Cards) :
      - [x] Besoins totaux.
      - [x] Satisfaits.
      - [x] Restants.
      - [x] Donner un **ID unique** à chaque zone de texte (ex: `id="total-montant"`, `id="satisfait-montant"`, `id="restant-montant"`).
      - [x] Ajouter un bouton "Actualiser".

  - [x] **Fonction JS `refreshData()`** :
    - [x] Déclenchée par le clic sur le bouton "Actualiser".
    - [x] Utiliser `fetch('/api/recap')` (Route Flight vers le controller).
    - [x] Dans le `.then()`, parser le JSON.
    - [x] Mettre à jour le contenu HTML des cartes via `innerHTML` ou `innerText`.
  - [x] **Auto-load** : Ajouter l'appel à la fonction au chargement (`window.onload`).
        - (pas directement inserer dans la base de donnee, juste histoire de voir(visualiser) l'action va se passer      prochainement)
    - [x] bouton validation qui dispatch vraiment les dons 
        - (insertion directe dans la base de ce qu'on venait de voir dans la simulation)

- [ ] page de reinitialisation de la base de données (TSOA)
    - [ ] bouton pour réinitialiser la base de données
    - [?] lorsqu'on clique sur le bouton, on supprime toutes les données de la base de données et on remet la base a ses donnees de depart

- [ ] page d'insertion de besoins (TSOA)
    - [ ] formulaire pour ajouter un besoin
    - [ ] validation du formulaire
    - [ ] fonction pour ajouter un besoin à la base de données

- [ ] modifier page simulation (MANA + GIOVANNI)
    - formulaire de distribution
        - [ ] selection de type de distribution
        - [ ] trois type de distribution
            - [x] par date de demande en premier (existe deja)
            - [ ] par demande minimum
            - [ ] proportionnelle
        - [ ] fonctions par demande minimum (MANA)
        - [ ] fonctions pour la distribution proportionnelle (GIOVANNI)
            - ex : si une ville A a besoin de 1 kg de riz et une ville B a besoin de 2 kg de riz et une ville C a besoin de 5 kg de riz et qu'on a 6 kg, alors on donne 1/6 a la ville A, 2/6 a la ville B et 5/6 a la ville C
    - [ ] quand on clique sur simuler, on prend aussi l'id du type de distribution