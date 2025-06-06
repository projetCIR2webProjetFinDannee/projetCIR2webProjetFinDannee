# projetCIR2webProjetFinDannee
## Description

Ce projet est un site web développé dans le cadre du module CIR2 de fin d'année. Il permet de gérer et de présenter différentes fonctionnalités selon le cahier des charges fourni.

## Fonctionnalités principales

- Affichage sur une carte
- Affichage de statistique
- Recherche en fonction de 3 paramètre select avec 20 élément aléatoire
- Affichage des détail de l'instalation au clique sur l'élément
- Login pour la partie administrateur
- Ajout, modification et suppression de contenus
- Interface utilisateur responsive
- Tableau de bord d'administration

## Technologies utilisées

- HTML, CSS, JavaScript
- PHP
- MySQL, postgres

## Installation

1. Cloner le dépôt :
    git clone https://github.com/votre-utilisateur/projetCIR2webProjetFinDannee.git
2. Placer les fichiers dans le dossier `/var/www/html/projetCIR2webProjetFinDannee`.
3. Créer la base de données SQL sur votre driver.
4. Exécuter les scripts du dossier sql pour construire la base de données.
    Éxécuter `sql/{driver}/model.sql`
    Éxécuter `sql/data.sql`
    Éxécuter `sql/{driver}/increment-fixer.sql`
5. Copier le fichier de configuration d'exemple vers `back/constants.php`, et modifier le fichier selon votre configuration

## Configuration serveur (Apache2)
1. Activer les modules nécessaires
    `a2enmod auth_digest`
    `systemctl restart apache2`
2. Créer un fichier de mot de passe avec htdigest
    Éxécuter: `htdigest -c /etc/apache2/.htdigest "Secure admin access" utilisateur`
    Le paramètre -c n'est à utiliser que lors de la création du fichier
    Il faut remplacer 'utilisateur' par votre login
    Le système vous demandera ensuite un mot de passe.
3. Créer une nouvelle configuration apache
    `cd /etc/apache2/sites-available`
    `cp 000-default.conf solarpanel.conf`
4. Changer la nouvelle configuration apache
    `nano solarpanel.conf`
    Ajouter au fichier ouvert la configuration indiquée dans le fichier `config.example`
5. Activer la configuration apache
    `a2ensite solarpanel.conf`
    `a2dissite 000-default.conf`
6. Redémarrer apache
    `systemctl restart apache2`

## Utilisation

Le site est déjà configuré à l'adresse `http://10.10.51.129/`. Les logins pour la partie serveur sont 'admin' avec le mot de passe 'Isen44' et un mot de passe en front '123'.

## Auteurs

Groupe 9
- Alexis ROCHON--SANZ
- Mathieu GICQUEL--BOURDEAU
- Mathis CHARTIER

## Licence

Ce projet est sous licence MIT.