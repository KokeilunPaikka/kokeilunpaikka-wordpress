<?php
declare(strict_types=1);

namespace App\Lib;

use Composer\Script\Event;

class Installer
{
    public static function installThemeAndPlugin(Event $event)
    {
        if (file_exists(getcwd() . '/.not_installed')) {
            exec("git clone git@bitbucket.org:sofokus/wp-beissi-plugin.git ./packages/plugin");
            exec("git clone git@bitbucket.org:sofokus/wp-beissi-theme.git ./packages/theme");

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $path = getcwd() . '/packages/plugin/.git/';
                exec("rd /s /q \"" . $path . "\"");

                $path = getcwd() . '/packages/theme/.git/';
                exec("rd /s /q \"" . $path . "\"");
            } else {
                $path = getcwd() . '/packages/plugin/.git/';
                exec("rm -rf " . $path);

                $path = getcwd() . '/packages/theme/.git/';
                exec("rm -rf " . $path);
            }

            unlink(getcwd() . "/.not_installed");
        }
    }
}
