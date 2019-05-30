# CS Gov

Basic Drupal distribution created to support needs of Czech and Slovak government and city website needs.

## Known Issues

* This repository is still a Work-in-Progress, and may and will change in time.

## Requirements

* PHP 7.2+
* MySQL, MariaDB or Percona Server
* Apache 2
* For detailed list of requirements see https://www.drupal.org/docs/8/system-requirements

## Basic installation

* Clone this repository.
* Run `composer install`
* Install the site as usual

## Frontend changes

* Theme is using Gulp workflow
* Go to the theme folder
* Run `npm run setup` to install the necessary node modules
* Run `npm run gulp dev` – produce development output with sourcemaps and uncompressed CSS, watches for changes
* Run `npm run gulp` – produce compressed output, delete temporary helper files.

***Happy codding!***
