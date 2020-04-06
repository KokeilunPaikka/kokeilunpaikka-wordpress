# sofokus/wp-sentry

Library which helps to tie WordPress installations into Sentry, an error
and log aggregation and management system.

## Usage

### Create a Sentry project

After this you should receive a Sentry DSN to use in configuration.

### Copy `sentry-init.php` to `mu-plugins`

The `sentry-init.php` file contains bootstrapping code to allow
WordPress runtime environment to be used in Sentry context
initialization.

### Configure

Sentry is only loaded in `mu-plugins` if the ENV variable
`SENTRY_ENABLE` is present and contains `'true'`. You can use phpdotenv
or similar to make ENV variable management simple.

### Implement

A good place to implement the Sentry helper is `wp-config.php`.

Inside `wp-config.php` you can insert the following to load and register
Sentry (replace the example values with correct ones):

    <?php

    if (getenv('SENTRY_ENABLE') === 'true') :
        \Sofokus\WpSentry\Sentry::instance()
            ->initialize('https://...') # insert Sentry DSN here
            ->setRelease('1.2.3')
            ->setEnvironment('production')
            ->setAppPath(__DIR__)
            ->register();
    endif;

After which Sentry should start receiving errors from WP.
