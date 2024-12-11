<?php

namespace Bojaghi\SeedObjects;

/**
 * Insert seed users
 *
 * It creates only one user per user_login.
 *
 * @see wp_insert_user()
 */
class UserSeeds extends Seeds
{
    public function add(): void
    {
        foreach ($this->items as $item) {
            if (!isset($item['user_login'])) {
                continue;
            }

            $user = get_user_by('login', $item['user_login']);
            if ($user && $user->exists()) {
                continue;
            }

            wp_insert_user($item);
        }
    }

    public function remove(): void
    {
        foreach ($this->items as $item) {
            if (isset($item['user_login'])) {
                $user = get_user_by('login', $item['user_login']);
                if ($user && $user->exists()) {
                    wp_delete_user($user->ID);
                }
            }
        }
    }
}
