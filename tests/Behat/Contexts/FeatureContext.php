<?php
declare(strict_types=1);

namespace App\Tests\Behat\Contexts;

use Behat\MinkExtension\Context\MinkContext;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * Class FeatureContext
 *
 * @package App\Tests\Behat\Contexts
 */
class FeatureContext extends MinkContext
{
    /**
     * Internal posts cache.
     *
     * @access protected
     * @var \WP_Post[]
     */
    protected $post_cache = [];

    /**
     * Get all available posts.
     *
     * @access protected
     * @return \WP_Post[]
     */
    protected function getAllPosts(bool $refetch = false) : array
    {
        if (!empty($this->post_cache) && !$refetch) {
            return $this->post_cache;
        }

        $this->post_cache = (new \WP_Query([
            'post_type' => 'any',
            'post_status' => 'all'
        ]))->posts;

        return $this->post_cache;
    }

    /**
     * @Then the response contains no PHP errors
     */
    public function responseDoesNotContainPhpErrors()
    {
        $this->assertResponseNotContains('PHP Notice');
        $this->assertResponseNotContains('PHP Warning');
        $this->assertResponseNotContains('PHP Error');
        $this->assertResponseNotContains('PHP Fatal');
        $this->assertResponseNotContains('Fatal error');
    }

    /**
     * @Then the response contains no database errors
     */
    public function responseDoesNotContainDatabaseErrors()
    {
        $this->assertResponseNotContains('Error establishing a database connection');
    }

    /**
     * @Then the application should be in test running mode
     */
    public function applicationIsInTestMode()
    {
        return file_exists(app_root() . '/.running_tests');
    }

    /**
     * Check is a plugin is active.
     *
     * @Then the plugin :plugin should be active
     */
    public function thePluginShouldBeActive($plugin)
    {
        $plugins = get_option('active_plugins', []);

        PHPUnit::assertNotEmpty($plugins);
        PHPUnit::assertContains($plugin, $plugins);
    }

    /**
     * Check that a post with certain title exists.
     *
     * @Then post titled :title should exist
     */
    public function postWithTitleShouldExist($title)
    {
        $posts = $this->getAllPosts();

        $posts = array_filter($posts, function (\WP_Post $p) use ($title) {
            return $p->post_title === $title;
        });

        PHPUnit::assertNotEmpty($posts);

        $post = array_shift($posts);

        PHPUnit::assertTrue($post instanceof \WP_Post);
        PHPUnit::assertEquals($title, $post->post_title);
    }
}
