<?php

namespace Bojaghi\SeedObjects\Tests;

use Bojaghi\SeedObjects\UserSeeds;
use WP_UnitTestCase;
use WP_User;

class TestUserSeeds extends WP_UnitTestCase
{
    public function testAddRemove(): void
    {
        global $wpdb;

        $items = [
            [
                'user_login' => 'test_user_1',
                'user_email' => 'test_user_1@example.com',
                'user_pass'  => 'password',
                'role'       => 'subscriber',
                'meta_input' => ['_seed' => '1'],
            ],
            [
                'user_login' => 'test_user_2',
                'user_email' => 'test_user_2@example.com',
                'user_pass'  => 'password',
                'role'       => 'subscriber',
                'meta_input' => ['_seed' => '1'],
            ],
        ];

        $seeds = new UserSeeds($items);

        // Test add()
        $seeds->add();

        $users = get_users('meta_key=_seed&meta_value=1&orderby=ID&order=ASC');
        $this->assertIsArray($users);
        $this->assertCount(2, $users);

        $this->assertInstanceOf(WP_User::class, $users[0]);
        $this->assertEquals('test_user_1', $users[0]->user_login);
        $this->assertEquals('test_user_1@example.com', $users[0]->user_email);

        $this->assertInstanceOf(WP_User::class, $users[1]);
        $this->assertEquals('test_user_2', $users[1]->user_login);
        $this->assertEquals('test_user_2@example.com', $users[1]->user_email);

        // Repeated add() calls do not affect the total number of seed users.
        $seeds->add();
        $seeds->add();

        // Get count by raw query, refusing to be affected by cache.
        $query = "SELECT COUNT(ID) FROM $wpdb->users AS u" .
            " INNER JOIN $wpdb->usermeta AS um ON um.user_id = u.ID" .
            " WHERE um.meta_key='_seed' AND um.meta_value='1'";
        $count = (int)$wpdb->get_var($query);
        $this->assertEquals(2, $count);

        // Test remove().
        $seeds->remove();

        $users = get_users('meta_key=_seed&meta_value=1&orderby=ID&order=ASC');
        $this->assertIsArray($users);
        $this->assertCount(0, $users);
    }
}