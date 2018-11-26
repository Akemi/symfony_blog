Dependencies composer and php
```
brew install composer php
```

PHPStorm Plugins
Symfony (activate), PHP Annotation, PHP Toolbox


Create a project

```
composer create-project symfony/website-skeleton my-project
```

Install web server manually (symfony/skeleton)
```
composer require server
```

Install composer manually (black bar)
```
composer require profiler --dev
```

Install composer manually (black bar)
```
composer require debug --dev
composer unpack debug
```

Run project

```
./bin/console server:run
```

Setting up existing project

```
composer install
```

Security checker

```
composer require sensiolabs/security-checker --dev
./bin/console security:check
```

Remove cache
```
rm -rf var/cache/dev/*
```
Get routes

```
./bin/console debug:router
```
```
@Route("/", name="app_home")
```
```
{{ path('app_home') }}
{{ path('article_show', {id: 'test'}) }}
```

Logging
```
tail -f var/log/dev.log
```

Getting services List
```
./bin/console debug:autowiring
```

Twig
https://twig.symfony.com/doc/2.x/

Profile
```
dump()
{{ dump() }}
```

MySQL
Change DATABASE_URL in `.env` 

Create the configured Database
```
./bin/console doctrine:database:create
```

Create a Table/Entity, rerun to add new fields
```
./bin/console make:entity
```

Create Table scheme, rerun to update scheme
```
./bin/console make:migration
```

Create Tables in Database, rerun to alter database
```
./bin/console doctrine:migrations:migrate
```

Adding properties manually and generate getter/setter
```
./bin/console make:entity --regenerate
```

MySQL add dummy data/fixtures
https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html#multiple-files
```
composer require --dev doctrine/doctrine-fixtures-bundle
```
Create new Fixture
```
./bin/console make:fixture
```

Load fixture into database
```
./bin/console doctrine:fixtures:load
```

Create new Controller
```
./bin/console make:controller
```

Create Login
```
./bin/console make:auth
```