# Resque app for Elefant

This is an app that integrates [PHP-Resque](https://github.com/chrisboulton/php-resque)
into the Elefant CMS, so you can easily add background tasks to your apps.

## Requirements

PHP-Resque requires [Redis 2.2+](http://redis.io/) as well as the
[PCNTL extension](http://ca1.php.net/manual/en/pcntl.installation.php).

## Installation

1. Install the app into the `apps/` folder.
2. Copy `apps/resque/conf/config.php` to `conf/app.resque.config.php` and edit the settings there.

## Adding jobs to the queue

First you need to initialize the app for adding jobs to the queue:

```php
<?php

// Initialize the Resque app
$this->run ('resque/init');

?>
```

After initializing the app, you can call `Resque::enqueue()` anywhere after that.

```php
<?php

// Enqueue a job after calling resque/init
Resque::enqueue ('queue_name', 'JobName', array ('arg1' => 'value'));

?>
```

## Running the workers

To start running the workers, use the following command:

```bash
$ ./elefant resque/run
```
