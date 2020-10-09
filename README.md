# OC-P6-SnowTricks
OpenClassRooms 6th Project
Build a community website with symfony

## Installation
* clone the project
* go to project folder in a terminal
* type `composer install`
* type `npm install -g yarn`
* type `yarn encore production` or `yarn encore dev` for development build
* copy `.env` to `.env.local` and edit sql parameters
* type   `php bin/console make:migration`  
       `php bin/console doctrine:database:create`  
      ` php bin/console doctrine:migrations:migrate`  
       `php bin/console make:entity --regenerate`  
