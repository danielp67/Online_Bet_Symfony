Création d'un diagramme de classe pour une application dédiée au pari sportif

<!-- *Les entités -->

Utilisateurs(joueur) | Concurrents | Epreuves Sportive | Type de sport | Pari | Sport

<!-- *Les informations -->

Utilisateur(Joueur)
--> nomUtilisateur
--> prenomUtilisateur
--> dateNaissanceUtilisateur
--> contactUtilisateur(tel,mail... à définir)
--> portefeuilleUtilisateur

??? Monnaie(virtuelle ou réelle )

<!--  -->

Pari
--> natureDuPari
--> pariMise
--> pariCote
--> PariHistorique
--> pariResultat
--> pariGain
--> pariGainHistorique

<!--  -->

Epreuve
--> nomEpreuve
--> lieuEpreuve
--> dateEpreuve
--> fuseauHoraireEpreuve
--> nbOpposants

<!--  -->

Sport
--> nomSport
--> nbJoueurs

??? equipement (ex-voiture,velo, )

<!--  -->

Epreuve, Sport --> TypeSport (sportIndividuel, sportEquipe, Les deux)

<!--  -->

Compétition
--> nomCompetition
--> dateDebut
--> dateFin
--> nbrEquipe
