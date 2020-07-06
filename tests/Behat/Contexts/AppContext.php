<?php

namespace App\Tests\Behat\Contexts;

use Behat\Gherkin\Node\TableNode;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PHPUnit\Framework\Assert as PHPUnit;
use Sofokus\WordPressBehatExtension\Context\WordPressContext;
use Dotenv\Dotenv;

/**
 * Class AppContext
 *
 * @package App\Tests\Behat\Contexts
 */
class AppContext extends WordPressContext
{
    /**
     * Application root.
     *
     * @static
     * @access protected
     * @var string
     */
    protected static $appRoot = null;

    /**
     * Marker file to run tests.
     *
     * @static
     * @access protected
     * @var string
     */
    protected static $markerFile;

    /**
     * Has a test installation been seeded.
     *
     * @access protected
     * @var bool
     */
    protected $seeded = false;

    /**
     * @BeforeSuite
     *
     * @param \Behat\Testwork\Hook\Scope\BeforeSuiteScope $scope
     */
    public static function beforeSuite(BeforeSuiteScope $scope)
    {
        self::$appRoot = dirname(__DIR__, 3);
        self::$markerFile = self::$appRoot . '/.tests_running';

        $_ENV['RUNNING_AUTOMATED_TESTS'] = true;

        if (!file_exists(self::$markerFile)) {
            touch(self::$markerFile);
        }

        // Remove the marker with a shutdown function in case AfterSuite does not run
        // properly
        register_shutdown_function(function () {
            if (file_exists(self::$markerFile)) {
                unlink(self::$markerFile);
            }
        });

        if (!is_dir(self::$appRoot . '/tmp/mail')) {
            mkdir(self::$appRoot . '/tmp/mail', 0777, true);
        }

        self::loadEnv();
    }

    /**
     * @AfterSuite
     *
     * @param \Behat\Testwork\Hook\Scope\AfterSuiteScope $scope
     */
    public static function afterSuite(AfterSuiteScope $scope)
    {
        if (file_exists(self::$markerFile)) {
            unlink(self::$markerFile);
        }
    }

    /**
     * Load environment variables for use with WordPress.
     *
     * @access protected
     * @return void
     */
    protected static function loadEnv()
    {
        global $table_prefix;

        //$dotenv = \Dotenv\Dotenv::create(self::$appRoot, '.env.testing');
        $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(self::$appRoot, '.env.testing');

        $dotenv->load();

        if (empty($_SERVER['SERVER_NAME'])) {
            $_SERVER['SERVER_NAME'] = env('APP_DOMAIN', 'localhost');
        }

        $table_prefix = env('DB_PREFIX', 'testwp_');

        define('APP_ENABLE_CACHE', false);
        define('DB_NAME', env('DB_NAME'));
        define('DB_USER', env('DB_USER'));
        define('DB_PASSWORD', env('DB_PASSWORD'));
        define('DB_HOST', env('DB_HOST', '127.0.0.1'));
        define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
        define('DB_COLLATE', env('DB_COLLATION', 'utf8mb4_unicode_ci'));
    }

    /**
     * Check if WP core is installed.
     *
     * @access protected
     * @return bool
     */
    protected function isWpInstalled(): bool
    {
        return is_blog_installed();
    }

    /**
     * Has the test installation been seeded.
     *
     * @access protected
     * @return bool
     */
    protected function seeded(): bool
    {
        return $this->seeded;
    }

    /**
     * Seed testing data.
     *
     * @access protected
     * @return void
     */
    protected function seed()
    {
        $this->clearInstallation();
        $this->seedPlugins();
        $this->seedPosts();
        $this->seedOptions();
        $this->seedUsers();

        $this->seeded = true;
    }

    /**
     * In case baseline WP installation installs crap into DB, clear those here.
     *
     * @access protected
     * @return void
     */
    protected function clearInstallation()
    {
        $post_ids = (new \WP_Query([
            'fields' => 'ids',
            'post_type' => 'all',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'no_found_rows' => 1
        ]))->posts;

        foreach ($post_ids as $id) {
            wp_delete_post($id, true);
        }
    }

    /**
     * Seed plugins which should be activated for testing.
     *
     * @access protected
     * @return void
     */
    protected function seedPlugins()
    {
        $plugins = self::$appRoot . '/tests/data/plugins.php';
        $plugins = include($plugins);

        $plugins_to_activate = array_filter(array_map(function ($plugin_file) {
            if (!file_exists(wp_content_root() . '/plugins/' . $plugin_file)) {
                return false;
            }

            return $plugin_file;
        }, $plugins));

        update_option('active_plugins', $plugins_to_activate);
    }

    /**
     * Seed WordPress options table.
     *
     * @access protected
     * @return void
     */
    protected function seedOptions()
    {
        $options = self::$appRoot . '/tests/data/options.php';
        $options = include($options);

        foreach ($options as $key => $value) {
            update_option($key, $value);
        }
    }

    /**
     * Seed test users.
     *
     * @access protected
     * @return void
     */
    protected function seedUsers()
    {
        $users = self::$appRoot . '/tests/data/users.php';
        $user_data = include($users);

        foreach ($user_data as $user) {
            $inserted = wp_insert_user($user);

            PHPUnit::assertNotInstanceOf(\WP_Error::class, $inserted, 'Could not seed test user');
        }
    }

    /**
     * Seed WordPress posts, pages, and custom post items.
     *
     * @access protected
     * @return void
     */
    protected function seedPosts()
    {
        $posts = self::$appRoot . '/tests/data/posts.php';
        $post_data = include($posts);

        foreach ($post_data as $post) {
            $inserted = wp_insert_post($post);

            PHPUnit::assertNotInstanceOf(\WP_Error::class, $inserted, 'Error seeding post for tests');
            PHPUnit::assertNotEquals(0, $inserted, 'Error seeding post for tests');
        }
    }

    /**
     * @Given /^\w+ have a wordpress installation$/
     */
    public function installWordPress(TableNode $table = null)
    {
        if (!$this->isWpInstalled()) {
            parent::installWordPress($table);
        }

        if (!$this->seeded()) {
            $this->seed();
        }
    }
}
