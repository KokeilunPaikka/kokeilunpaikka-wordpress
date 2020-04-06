<?php

namespace Sofokus\Plugin\Navigation;

class NavigationLocations
{
    public function __construct()
    {
        add_action('init', [$this, 'registerMenuLocations']);
    }

    /**
     * Register navigation locations.
     */
    function registerMenuLocations()
    {
        register_nav_menus(
            array(
                'header-menu' => __('Header Menu', 'plugin'),
            )
        );

        register_nav_menus(
            array(
                'footer-menu-1' => __('Footer Menu 1', 'plugin'),
            )
        );
        register_nav_menus(
            array(
                'footer-menu-2' => __('Footer Menu 2', 'plugin'),
            )
        );
    }
}
