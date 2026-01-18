Bienvenue sur le projet Backoffice. Il s'agit d'une application web complète développée avec Symfony 7 et Tailwind CSS, permettant la gestion administrative de produits, d'utilisateurs et de clients.

Ce projet met l'accent sur une architecture propre, une interface utilisateur soignée et des fonctionnalités avancées.

---------- Description d'installation ----------

# Symfony 7.4 Boilerplate 

Attention : Il vous faut PHP 8.2 pour faire fonctionner ce projet.

-------------- Pré-requis --------------

Voici les commandes à exécuter pour installer le projet :

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

# Chargement des données (Fixtures)
php bin/console doctrine:fixtures:load --no-interaction

4. Lancer le serveur

symfony server:start

Suite à cela le site sera disponible et accessible à l'adresse : 
http://127.0.0.1:8000

-------------- Application --------------

Une fois sur le site, les trois seules pages accessibles sont : la page d'accueil, la page de connexion, ainsi que la page des produits. Pour débloquer l'accès aux autres pages, vous pouvez vous connecter via les identifiants suivants :

# Utilisateur lambda

Email : user@test.com
Mot de passe : password

Ces identifiants servent à se connecter sur un compte lambda, sans droits particuliers.

# Utilisateur administrateur

Email : admin@test.com
Mot de passe : password

Ces identifiants permettent une connexion sur un compte possédant des droits supérieurs.
Il a accès à tout : Gestion des utilisateurs, produits, clients, import/export.

# Utilisateur manager

Email : manager@test.com
Mot de passe : password

Ces identifiants permettent également une connexion sur un compte possédant des droits supérieurs.
Il peut gérer les clients et les produits, mais pas les utilisateurs.

# ✨ Fonctionnalités Implémentées
Le projet respecte une architecture MVC stricte et inclut les fonctionnalités suivantes :

📊 Tableau de Bord (Dashboard)
Visualisation des statistiques clés (KPI) : Nombre d'utilisateurs, produits, clients.

Tableaux récapitulatifs des derniers ajouts.

Design responsive et épuré avec cartes statistiques.

📦 Gestion des Produits

Formulaire Multi-étapes : Création et édition de produits via un parcours dynamique (Type -> Détails -> Spécifique).
Logique conditionnelle : Gestion différenciée entre produits "Physiques" (Stock) et "Numériques" (Licence).
Tri et Filtres : Possibilité de trier par prix ou par nom via l'interface.

Import / Export :
Export CSV via un service dédié.
Import massif via une commande (app:import-products).

👥 Gestion des Clients

CRUD complet.
Validation stricte : Regex pour les noms/prénoms (lettres, tirets, espaces uniquement) et unicité de l'email.

Intégration d'avatars générés via les initiales.

🛠️ Gestion des Utilisateurs

Réservé aux administrateurs.
Gestion des rôles (Admin, Manager, User) et des accès via des Voters de sécurité.

💻 Commandes Personnalisées (CLI)

Des commandes Symfony ont été créées pour automatiser certaines tâches :

php bin/console app:import-products : Importe des produits depuis un CSV.
php bin/console app:create-client : Création interactive d'un client en ligne de commande.

# 🧪 Exécution des Tests

Le projet dispose de tests unitaires (PHPUnit) pour garantir la fiabilité des services et des entités.
Pour lancer la suite de tests :

php bin/phpunit

Cela vérifiera notamment :

Le bon fonctionnement du service d'export CSV.
La cohérence de l'entité Product.

# 🎥 Démonstration

-------------- Fin --------------

Courage pour les corrections
