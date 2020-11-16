# OC-P6-SnowTricks
OpenClassRooms 6th Project
Build a community website with symfony

# Prerequisite
* A Web Server (Apache, Nginx...)
* Php 7.3
* Yarn (`npm install -g yarn`)
* A Database engine (Mysql, PostgreSQL...)


## Installation
* clone or download the project
* go to project folder in a terminal
* type `composer install`
* type `npm install -g yarn`
* type `yarn encore production` or `yarn encore dev` for development build
* copy `.env` to `.env.local` and edit sql and smtp parameters
* type   `php bin/console make:migration`  
       `php bin/console doctrine:database:create`  
      ` php bin/console doctrine:migrations:migrate`  
 * To start with no data type `doctrine:fixtures:load --group=starting_users`
