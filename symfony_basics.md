## Install

Dependencies composer and php
```
brew install composer php
```

PHPStorm Plugins
Symfony (activate), PHP Annotation, PHP Toolbox


##### Create a project
```
composer create-project symfony/website-skeleton my-project
```

##### Run project
```
./bin/console server:run
```

## Install (Optional)

##### Install web server manually (symfony/skeleton)
```
composer require server
```

##### Install profiler manually (black bar)
```
composer require profiler --dev
```

##### Unpack a dependency (black bar)
```
composer require debug --dev
composer unpack debug
```

## Setting up existing project

```
composer install
```

## Useful commands

##### Security checker
```
composer require sensiolabs/security-checker --dev
./bin/console security:check
```

##### Remove cache
```
rm -rf var/cache/dev/*
```

##### Get routes

```
./bin/console debug:router
```

##### Logging
```
tail -f var/log/dev.log
```

##### Getting services List
```
./bin/console debug:autowiring
```

##### Profile
```
dump()
{{ dump() }}
```

## Setting up Routes

##### Controller
```
@Route("/", name="app_home")
```

##### Twig usage
```
{{ path('app_home') }}
{{ path('article_show', {id: 'test'}) }}
```

##### Twig general usage
```
https://twig.symfony.com/doc/2.x/
```

## MySQL
Change DATABASE_URL in `.env`.

##### Create the configured Database
```
./bin/console doctrine:database:create
```

##### Create a Table/Entity, rerun to add new fields
```
./bin/console make:entity
```

##### Create Table scheme, rerun to update scheme
```
./bin/console make:migration
```

##### Create Tables in Database, rerun to update database
```
./bin/console doctrine:migrations:migrate
```

##### Adding properties manually and generate getter/setter
```
./bin/console make:entity --regenerate
```

#####  MySQL add dummy data/fixtures
https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html#multiple-files
```
composer require --dev doctrine/doctrine-fixtures-bundle
```

##### Create new Fixture
```
./bin/console make:fixture
```

##### Load fixture into database
```
./bin/console doctrine:fixtures:load
```

## Useful Classes

##### Create new Controller
```
./bin/console make:controller
```

##### Create Login
```
./bin/console make:auth
```