<?php

namespace Sofokus\Plugin;

/**
 * Class Block
 * @package Sofokus\Plugin
 *
 * Abstrack class for Gutenberg blocks.
 * Advanced Custom Fields Pro is required for this to work.
 *
 * Just extend this class, call parent constructor and implement registerFields function
 */
abstract class Block
{

    private $name;
    private $args;

    function __construct($name, $args)
    {
        $this->name = $name;
        $this->args = $args;

        add_action('acf/init', [$this, 'registerBlock']);
        add_action('acf/init', [$this, 'registerFields']);

        add_filter('allowed_block_types_all', [$this, 'allowBlockType'], 11, 2);
    }

    /**
     * Register block via acf_register_block function.
     */
    public function registerBlock()
    {
        if (function_exists('acf_register_block')) {

            $args = [];
            $args['render_callback'] = [$this, 'renderBlock'];
            $args['name'] = $this->name;

            $args = array_merge($args, $this->args);

            acf_register_block($args);
        }
    }

    /**
     * Render block template.
     */
    function renderBlock()
    {
        if (file_exists(STYLESHEETPATH . '/templates/blocks/content-' . $this->name . '.php')) {
            include(STYLESHEETPATH . '/templates/blocks/content-' . $this->name . '.php');
        }
    }

    /**
     * Add block to allowed blocks.
     *
     * @param $allowed_blocks
     * @param $post
     * @return array
     */
    function allowBlockType($allowed_blocks, $block_editor_context)
    {
        if (is_array($allowed_blocks)) {
            array_push($allowed_blocks, 'acf/' . $this->name);
        } else {
            $allowed_blocks = [];
            array_push($allowed_blocks, 'acf/' . $this->name);
        }
        return $allowed_blocks;
    }

    /**
     * Register ACF custom fields.
     * @return mixed
     */
    abstract function registerFields();
}
