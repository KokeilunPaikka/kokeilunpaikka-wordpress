<?php

namespace Sofokus\Theme;

/**
 * functions.php
 *
 * @package Sofokus\Theme
 */


/**
 * Grab a theme instance.
 *
 * @return Theme
 */
function theme()
{
    global $theme;

    if (!$theme instanceof Theme) {
        $theme = new Theme();
    }

    return $theme;
}

/**
 * Load the plugin text domain for this plugin.
 *
 * @return void
 */
function load_localizations()
{
    add_action('after_setup_theme', function () {
        load_theme_textdomain('theme', dirname(__DIR__) . '/languages/');
    });
}

/*
 * Enable full width Gutenberg embed
 */
add_theme_support( 'responsive-embeds' );

/*
 * Enable featured images
 */
add_theme_support( 'post-thumbnails' );


