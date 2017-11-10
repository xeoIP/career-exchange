[![Career Exchange](https://i.imgur.com/XDjJZyZ.png)](https://career.exchange/)

The repository contains the platform source code for the new career exchange. A curated network with a diverse set of opportunities across a variety of companies, matching you with only those that best meet your unique skills, interests, preferences,  and priorities.*

[![Career Exchange](https://i.imgur.com/AoytKbQ.png)](https://career.exchange/)

## Table of contents

- [Installation](#installation)
    - [Composer](#composer)
- [Setup](#setup)
    - [Database](#database)
- [Links](#links)
- [Laravel Stuff](#laravel)

## Installation

### Composer

The latest version can be installed via composer. This is especially useful if you want to create new  installation automatically or play with the latest code. You need to install the [composer](https://getcomposer.org/) package first if it isn't already available:
```
php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer
```
## Setup

Coming soon...

## Links

* [Web site](https://career.exchange)
* [Documentation](https://bitbucket.org/bizpreneur/career-exchange-web/wiki/Home)
* [Pipelines](https://bitbucket.org/bizpreneur/career-exchange-web/addon/pipelines/home)
* [Issue tracker](https://bitbucket.org/bizpreneur/career-exchange-web/issues)
* [Source code](https://bitbucket.org/bizpreneur/career-exchange-web)

## Laravel Stuff

- Website: http://laravel.com/

## Tools

- Laravel cheatsheet: http://cheats.jesse-obrien.ca/
- Laravel 5 cheatsheet: https://aufree.github.io/laravel5-cheatsheet/
- Laravel IDE helper for PhpStorm: https://github.com/barryvdh/laravel-ide-helper

## Tutorials

- https://laravel.com/docs/5.2/quickstart
- https://laravel.com/docs/5.2/quickstart-intermediate

## Usage

Show version: `php artisan --version`

Show all commands: `php artisan list`

Show all routes: `php artisan route:list`

## Setup

Key: `php artisan key:generate`

### Secure Dotfiles on Apache

```
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## Setup Homestead with Vagrant

- https://laravel.com/docs/5.5/homestead

### Multiple PHP versions

Execute a command in the command line with a particular PHP version:

- `php5.6 artisan list`
- `php7.0 artisan list`
- `php7.1 artisan list`

### Fix authentication failure on bootup

Problem: `$ vagrant up` results in authentication failure.

> default: Warning: Authentication failure. Retrying...

Solution: SSH into the VM via `$ ssh vagrant@localhost -p 2222` and [remove and regenerate the private keys](http://stackoverflow.com/a/32106919/1815847):

```
wget https://raw.githubusercontent.com/mitchellh/vagrant/master/keys/vagrant.pub -O .ssh/authorized_keys
chmod 700 .ssh
chmod 600 .ssh/authorized_keys
chown -R vagrant:vagrant .ssh
```

## Mcrypt

Install Mcrypt via Homebrew: `brew install mcrypt`

### Multiple PHP versions

Install Mcrypt for a particular PHP Version (Ubuntu): `sudo apt install php5.6-mcrypt`

## Setup Permissions

Make sure to grant Apache access to store data within `storage`:
- `storage/app`
- `storage/framework`
- `storage/logs`

See: [https://gist.github.com/hofmannsven/8392477#permissions](https://gist.github.com/hofmannsven/8392477#permissions)

## [Logs](https://laravel.com/docs/master/errors)

Daily logs: Add `APP_LOG=daily` to your `.env` config file (see `config/app.php`).
