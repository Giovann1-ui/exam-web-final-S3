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

- [ ] page des besions restants par ville (GIOVANNI)
    - [ ] bouton a côté de chaque ville pour acheter les besoins restants avec les dons en argent
    - ! Si on essaye d'acheter du riz alors qu'il y a encore du riz dans le stock, on affiche une erreur

- [ ] page de simulation (MANA)
    - [ ] saisie de dons, pas directement dans la fonctionnalite de dispatch "immediat". Juste insertion dans la table 'dons'
    - [ ] bouton simuler qui permet de voir le résulat (simuler distribution pour visualier ou va les dons)
        - (pas directement inserer dans la base de donnee, juste histoire de voir(visualiser) l'action va se passer      prochainement)
    - [ ] bouton validation qui dispatch vraiment les dons 
        - (insertion directe dans la base de ce qu'on venait de voir dans la simulation)