<?php

namespace Sofokus\Theme;

/**
 * Class Theme
 *
 * @package Sofokus\Theme
 */
class Theme
{
    /**
     * Has this theme instance been initialized?
     *
     * @access protected
     * @var bool
     */
    protected $initialized = false;

    /**
     * Initialize this theme instance.
     *
     * Note: the WP `init` hook has presumably not run yet when calling this method,
     * so hook to it in case something doesn't seem to work as expected.
     *
     * @global string $pagenow
     * @return void
     */
    public function initialize()
    {
        global $pagenow;

        // Prevent reinit.
        if ($this->initialized) {
            return;
        }

        $this->initializeGlobal();

        $is_admin = stripos($pagenow, 'wp-login.php') !== false || is_admin();

        if ($is_admin) {
            $this->initializeAdmin();
        } else {
            $this->initializeFrontend();
        }

        $this->initialized = true;
    }

    /**
     * Global theme related inits.
     *
     * @access protected
     * @return void
     */
    protected function initializeGlobal()
    {
        //
    }

    /**
     * Admin panel related inits.
     *
     * @access protected
     * @return void
     */
    protected function initializeAdmin()
    {
        add_action('admin_head', [$this, 'gutenbergFullWidth']);
    }

    function gutenbergFullWidth()
    {
        ?>
        <style>
            #editor .wp-block {
                max-width: none;
            }
        </style>
        <?php
    }

    /**
     * Frontend related inits.
     *
     * @access protected
     * @return void
     */
    protected function initializeFrontend()
    {
        // Scripts and styles.
        add_action('wp_enqueue_scripts', [$this, 'setupFrontendScriptsAndStyles']);
    }

    /**
     * Scripts and styles for the frontend. Anything registered and enqueued here
     * will _not_ be available inside wp-admin or wp-login.php.
     *
     * @return void
     */
    public function setupFrontendScriptsAndStyles()
    {
        wp_register_script('theme-global', get_stylesheet_directory_uri() . ($this->asset_path('js/global.js')),
            ['jquery'],
            null, true);
        wp_enqueue_script('theme-global');

        wp_register_style('theme-global', get_stylesheet_directory_uri() . ($this->asset_path('css/global.css')),
            [], null);
        wp_enqueue_style('theme-global');
    }

    /**
     * @param  string $filename
     * @return string
     */
    private function asset_path($filename)
    {
        $manifest_path = get_stylesheet_directory() . '/assets/rev-manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = [];
        }


        if (array_key_exists($filename, $manifest)) {
            $filename = 'build/' . $manifest[$filename];
        }

        return '/assets/' . $filename;
    }
}
