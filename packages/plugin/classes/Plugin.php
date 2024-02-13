<?php

namespace Sofokus\Plugin;

use Sofokus\Plugin\Api\Api;
use Sofokus\Plugin\Navigation\NavigationLocations;

/**
 * Class Plugin
 *
 * @package Sofokus\Plugin
 */
class Plugin
{
    /**
     * Has this instance been initialized?
     *
     * @access protected
     * @var bool
     */
    protected $initialized = false;

    /**
     * Initialize this instance.
     *
     * Note: the WP `init` hook has presumably not run yet when calling this method,
     * so hook to it in case something doesn't seem to work as expected.
     *
     * @return void
     */
    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        new NavigationLocations();
        new Api();

        add_action('init', [$this, 'registerStrings']);
        add_filter('wp_mail_from', [$this, 'changeFromAddress']);

        //initialize all blocks in blocks folder
        foreach (glob(dirname(__FILE__) . '/Blocks/*.php') as $file) {
            require_once $file;
            // get the file name of the current file without the extension
            // which is essentially the class name
            $class = basename($file, '.php');
            $class = "Sofokus\Plugin\Blocks\\" . $class;
            if (class_exists($class)) {
                $obj = new $class;
                $obj->__construct();
            }
        }

        add_filter('allowed_block_types_all', [$this, 'allowedBlockTypes'], 10, 2);

        $this->initialized = true;
    }

    /**
     * Plugin activation.
     *
     * Create roles, rewrite rules and other activation related thingies here.
     *
     * @static
     * @return void
     */
    public static function activate()
    {
        //
    }

    /**
     * Plugin deactivation.
     *
     * Remove roles, rewrite rules and other deactivation related thingies here.
     *
     * @static
     * @return void
     */
    public static function deactivate()
    {
        //
    }


    function changeFromAddress($original_email_address)
    {
        return 'noreply@kokeilunpaikka.fi';
    }


    function allowedBlockTypes($allowed_blocks, $block_editor_context)
    {
        $allowed_blocks = [
            'core/columns',
            'core/paragraph',
            'core/heading',
            'core/subhead',
            'core/list',
            'core/image',
            'core-embed/youtube'
        ];
        return $allowed_blocks;
    }

    function registerStrings()
    {
        require_once(get_plugin_dir() . '/includes/strings.php');
    }
}
