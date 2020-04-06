<?php
declare(strict_types=1);

namespace App\Lib;

use Composer\Script\Event;

class UploadMove
{
    public static function uploadMoveFrom(Event $event)
    {
        if (file_exists(getcwd() . '/public_html/wp-content/uploads/')) {
            rename(getcwd() . '/public_html/wp-content/uploads/', getcwd() . '/uploads/');
        }
    }

    public static function uploadMoveTo(Event $event)
    {
        if (file_exists(getcwd() . '/uploads/')) {
            rename(getcwd() . '/uploads/', getcwd() . '/public_html/wp-content/uploads/');
        }
    }
}
