# dotenv
Simple .env file parser and ENV loader (`.env` to `getenv()` and `$_ENV`) based on standard PHP INI parser (parse_ini_file).

![Version 1.0](https://img.shields.io/badge/version-1.0-yellow.svg)
![Build Passing](https://img.shields.io/badge/build-passing-green.svg)
![PHP 7.0](https://img.shields.io/badge/php-7.0-blue.svg)

[![balint-horvath/dotenv-php](https://img.shields.io/badge/packagist-balint%E2%80%94horvath%2Fdotenv%E2%80%94php-blue.svg)](https://packagist.org/packages/balint-horvath/dotenv-php)

**Supported methods:**
- **getenv(variable)**
- **getenv(section_variable)**
- **$_ENV[variable]**
- **$_ENV[section_variable]**
- **$dotenv->variable**
- **$dotenv->section->variable**
- **$dotenv[variable]**
- **$dotenv[section][variable]**

# Examples
- [Usage Example (PHP Code)](/examples/usage.php)
- [Example INI (.ini)](/examples/.env)

# Installation

To install this library, you need to use Composer in your project. If you are not using Composer yet, here's how to install:

```bash
curl -sS https://getcomposer.org/installer | php
```

## via Composer

```bash
composer require balint-horvath/dotenv-php
```

### Phar
```bash
php composer.phar require balint-horvath/dotenv-php
```

# Example Environment File (INI) (.env)

```ini
#.env
[API]
apiUser = User
apiKey = Key
```

# Usage (Instance)

## Class

**Namespace**: `\BalintHorvath\DotEnv\`

**Class**: `DotEnv`

```php
new \BalintHorvath\DotEnv\DotEnv($path)
```

## Properties
- `(string)` **path**: Directory of .env file or full path to your ini file. (default: `../../../`)
- `(bool)` **setEnvironmentVariables**: If it's `true`, variables will be available via environment (`$_ENV`, `getenv()`), otherwise (if `false`) they'll be available only via the DotEnv as object or array (`$dotenv->` `$dotenv[]`). (default: `true`)
- `(bool)` **processSections**: If it's `true`, variables will be organized under sections (`$dotenv->section` `$dotenv[section]`), otherwise sections will have no matter. (default: `true`)
- `(bool)` **scannerMode**: If it's `INI_SCANNER_TYPED`, values `0`/`off`/`"false"`/`false` will become `bool` `false`, values `1`/`on`/`"true"`/`true` will become `bool` `true`. Can either be `INI_SCANNER_NORMAL` or `INI_SCANNER_RAW`. If `INI_SCANNER_RAW` is supplied, then option values will not be parsed. 
(*See [PHP Manual: parse_ini_file](http://php.net/manual/en/function.parse-ini-file.php) and [PHP Manual: Predefined Constants](http://php.net/manual/en/filesystem.constants.php) for more.*)
(default: `INI_SCANNER_TYPED`)

## Example
```php

define('APP_DIR', dirname(__FILE__) . '/');

require 'vendor/autoload.php';

$dotenv = new \BalintHorvath\DotEnv\DotEnv(APP_DIR);

```

# Getting environment variables

## Object Access

**Usage:**
```php
    $dotenv->{variable}
    $dotenv->{section}->{variable}
```

**Example:**
```php
    $dotenv->API->apiUser
```

```php
    API User: <?=$dotenv->API->apiUser?>
    API Key: <?=$dotenv->API->apiKey?>
```



## ENV Access ($_ENV)

**Usage:**
```php
    $_ENV['{variable}']
    $_ENV['{section}']['{variable}']
```

**Example:**
```php
    $_ENV['API_apiUser']
```
```php
    API User: <?=$_ENV['API_apiUser']?>
    API Key: <?=$_ENV['API_apiKey']?>
```

## ENV Access (getenv)

**Usage:**
```php
    getenv('variable')
    getenv('section_variable')
```

**Example:**
```php
    getenv('API_apiUser')
```
```php
    API User: <?=getenv('API_apiUser')?>
    API Key: <?=getenv('API_apiKey')?>
```

## Array Access

**Usage:**
```php
    $dotenv[{variable}]
    $dotenv[{section}][{variable}]
```

**Example:**
```php
    $dotenv['API']['apiUser']
```
```php
    API User: <?=$dotenv['API']['apiUser']?>
    API Key: <?=$dotenv['API']['apiKey']?>
```


# Dependencies
- [PHP 7 (php:>=7.0)](http://php.net/)

# Developer Dependencies
- [Kahlan 4 (kahlan/kahlan:^4.0)]((https://kahlan.github.io/docs/))

# Unit & BDD Test
This package has included test cases for [Kahlan](https://kahlan.github.io/docs/).


# PSR

## PSR-4 Autoload
- \\BalintHorvath\\DotEnv\\
