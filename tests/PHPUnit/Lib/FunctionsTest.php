<?php
declare(strict_types=1);

namespace App\Tests\Lib;

use PHPUnit\Framework\TestCase;

/**
 * Class FunctionsTest
 *
 * @package App\Tests\Lib
 */
class FunctionsTest extends TestCase
{
    /**
     * Get actual app root when calculated from the tests directory.
     *
     * @access protected
     * @return string
     */
    protected function getActualAppRoot() : string
    {
        return dirname(__DIR__, 3);
    }

    /**
     *
     */
    public function test_env_function()
    {
        putenv('HELLO_WORLD=hello world');
        putenv('FOOBAR=true');
        putenv('THIS_IS_FALSE="false"');
        putenv('NUMERICAL_ONE=1234');
        putenv('NUMERICAL_TWO=1337');

        $this->assertEquals('hello world', env('HELLO_WORLD'));
        $this->assertEquals(true, env('FOOBAR'));
        $this->assertEquals(false, env('THIS_IS_FALSE'));
        $this->assertEquals(1234, env('NUMERICAL_ONE'));
        $this->assertEquals(1337, env('NUMERICAL_TWO'));
        $this->assertEquals('defaulted', env('HELLO_WORLD_MISSING', 'defaulted'));
        $this->assertEquals(null, env('SHOULD_BE_NULL'));
    }

    /**
     *
     */
    public function test_app_root_function()
    {
        $this->assertEquals($this->getActualAppRoot(), app_root());
    }

    /**
     *
     */
    public function test_wp_root_function()
    {
        $this->assertEquals($this->getActualAppRoot() . '/public_html', wp_root());
    }

    /**
     *
     */
    public function test_wp_content_root_function()
    {
        $this->assertEquals($this->getActualAppRoot() . '/public_html/wp-content', wp_content_root());
    }
}
