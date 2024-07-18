# CS Gov

Basic Drupal distribution created to support needs of Czech and Slovak government and city website needs.

## How to create new project in a minute.

You should be good with any LAMP stack complying with Drupal 10 requirements. You also need to have composer 2 and at least PHP 8.1 on your local machine.

- Create project using composer:
```
$ composer create-project drupalcz/csgov-project:"2.0.0" MY_PROJECT
```

- Install site:
```
$ drush site:install csgov
```
In the output you find admin login and password.

- Start using it:
Previous commands gave you URL and credentials. Go to the URL/user, fill in the credentials and you're good to go.

## Known Issues

* This repository is still a Work-in-Progress, and may and will change in time.

## Requirements

* PHP 8.1
* MySQL, MariaDB or Percona Server
* Apache 2
* For detailed list of requirements see https://www.drupal.org/docs/system-requirements

***Happy codding!***
