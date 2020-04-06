<?php

namespace Sofokus\Theme\Tests\PHPUnit;

/**
 * Class ThemeTestCase
 *
 * @package Sofokus\Theme\Tests\PHPUnit
 */
class ThemeTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * ThemeTestCase constructor.
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        global $wpdb;

        parent::__construct($name, $data, $dataName);

        $this->wpdb = $wpdb;
    }
    /**
     * Set up before testing.
     *
     * Start database transactions.
     */
    public function setUp()
    {
        global $wpdb;

        $wpdb->query('START TRANSACTION;');
    }

    /**
     * Teardown after testing.
     *
     * Rollback database transaction.
     */
    public function tearDown()
    {
        global $wpdb;

        $wpdb->query('ROLLBACK');
    }
}
