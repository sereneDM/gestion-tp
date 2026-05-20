# Diagramme de Classes

Ce diagramme présente les principales classes du système de gestion des TP.

Les classes `Administrateur`, `Etudiant` et `Enseignant` sont représentées comme des spécialisations de la classe `Utilisateur`. Dans l'implémentation Laravel, ces rôles sont stockés dans l'attribut `role` de la table `users`.

```mermaid
classDiagram
    direction TB

    class Utilisateur {
        +int id
        +string nom
        +string email
        +string mot_de_passe
        +string role
        +seConnecter()
        +seDeconnecter()
        +modifierProfil()
        +estAdministrateur()
        +estEtudiant()
        +estEnseignant()
    }

    class Administrateur {
        +gererUtilisateurs()
        +gererClasses()
        +gererParametres()
        +consulterStatistiques()
    }

    class Etudiant {
        +rejoindreClasse()
        +consulterCours()
        +consulterTP()
        +soumettreTP()
        +consulterProgression()
    }

    class Enseignant {
        +creerClasse()
        +modifierClasse()
        +creerTP()
        +corrigerSoumission()
        +faireAppel()
        +publierAnnonce()
    }

    class Classe {
        +int id
        +string nom
        +text description
        +string code_inscription
        +string statut
        +genererCodeInscription()
        +regenererCodeInscription()
        +activer()
        +archiver()
        +ajouterEtudiant()
        +retirerEtudiant()
    }

    class TP {
        +int id
        +string titre
        +text description
        +datetime date_limite
        +string statut
        +array pieces_jointes
        +publier()
        +modifier()
        +fermer()
        +ajouterPieceJointe()
    }

    class Soumission {
        +int id
        +text contenu
        +array pieces_jointes
        +decimal note
        +text commentaire_enseignant
        +string statut
        +datetime date_soumission
        +deposer()
        +modifier()
        +noter()
        +ajouterCommentaire()
    }

    class Presence {
        +int id
        +date date
        +string statut
        +text remarques
        +marquerPresent()
        +marquerAbsent()
        +marquerRetard()
        +modifierStatut()
    }

    class Publication {
        +int id
        +string type
        +string titre
        +text contenu
        +string piece_jointe
        +publier()
        +modifier()
        +supprimer()
    }

    class Commentaire {
        +int id
        +text contenu
        +ajouter()
        +modifier()
        +supprimer()
    }

    class Notification {
        +int id
        +string type
        +string titre
        +text message
        +string lien
        +bool est_lue
        +creer()
        +marquerCommeLue()
    }

    class Parametre {
        +int id
        +string cle
        +text valeur
        +string type
        +text description
        +obtenir()
        +definir()
        +modifier()
    }

    Administrateur --|> Utilisateur
    Etudiant --|> Utilisateur
    Enseignant --|> Utilisateur

    Enseignant "1" --> "0..*" Classe : enseigne
    Etudiant "0..*" --> "0..*" Classe : inscrit

    Classe "1" --> "0..*" TP : contient
    Enseignant "1" --> "0..*" TP : propose

    TP "1" --> "0..*" Soumission : recoit
    Etudiant "1" --> "0..*" Soumission : depose

    Classe "1" --> "0..*" Presence : possede
    Etudiant "1" --> "0..*" Presence : concerne

    Utilisateur "1" --> "0..*" Publication : publie
    Classe "1" --> "0..*" Publication : contient
    Publication "1" --> "0..*" Commentaire : contient
    Utilisateur "1" --> "0..*" Commentaire : ecrit

    Utilisateur "1" --> "0..*" Notification : recoit
    Administrateur "1" --> "0..*" Parametre : configure
```

## Description

Le diagramme montre les classes principales du système et leurs relations. `Utilisateur` est la classe générale utilisée pour l'authentification. Les classes `Administrateur`, `Etudiant` et `Enseignant` héritent de `Utilisateur` afin de représenter les différents rôles du système.

Une `Classe` est créée et gérée par un `Enseignant`. Un `Etudiant` peut être inscrit dans plusieurs classes. Une classe contient plusieurs `TP`, et chaque TP peut recevoir plusieurs `Soumission`. Le suivi des présences est représenté par la classe `Presence`.

Les classes `Publication`, `Commentaire` et `Notification` représentent la partie communication du système. La classe `Parametre` représente les paramètres généraux configurés par l'administrateur.
