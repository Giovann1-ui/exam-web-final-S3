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

- [ ] page de simulation (MANA)
    - [ ] bouton simuler qui permet de voir le résulat (simuler distribution pour visualier ou va les dons)
    - [ ] bouton validation qui dispatch vraiment les dons