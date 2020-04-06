<?php

/**
 * Plugin name: Motiva plugin
 * Author: Sofokus
 * Text Domain: plugin
 * Domain Path: /languages
 */

// Force plugin to be setup after base.
if (file_exists(__DIR__ . '/.cloned')) {
    echo "Package has not been <code>setup</code>, please see README.";

    exit;
}

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/includes/functions.php');

\Sofokus\Plugin\load_acf_fields();
\Sofokus\Plugin\load_localizations();

register_activation_hook(__FILE__, [\Sofokus\Plugin\Plugin::class, 'activate']);
register_deactivation_hook(__FILE__, [\Sofokus\Plugin\Plugin::class, 'deactivate']);

\Sofokus\Plugin\plugin()->initialize();
