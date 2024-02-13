<?php

namespace Sofokus\Plugin\Blocks;

use Sofokus\Plugin\Block;

class Cookiebot extends Block
{
    function __construct()
    {
        $args = [
            'title' => __('Cookiebot', 'plugin'),
            'category' => 'common',
            'icon' => 'align-center',
            'keywords' => ['text'],
            'mode' => 'edit'
        ];
        parent::__construct('cookiebot', $args);
    }

    function registerFields()
    {
        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_5f227a30a8101',
                'title' => 'Cookiebot',
                'fields' => array(
                    array(
                        'key' => 'field_5f227a3bfeba7',
                        'label' => 'Cookiebot ID',
                        'name' => 'id',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_57j48gkd8b7fka',
                        'label' => 'Language',
                        'name' => 'lang',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'fi' => 'FI',
                            'en' => 'EN',
                        ),
                        'default_value' => 'fi',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/cookiebot',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ));

        endif;
    }
}
