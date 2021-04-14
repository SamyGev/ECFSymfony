Projet fait par Samy GEVERS, formation AFPA (titre dev web & web mobile) à Marseille Saint Jerome.
Fait avec Symfony - composer (merci la CLI).

Pour lancer le projet, il faut avoir php d'installé, puis lancer un serveur local grâce à

php bin/console server:start
OU BIEN
symfony server:start

Données générées aléatoirement en attendant de remplir manuellement la base.
pour refaire des données aléatoires, lancer la commande 

php bin/console doctrine:fixtures:load

(Il faut doctrine, et fixtures d'installés)


https://www.php.net
https://www.doctrine-project.org
https://getcomposer.org
composer require --dev orm-fixtures (dépendance fixtures)
composer require fzaninotto/faker


https://github.com/fzaninotto/Faker (Utilisation de Faker avec fixtures)

Merci à Thierry pour la motivation et les cours de qualité ++
A Yannis pour sa patience, à nous expliquer symfony malgré les contretemps
Aux copains de l'AFPA pour le support au quotidien
Au thermos de café, meilleur ami du développeur amateur en période d'ECF.