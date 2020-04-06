<?php

/**
 * users.php
 *
 * Users to seed for tests. Array of arrays containing `wp_insert_user` friendly
 * user data.
 */

return [
    [
        'user_login' =>  'testuserone',
        'user_email' => 'testuserone@example.com',
        'user_pass' => 'testpasswordone',
        'first_name' => 'Testuser',
        'last_name' => 'One',
        'role' => 'editor'
    ],
    [
        'user_login' =>  'testusertwo',
        'user_email' => 'testusertwo@example.com',
        'user_pass' => 'testpasswordtwo',
        'first_name' => 'Testuser',
        'last_name' => 'Two',
        'role' => 'author'
    ],
    [
        'user_login' =>  'testuserthree',
        'user_email' => 'testuserthree@example.com',
        'user_pass' => 'testpasswordthree',
        'first_name' => 'Testuser',
        'last_name' => 'Three',
        'role' => 'subscriber'
    ]
];
