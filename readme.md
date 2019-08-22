# EliteNews
EN : A fake news website (no pun intended), written in PHP, with the web framework Symfony

FR : Un site d'actualité factice, écrit en PHP, avec Symfony !

Symfony version : 4.1.2

## Features :
EN : Article browsing, authorization-dependent CRUD access on articles, searching through database with keywords and categories, multiple user roles including administrator, moderator, writing editor, voting system for authorization access, account management and authorization-dependant CRUD access on it, commenting system complete with authorization-dependent CRUD access on comments, comments browsing, "smart" page navigation, wysiwig content writing with ckeditor, administration panel with easyadmin.
All pages are designed and their styles defined with great care using vanilla CSS.

FR : Affichage de résumé d'articles, consultation d'articles et accès CRUD en fonction du niveau d'autorisation, recherche asynchrone et discrimination par mots-clés et catégories, roles user multiples, incluant modérateur, éditeur, admin etc. Système de vote à critères multiples pour déterminer les droits d'accès à une ressource, gestion de compte user (CRUD) qui ne permet pour l'instant pas d'éditer les roles (il faut passer par l'admin SGBD), système de commentaire complet, avec accès CRUD sur les commentaires en fonction des niveaux d'autorisation, parcours de commentaires, affichage des puces de navigation intelligent, éditeur de contenu wysiwig avec ckeditor, page d'administration avec easyadmin.
Toutes les pages sont designées et leurs styles définis avec soin en vanilla CSS (bootstrap est utilisé pour certains formulaires).


--------------------------------------------------------------------------------------------------------------------------
INSTALLATION EN FRANÇAIS 
--------------------------------------------------------------------------------------------------------------------------

FR : 
Procédure d'installation :

1. Si vous ne l'avez déjà fait, télécharger et installer Composer, le package manager PHP

2. Dans la console, faites un cd vers le dossier où vous voulez installer le projet, puis faire :
- composer create-project symfony/skeleton EliteNews

Vous avez un prototype de projet, il ne reste plus qu'à installer quelques dépendances :

3. Faites un cd EliteNews, puis installez les dépendances suivantes grâce à composer (copiez dans la console) :
- composer require annotations
- composer require server --dev
- composer require maker --dev
- composer require sensiolabs/security-checker --dev
- composer require symfony/maker-bundle --dev
- composer require server --dev
- composer require orm-fixtures --dev
- composer require fzaninotto/faker --dev
- composer require symfony/profiler-pack
- composer require symfony/twig-bundle
- composer require symfony/asset
- composer require symfony/orm-pack
- composer require symfony/form
- composer require symfony/security-bundle
- composer require symfony/validator
- composer require sensio/framework-extra-bundle

4. Maintenant, copiez EliteNews en faisant :
- cd ..
- git clone https://github.com/GabrielGrrr/EliteNews.git

5. Configurer la base de donnée :
- cd EliteNews

Aller dans le fichier .env à la racine de votre projet, et configurer l'adapter en fonction du SGBD que vous utilisez,
par exemple si c'est MySQL (setup conseillé) avec le nom d'admin root, sans mot de passe, ce sera :
DATABASE_URL=mysql://root:@127.0.0.1:3306/Elite_News
Puis, faites :

- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate

Note :
Le SGBD sera forcément un SGBD SQL, je tape certaines requêtes en dur dans ce projet. SI VOUS VOULEZ VRAIMENT le faire migrer
vers du NOSQL, il faudra installer un bundle approprié, voir (pour Mongo) :
http://symfony.com/doc/master/bundles/DoctrineMongoDBBundle/index.html
Puis, remplacer les requêtes en dur dans les différents repositories par des utilisation du querybuilder,
et éventuellement optimiser le comportement de Symfony en ajoutant les annotations appropriées dans les entités.

6. Générer des données de test

Tapez simplement dans la console :
- php bin/console doctrine:fixtures:load

Patientez jusqu'à ce que la base de donnée soit remplie. Vous pouvez configurer la quantité d'entrées tests bidon insérées 
dans src/DataFixtures/BlogFixtures, au début de la fonction load.

7. Lancer l'application
- php bin/console server:run

Par défaut, l'appli est accessible sur le port 8000 de localhost (un lien clickable est visible dans la console)
http://localhost:8000

--------------------------------------------------------------------------------------------------------------------------
ENGLISH SETUP
--------------------------------------------------------------------------------------------------------------------------

EN : 
Setup process :

1. Download and install composer, if you haven't done so already

2. In your shell terminal, cd toward an appropriate setup folder, then enter :
- composer create-project symfony/skeleton EliteNews

You know have a skeleton project, but you'll need a few dependencies :

3. cd EliteNews, then install the following dependencies :
- composer require annotations
- composer require server --dev
- composer require maker --dev
- composer require sensiolabs/security-checker --dev
- composer require symfony/maker-bundle --dev
- composer require server --dev
- composer require orm-fixtures --dev
- composer require fzaninotto/faker --dev
- composer require symfony/profiler-pack
- composer require symfony/twig-bundle
- composer require symfony/asset
- composer require symfony/orm-pack
- composer require symfony/form
- composer require symfony/security-bundle
- composer require symfony/validator
- composer require sensio/framework-extra-bundle

4. Now, let's clone this repo :
- cd ..
- git clone https://github.com/GabrielGrrr/EliteNews.git

5. You'll still need to configure your database :
- cd EliteNews

In .env config file at the root of your folder, set an appropriate adapter. I use MySQL, so should you if you want 
this to work out-of-the-box. For example with a root admin, and no password, you'll have to write :
DATABASE_URL=mysql://root:@127.0.0.1:3306/Elite_News

Then populate the database with the appropriate tables :
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate

Warning :
You'll have to use an SQL adapter by default, since I use some hardcoded SQL requests. 
IF YOU REALLY WANT to migrate towards a Mongo, ES or any other NOSQL DB, you'll have to install an appropriate bundle : 
http://symfony.com/doc/master/bundles/DoctrineMongoDBBundle/index.html
Then, replacing the SQL requests in different repositories by some querybuilder calls, 
and optimize Symfony's default behavior with Mongo by adding the appropriate annotations in the entities (see the link above).

6. Generate fixtures
- php bin/console doctrine:fixtures:load

7. Launch
- php bin/console server:run

It's here :
http://localhost:8000
