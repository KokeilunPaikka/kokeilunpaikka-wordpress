<?php
declare(strict_types=1);

namespace Sofokus\WpSentry;

/**
 * Class Sentry
 *
 * @package Sofokus\WpSentry
 */
class Sentry
{
    /**
     * Eww, singleton.
     *
     * @static
     * @access protected
     * @var self
     */
    protected static $instance;

    /**
     * Sentry configuration.
     *
     * @access protected
     * @var array
     */
    protected $config;

    /**
     * Sentry Raven client instance.
     *
     * @access protected
     * @var \Raven_Client
     */
    protected $client;

    /**
     * Get instance.
     *
     * @static
     * @return self
     */
    public static function instance() : self
    {
        if (self::$instance instanceof Sentry) {
            return self::$instance;
        }

        self::$instance = new self();

        return self::$instance;
    }

    /**
     * Configuration for Sentry.
     *
     * @param string $dsn Sentry application DNS for project
     *
     * @return self
     */
    public function initialize(string $dsn) : self
    {
        if (!preg_match('%^https?://.+$%', $dsn)) {
            throw new \InvalidArgumentException('Invalid DSN format given for Sentry');
        }

        $sentry_options = [
            'dsn' => $dsn,
            'processors' => [
                \Raven_SanitizeDataProcessor::class,
                Processor::class
            ]
        ];

        $this->client = new \Raven_Client($sentry_options);

        return $this;
    }

    /**
     * Set the application environment tag.
     *
     * @param string $env
     *
     * @return Sentry
     */
    public function setEnvironment(string $env) : self
    {
        $this->client->setEnvironment($env);

        return $this;
    }

    /**
     * Set the application path for Sentry.
     *
     * @param string $path
     *
     * @return Sentry
     */
    public function setAppPath(string $path) : self
    {
        $this->client->setAppPath($path);

        return $this;
    }

    /**
     * Set the release to track in Sentry.
     *
     * @param string $release
     *
     * @return Sentry
     */
    public function setRelease(string $release) : self
    {
        $this->client->setRelease($release);

        return $this;
    }

    /**
     * Register/install error and exception handlers.
     *
     * @return void
     */
    public function register()
    {
        $this->client->install();
    }

    /**
     * Setup the environment context to populate Sentry events with (in addition to
     * the built-in data Sentry gathers).
     *
     * @return bool
     */
    public function setupContext() : bool
    {
        if (!function_exists('add_action')) {
            // WordPress not loaded, fail.
            return false;
        }

        add_action('init', function () {
            $this->setupUserContext();
            $this->setupTagsContext();
            $this->setupExtraContext();
        });

        return true;
    }

    /**
     * Initialize Sentry context for user data.
     *
     * @access protected
     * @return void
     */
    protected function setupUserContext()
    {
        if (!is_user_logged_in()) {
            return;
        }

        $current_user = wp_get_current_user();

        // Do not send sensitive info or personalizable data to be a better person.
        $this->client->user_context([
            'id' => $current_user->ID,
            'roles' => $current_user->roles
        ]);
    }

    /**
     * Initialize Sentry context for additional tags.
     *
     * @access protected
     * @return void
     */
    protected function setupTagsContext()
    {
        //
    }

    /**
     * Initialize Sentry context for extra context.
     *
     * @access protected
     * @return void
     */
    protected function setupExtraContext()
    {
        $this->client->extra_context([
            'wp_current_hook' => 'unknown'
        ]);
    }

}
