Bienvenue sur le projet SymfProject. Il s'agit d'une application e-commerce complète développée avec Symfony 7.3, permettant la gestion de produits, d'utilisateurs et de commandes.

---------- Description d'installation ----------

# Symfony 7.3 Boilerplate 

Attention : Il vous faut PHP 8.2 pour faire fonctionner ce projet.

-------------- Pré-requis --------------

Voici les commandes à éxecuter pour installer le projet :

1. Installer les dépendances PHP

composer install

2. Fichier ".env.local"

Vous devrez ajouter un fichier nommé ".env.local" à la racine du projet. 
Ce fichier possède la variable d'environnement permettant la création et la connexion à une base de données.
C'est un élément essentiel pour faire fonctionner le projet.
Ce fichier doit uniquement inclure la ligne suivante :

DATABASE_URL="mysql://root:@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"

3. Configurer la Base de Données

# Création de la base de données
php bin/console doctrine:database:create

# Exécution des migrations (création des tables)
php bin/console doctrine:migrations:migrate

# Chargement des fausses données (Fixtures)
php bin/console doctrine:fixtures:load --no-interaction

4. Lancer le serveur

symfony server:start

Suite à cela le site sera disponible et accessible à l'adresse : 
http://127.0.0.1:8000

-------------- Application --------------

Une fois sur le site, les deux seules pages accessibles sont : la page d'accueil, ainsi que la page des produits. Pour débloquer l'accès aux autres pages, vous pouvez vous connecter via les identifiants suivants :

# Utilisateur lambda

Email : user1@example.com
Mot de passe : password

Ces identifiants servent à se connecter sur un compte normal, sans droits particuliers.
Il existe 5 comptes différents utilisant donc comme emails : user1@example.com, user2@example.com, ..., user5@example.com.

# Utilisateur administrateur

Email : admin@example.com
Mot de passe : admin123

Ces identifiants permettent une connexion sur un compte possédant des droits supérieurs.
Il peut, par exemple, créer des nouveaux produits, supprimer et ajouter des utilisateurs, etc ...

-------------- Fin --------------

En vous souhaitant de joyeuses fêtes !!
Courage !