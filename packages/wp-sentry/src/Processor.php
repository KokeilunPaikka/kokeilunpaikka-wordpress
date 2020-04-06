<?php
declare(strict_types=1);

namespace Sofokus\WpSentry;

/**
 * Class Processor
 *
 * @package Sofokus\WpSentry
 */
class Processor extends \Raven_Processor
{
    /**
     * Process and sanitize data, modifying the existing value if necessary.
     *
     * @param array $data Array of log data
     *
     * @return void
     */
    public function process(&$data)
    {
        // First check whether WP is loaded or not.
        if (!function_exists('current_filter')) {
            return;
        }

        $this->processUser($data['user']);
        $this->processTags($data['tags']);
        $this->processExtra($data['extra']);
    }

    /**
     * Process user context.
     *
     * @access protected
     *
     * @param $user_data
     *
     * @return void
     */
    protected function processUser(&$user_data)
    {
        if (!is_array($user_data)) {
            $user_data = [];
        }

        //
    }

    /**
     * Process tag context.
     *
     * @access protected
     *
     * @param $tags_data
     *
     * @return void
     */
    protected function processTags(&$tags_data)
    {
        if (!is_array($tags_data)) {
            $tags_data = [];
        }

        //
    }

    /**
     * Process extra context.
     *
     * @access protected
     *
     * @param $extra_data
     *
     * @return void
     */
    protected function processExtra(&$extra_data)
    {
        if (!is_array($extra_data)) {
            $extra_data = [];
        }

        if (array_key_exists('wp_current_hook', $extra_data)) {
            $extra_data['wp_current_hook'] = (string) current_filter();
        }
    }

}
