<?php

/**
 * functions.php
 */

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/includes/functions.php');

\Sofokus\Theme\load_localizations();

\Sofokus\Theme\theme()->initialize();
