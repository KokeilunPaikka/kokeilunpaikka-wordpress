<?php
declare(strict_types=1);

/**
 * Plugin Name: WordPress Sentry Integration
 * Description: Use Sentry for error and log aggregation.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (getenv('SENTRY_ENABLE') === 'true') {
    \Sofokus\WpSentry\Sentry::instance()->setupContext();
}
