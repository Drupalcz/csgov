# CS Gov

Basic Drupal distribution created to support needs of Czech and Slovak government and city website needs.

## How to create new project in a minute.

We recommend to use Lando for local environment. If you don't want to use it, you should be good with any LAMP stack
complying with Drupal 8 requirements. You also need to have composer and PHP 7.2+ on your local machine.

- Create project using composer:
```
$ composer create-project drupalcz/csgov-project:"dev-8.x-1.x" MY_PROJECT
```

- Start lando:
``` 
$ cd MY_PROJECT
$ lando start
```
In the output you find URLs for your website.

- Install site:
``` 
$ lando drush site:install csgov --db-url="mysql://drupal8:drupal8@database/drupal8" -y
```
In the output you find admin login and password.

- Start using it:
Previous commands gave you URL and credentials. Go to the URL/user, fill in the credentials and you're good to go.

## Known Issues

* This repository is still a Work-in-Progress, and may and will change in time.

## Requirements

* PHP 7.2+
* MySQL, MariaDB or Percona Server
* Apache 2
* For detailed list of requirements see https://www.drupal.org/docs/8/system-requirements

***Happy codding!***
