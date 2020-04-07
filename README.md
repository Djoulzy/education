# education
Jeux pour apprendre

php bin/console make:controller <name> # Creation d'un squelette de controller + Twig
php bin/console make:entity # Creation d'un table en base

php bin/console debug:router
php bin/console cache:clear

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

Installation:

apt install php-xml php-gd php-zip php-intl php-curl php-pgsql

composer install
yarn install
yarn encore dev
yarn run encore dev --watch

su - postgres
createuser --interactive --pwprompt

DUMP:
pg_dump --format=d --no-owner --data-only --file=dump hotels
full: pg_dump edu > edu.dump

RESTORE
pg_restore --dbname=hotels --disable-triggers --table=user dump
pg_restore --dbname=hotels --disable-triggers --table=import dump
pg_restore --dbname=hotels --disable-triggers --table=entity dump
pg_restore --dbname=hotels --disable-triggers --table=filter dump
pg_restore --dbname=hotels --disable-triggers --table=arrivee dump
pg_restore --dbname=hotels --disable-triggers --table=occupation dump
pg_restore --dbname=hotels --disable-triggers --table=taux dump

pg_restore --dbname=hotels --disable-triggers --table=filter_group dump
pg_restore --dbname=hotels --disable-triggers --table=filter_in_group dump
full: psql hotels < hotels.dump

Utils:
Gestion Utilisateurs: http://blog.dev-web.io/2018/10/30/symfony-4-gestion-utilisateurs-sans-fosuserbundle-v2018-chapitre-1/

DEPLOY
composer install --no-dev --optimize-autoloader
yarn encore production --progress