<?php
declare(strict_types=1);

/**
 * wp-config.php
 */

// Autoload
require_once __DIR__ . '/vendor/autoload.php';

/**==================================================================================
 * ENVIRONMENT CONFIGURATION
 */
$running_tests = isset($_ENV['RUNNING_AUTOMATED_TESTS'])
    ? (bool) $_ENV['RUNNING_AUTOMATED_TESTS']
    : false;

if (!$running_tests) {
    $running_tests = file_exists(__DIR__ . '/.tests_running');
}

// Environment config
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();
$dotenv->required([
    'DB_NAME', 'DB_USER', 'DB_PASSWORD',
    'APP_DOMAIN', 'APP_PROTOCOL'
]);

/**==================================================================================
 * URLS AND PATHS
 */

if (env('APP_ALLOW_ANY_DOMAIN', false)) {
    define('WP_HOME', env('APP_PROTOCOL') . '://' . $_SERVER['HTTP_HOST']);
} else {
    define('WP_HOME', env('APP_PROTOCOL') . '://' . env('APP_DOMAIN'));
}

define('WP_REACT_URL', env('WP_REACT_URL'));
define('WP_SITEURL', WP_HOME);
define('WP_CONTENT_DIR', wp_content_root());
define('WP_CONTENT_URL', WP_HOME . '/wp-content');

/**==================================================================================
 * DATABASE CONFIGURATION
 */

define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_HOST', env('DB_HOST', '127.0.0.1'));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
define('DB_COLLATE', env('DB_COLLATION', 'utf8mb4_unicode_ci'));

$table_prefix = env('DB_PREFIX', 'wp_');

define('WP_POST_REVISIONS', env('WP_POST_REVISIONS', 8));

/**==================================================================================
 * SECURITY, FILESYSTEM, AUTH
 */

define('FS_METHOD', 'direct');

// Prevent file editors from showing up, also prevent plugins and themes from being
// installed inside wp-admin.
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);

define('FORCE_SSL_ADMIN', env('APP_PROTOCOL') === 'https');

/**==================================================================================
 * REDIS
 */

define('WP_REDIS_BACKEND_HOST', env('REDIS_HOST', '127.0.0.1'));
define('WP_REDIS_BACKEND_PORT', env('REDIS_PORT', 6379));

// Prefix for Redis cache values
define('WP_CACHE_KEY_SALT', env('REDIS_PREFIX', 'wp'));

/**==================================================================================
 * SMTP
 */

define('WP_SMTP_ENABLE', env('WP_SMTP_ENABLE', false));

define('WP_SMTP_AUTH', env('WP_SMTP_AUTH', true));
define('WP_SMTP_USER', env('WP_SMTP_USERNAME', 'myusername')); // only if auth true
define('WP_SMTP_PASS', env('WP_SMTP_PASSWORD', 'kissatkoiria')); // only if auth true
define('WP_SMTP_HOST', env('WP_SMTP_HOST', 'localhost'));
define('WP_SMTP_FROM', env('WP_SMTP_FROM', 'wordpress@localhost'));
define('WP_SMTP_FROM_NAME', env('WP_SMTP_FROM_NAME', 'WordPress'));
define('WP_SMTP_PORT', env('WP_SMTP_PORT', 1025));
define('WP_SMTP_SECURE', env('WP_SMTP_SECURE', 'tls')); // `tls` or `ssl`
define('WP_SMTP_DEBUG', env('WP_SMTP_DEBUG', false));

/**==================================================================================
 * DEBUGGING
 */

define('WP_DEBUG', env('APP_ENABLE_DEBUG', false));
define('SCRIPT_DEBUG', WP_DEBUG);
define('CONCATENATE_SCRIPTS', WP_DEBUG);

/**==================================================================================
 * SENTRY
 */

if (!WP_DEBUG && env('SENTRY_ENABLE', false)) {
    $release = file_exists(app_root() . '/.version')
        ? file_get_contents(app_root() . '/.version')
        : 'unknown';

    \Sofokus\WpSentry\Sentry::instance()
        ->initialize(env('SENTRY_DSN'))
        ->setEnvironment(env('SENTRY_ENVIRONMENT', 'unknown'))
        ->setRelease($release)
        ->setAppPath(app_root())
        ->register();
}

// If we have a local config we load it. Can be used for installation specifics such
// as multisite, filesystem, etc.
if (file_exists(__DIR__ . '/wp-config.local.php')) {
    require_once __DIR__ . '/wp-config.local.php';
}

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', wp_root() . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');