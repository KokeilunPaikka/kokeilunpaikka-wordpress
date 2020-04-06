<?php
declare(strict_types=1);

/**
 * Plugin name: WP SMTP Addin
 * Description: Provides SMTP configuration for WordPress mailing.
 * Author: Sofokus/Otto Rask
 */

if (!defined('ABSPATH') || !function_exists('\\add_action')) {
    exit;
}

add_action('phpmailer_init', function ($phpmailer)
{
    if (!defined('WP_SMTP_ENABLE') || !WP_SMTP_ENABLE) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = WP_SMTP_HOST;
    $phpmailer->SMTPAuth   = WP_SMTP_AUTH;
    $phpmailer->Port       = WP_SMTP_PORT;
    $phpmailer->Username   = WP_SMTP_USER;
    $phpmailer->Password   = WP_SMTP_PASS;
    $phpmailer->SMTPSecure = WP_SMTP_SECURE;
    $phpmailer->From       = WP_SMTP_FROM;
    $phpmailer->FromName   = WP_SMTP_FROM_NAME;
});
