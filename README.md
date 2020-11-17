# OC-P6-SnowTricks
OpenClassRooms 6th Project  
Build a community website with Symfony

[![SonarCloud](https://sonarcloud.io/images/project_badges/sonarcloud-white.svg)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)

[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P6-SnowTricks&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P6-SnowTricks&metric=alert_status)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P6-SnowTricks&metric=security_rating)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P6-SnowTricks&metric=bugs)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P6-SnowTricks&metric=code_smells)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P6-SnowTricks&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P6-SnowTricks)

Code Climate  
[![Maintainability](https://api.codeclimate.com/v1/badges/3c10ea86ff7ba94bf013/maintainability)](https://codeclimate.com/github/OSEvohe/OC-P6-SnowTricks/maintainability)
# Prerequisite
* A Web Server (Apache, Nginx...)
* Php 7.3
* Composer
* Yarn (`npm install -g yarn`)
* A Database engine (Mysql, PostgreSQL...)
* SMTP server accessible from the machine hosting the site


## Installation
* clone or download the project
* go to project folder in a terminal
* type `composer install`
* type `yarn install`
* type `yarn encore production` or `yarn encore dev` for development build
* copy `.env` to `.env.local` and edit sql and mail parameters
* configure a new Virtual host in your web server configuration with `public/` folder as DocumentRoot

Now set the database :   
`php bin/console doctrine:database:create`  
`php bin/console make:migration`    
`php bin/console doctrine:migrations:migrate`  
 * To start with no data : `php bin/console doctrine:fixtures:load --group=starting_users`
 * You can add samples data : `php bin/console doctrine:fixtures:load --group=samples_data`


## Secure your site
### Admin access 
By default, you can log as admin with :  
`Admin@snowtricks / Password` at url `/_sntrks_admin/`

#### Other users
Samples data come with 2 additional users, both user `Password` as password :
* john@snowtricks, a verified user, author of the 10 tricks
* user1@snowtricks, a new unverified user  
