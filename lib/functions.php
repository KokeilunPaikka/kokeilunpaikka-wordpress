<?php
declare(strict_types=1);

/**
 * functions.php
 *
 * Application-wide functions.
 */

if (!function_exists('env')) :
    /**
     * Get an environment value.
     *
     * Uses PHP `getenv` which means raw `false` values are considered missing
     * values which should return the given default value.
     *
     * @param string $key Value to get.
     * @param mixed $default Default value to return in case env has no value.
     *
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        $initial = getenv($key);

        if ($initial === false) {
            return $default;
        }

        switch ($initial) {
            case 'false':
            case '"false"':
            case '(false)':
                $parsed = false;
                break;
            case 'true':
            case '"true"':
            case '(true)':
                $parsed = true;
                break;
            default:
                $parsed = $initial;
                break;
        }

        if (is_numeric($parsed)) {
            $parsed = (int) $parsed;
        } elseif (is_string($parsed)) {
            $parsed = trim(trim($parsed, '\'\"'));
        }

        return $parsed;
    }
endif;

if (!function_exists('app_root')) :
    /**
     * Get the application root directory path.
     *
     * @return string
     */
    function app_root() : string
    {
        return dirname(__DIR__);
    }
endif;

if (!function_exists('wp_root')) :
    /**
     * Get the public document root directory path.
     *
     * @return string
     */
    function wp_root() : string
    {
        return app_root() . '/public_html';
    }
endif;

if (!function_exists('wp_content_root')) :
    /**
     * Get the wp-content directory path.
     *
     * @return string
     */
    function wp_content_root() : string
    {
        return wp_root() . '/wp-content';
    }
endif;
