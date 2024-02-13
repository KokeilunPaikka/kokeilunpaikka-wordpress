<?php
$cookiebot_ID = get_field('id');
$cookiebot_lang = get_field('lang');

if (!is_admin() && isset($cookiebot_ID)) { ?>
    <script id="CookieDeclaration" data-culture="<?= $cookiebot_lang ?>"
            src="https://consent.cookiebot.com/<?= $cookiebot_ID ?>/cd.js" type="text/javascript" async>
    </script>
<?php }
